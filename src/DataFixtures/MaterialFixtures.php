<?php

namespace App\DataFixtures;

use App\Entity\Location;
use App\Entity\Material;
use App\Entity\Tag;
use App\Repository\LocationRepository;
use App\Repository\TagRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;

/*
 * Load the MaterialFixtures from the example material list file.
 */

class MaterialFixtures extends Fixture
{
    const DEFAULT_FILE_NAME = 'materiaallijst';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        $reader = Reader::createFromPath('%kernel.root_dir%/../data/' . self::DEFAULT_FILE_NAME . '.csv');
        $reader->setHeaderOffset(0);
        $results = $reader->getrecords();

        $this->em->getConnection()->getConfiguration()->setSQLLogger();

        foreach ($results as $row) {
            $this->addMaterial($row);
        }

        $this->em->flush();
        $this->em->clear();
    }

    /**
     * Adds a material to the database, but only if not already in the database.
     */
    private function addMaterial($row): void
    {
        $dateTime = date('Y/m/d', strtotime($row['Koopdatum'])); // use European data format
        $dateTime = (new DateTime($dateTime));

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
            ->setType(trim($row['Type']))
            ->setDateBought($dateTime)
            ->setValue((float)str_replace(',', '', ltrim($row['Originele koopwaarde'], '€')))
            ->setManufacturer(trim($row['Fabrikant']))
            ->setDepreciationYears((int)$row['Afschrijvingsjaren'])
            ->setState(trim($row['Staat']));

        if ($material == $materialFromDatabase) {
            return; // nothing has changed; no update required
        }

        /** @var Material $material */
        $material = $this->em->merge($material);

        $this->updateLocation($material, $row['Locatie']);
        $this->updateTags($material, $row['Tags']);

        $this->em->persist($material);
        $this->em->flush();
        $this->addReference('material_' . $material->getName(), $material);
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
