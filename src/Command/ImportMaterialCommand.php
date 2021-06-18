<?php

namespace App\Command;

use App\Entity\Item;
use App\Entity\Tag;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use http\Exception\UnexpectedValueException;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportMaterialCommand extends Command
{
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
        $this->setDescription(self::$defaultDescription);
    }

    /**
     * @throws \League\Csv\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Attempting data import...');

        $reader = Reader::createFromPath('%kernel.root_dir%/../data/materiaallijst.csv');
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
     *
     * @param $row
     */
    private function addItem($row): void
    {
        $item = $this->em->getRepository(Item::class)
            ->findOneBy([
                'name' => $row['Naam'],
            ]);

        if ($item !== null) { // ensure not added twice
            return;
        }

        try {
            $dateTime = date('Y/m/d', strtotime($row['Datum gekocht'])); // use European data format
            $dateTime = (new DateTime($dateTime));
        } catch (Exception $e) {
            throw new UnexpectedValueException('Failed to parse time string! (' . $row['Datum gekocht'] . ')');
        }

        $value = (float)str_replace(',', '', ltrim($row['Originele koopwaarde'], '€'));

        $item = new Item;
        $item
            ->setAmount($row['Hoeveel'])
            ->setName($row['Naam'])
            ->setDescription($row['Omschrijving'])
            ->setType($row['Type'])
            ->setDateBought($dateTime)
            ->setValue($value)
            ->setStatus($row['Status'])
            ->setLocation($row['Locatie']);
        $this->em->persist($item);

        $tags = array_map('trim', explode(',', $row['Tags']));

        foreach ($tags as $tag) {
            $persistedTag = $this->em->getRepository(Tag::class)->findOneByName($tag);

            if (!isset($persistedTag)) {
                $persistedTag = new Tag();
                $persistedTag->setName($tag);
                $this->em->persist($persistedTag);
            }

            $persistedTag->addItem($item);
            $item->addTag($persistedTag);
        }

        $this->em->flush();
        $this->em->clear();
    }
}
