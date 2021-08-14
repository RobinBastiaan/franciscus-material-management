<?php

namespace App\Command;

use App\Entity\Item;
use App\Entity\Tag;
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
                $this->addItem($row);
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
     * Adds an item to the database, but only if not already in the database.
     */
    private function addItem($row): void
    {
        $value = (float)str_replace(',', '', ltrim($row['Originele koopwaarde'], '€'));

        try {
            $dateTime = date('Y/m/d', strtotime($row['Datum gekocht'])); // use European data format
            $dateTime = (new DateTime($dateTime));
        } catch (Exception $e) {
            throw new UnexpectedValueException('Failed to parse time string! (' . $row['Datum gekocht'] . ')', 'DateTime');
        }

        /** @var Item $itemFromDatabase */
        $itemFromDatabase = $this->em->getRepository(Item::class)
            ->findOneBy([
                'name' => $row['Naam'],
            ]);
        $item = clone $itemFromDatabase;
        $this->em->detach($item);

        if ($item == null) {
            $item = new Item; // add new Item instead of updating existing
        }

        $item
            ->setAmount((int)$row['Hoeveel'])
            ->setName($row['Naam'])
            ->setDescription($row['Omschrijving'])
            ->setType($row['Type'])
            ->setDateBought($dateTime)
            ->setValue($value)
            ->setStatus($row['Status'])
            ->setLocation($row['Locatie']);

        if ($item == $itemFromDatabase) {
            return; // nothing has changed; no update required
        }

        /** @var Item $item */
        $item = $this->em->merge($item);

        $this->updateTags($item, $row['Tags']);

        $this->em->persist($item);
        $this->em->flush();
        $this->em->clear();
    }

    private function updateTags(Item $item, $tags): void
    {
        $tags = array_map('trim', explode(',', $tags));

        /** @var TagRepository $tagRepository */
        $tagRepository = $this->em->getRepository(Tag::class);

        foreach ($tags as $tag) {
            $persistedTag = $tagRepository->findOneByName($tag);

            if (!isset($persistedTag)) {
                $persistedTag = new Tag();
                $persistedTag->setName($tag);
                $this->em->persist($persistedTag);
            }

            $persistedTag->addItem($item);
            $item->addTag($persistedTag);
        }
    }
}
