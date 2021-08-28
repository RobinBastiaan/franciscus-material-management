<?php

namespace App\DataFixtures;

use App\Entity\AgeGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AgeGroupFixtures extends Fixture
{
    private array $ageGroups = [
        [
            'name' => 'Bevers',
        ],
        [
            'name' => 'Parcival',
        ],
        [
            'name' => 'Leonardus',
        ],
        [
            'name' => 'Scouts',
        ],
        [
            'name' => 'Explorers',
        ],
        [
            'name' => 'Roverscouts',
        ],
        [
            'name' => 'Stam',
        ],
        [
            'name' => 'Bestuur',
        ],
        [
            'name' => 'Overig',
        ],
    ];

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->ageGroups as $anAgeGroup) {
            $ageGroup = new AgeGroup();
            $ageGroup->setName($anAgeGroup['name']);

            $manager->persist($ageGroup);
            $manager->flush();

            $this->addReference('age_group_' . $ageGroup->getName(), $ageGroup);
        }

        $manager->flush();
    }
}
