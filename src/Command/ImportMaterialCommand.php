<?php

namespace App\Command;

use App\Entity\Location;
use App\Entity\Material;
use App\Entity\Tag;
use App\Repository\LocationRepository;
use App\Repository\TagRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ImportMaterialCommand extends Command
{
    const DEFAULT_FILE_NAME = 'materiaallijst';
    protected static $defaultName = 'import-material';
    protected static $defaultDescription = 'Import a CSV file containing the material inventory.';
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('file_name', InputArgument::OPTIONAL, 'The name of the data file.', self::DEFAULT_FILE_NAME);
    }

    /**
     * @throws \League\Csv\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $input->getArgument('file_name');

        $io = new SymfonyStyle($input, $output);
        $io->title('Attempting data import...');

        $reader = Reader::createFromPath('%kernel.root_dir%/../data/' . $fileName . '.csv');
        $reader->setHeaderOffset(0);
        $results = $reader->getrecords();
        $io->progressStart(iterator_count($results));

        // disable SQL logging to avoid huge memory loss
        $this->em->getConnection()->getConfiguration()->setSQLLogger();

        foreach ($results as $row) {
            try {
                $this->addMaterial($row);
            } catch (Exception $e) {
                $io->error([
                    $e->getMessage(),
                    'Skipping entry with name: ' . $row['Naam'] . '!',
                ]);
            }

            $io->progressAdvance();
        }

        $this->em->flush();
        $this->em->clear();

        $io->progressFinish();
        $io->success('CSV Import has been successful!');

        return Command::SUCCESS;
    }

    /**
     * Adds a material to the database, but only if not already in the database.
     */
    private function addMaterial($row): void
    {
        try {
            $dateTime = date('Y/m/d', strtotime($row['Koopdatum'])); // use European data format
            $dateTime = (new DateTime($dateTime));
        } catch (Exception $e) {
            throw new UnexpectedValueException('Failed to parse time string! (' . $row['Koopdatum'] . ')', 'DateTime');
        }

        if (empty(trim($row['Naam']))) {
            return;
        }

        /** @var Material $materialFromDatabase */
        $materialFromDatabase = $this->em->getRepository(Material::class)
            ->findOneBy([
                'name' => trim($row['Naam']),
            ]);

        if (!empty($materialFromDatabase)) {
            $material = clone $materialFromDatabase;
            $this->em->detach($material);
        } else {
            $material = null;
        }

        if ($material == null) {
            $material = new Material; // add new Material instead of updating existing
        }

        $material
            ->setAmount((int)$row['Aantal'])
            ->setName(trim($row['Naam']))
            ->setDescription(trim($row['Korte omschrijving']))
            ->setInformation(trim($row['Uitgebreide informatie']))
            ->setCategory(trim($row['Type']))
            ->setDateBought($dateTime)
            ->setValue((float)str_replace(',', '', ltrim($row['Originele koopwaarde'], '€')))
            ->setResidualValue((float)str_replace(',', '', ltrim($row['Restwaarde'], '€')))
            ->setManufacturer(trim($row['Fabrikant']))
            ->setDepreciationYears((int)$row['Afschrijvingsjaren'])
            ->setState(!empty($row['Staat']) ? trim($row['Staat']) : 'Goed');

        /** @var Material $material */
        $material = $this->em->merge($material);

        $this->updateLocation($material, $row['Opslaglocatie']);
        $this->updateTags($material, $row['Tags']);

        if ($material == $materialFromDatabase) {
            return; // nothing has changed; no update required
        }

        $this->em->persist($material);
        $this->em->flush();
        $this->em->clear();
    }

    private function updateLocation(Material $material, $location): void
    {
        /** @var LocationRepository $locationRepository */
        $locationRepository = $this->em->getRepository(Location::class);

        $persistedLocation = $locationRepository->findOneByName($location);

        if (!isset($persistedLocation)) {
            $persistedLocation = new Location();
            $persistedLocation->setName($location);
            $persistedLocation->addMaterial($material);
            $this->em->persist($persistedLocation);
            $this->em->flush();
        }

        $material->setLocation($persistedLocation);
    }

    private function updateTags(Material $material, $tags): void
    {
        $tags = array_map('trim', explode(',', $tags));

        /** @var TagRepository $tagRepository */
        $tagRepository = $this->em->getRepository(Tag::class);

        foreach ($tags as $tag) {
            $persistedTag = $tagRepository->findOneByName($tag);

            if (!isset($persistedTag)) {
                if ($tag === '') {
                    continue;
                }

                $persistedTag = new Tag();
                $persistedTag->setName($tag);
                $persistedTag->addMaterial($material);
                $this->em->persist($persistedTag);
                $this->em->flush();
            }

            $material->addTag($persistedTag);
        }
    }
}
