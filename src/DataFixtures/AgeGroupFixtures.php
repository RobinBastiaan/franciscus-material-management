<?php

namespace App\DataFixtures;

use App\Entity\AgeGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AgeGroupFixtures extends Fixture
{
    private array $ageGroups = [
        [
            'name'  => 'Bevers',
            'color' => '#ff1919',
        ],
        [
            'name'  => 'Parcival',
            'color' => '#2db242',
        ],
        [
            'name'  => 'Leonardus',
            'color' => '#5ff009',
        ],
        [
            'name'  => 'Scouts',
            'color' => '#ffb700',
        ],
        [
            'name'  => 'Explorers',
            'color' => '#DC143C',
        ],
        [
            'name'  => 'Roverscouts',
            'color' => '#a83636',
        ],
        [
            'name'  => 'Stam',
            'color' => '#ff99cc',
        ],
        [
            'name'  => 'Bestuur',
            'color' => '#993366',
        ],
        [
            'name'  => 'Overig',
            'color' => '#808080',
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
            $ageGroup->setColor($anAgeGroup['color']);

            $manager->persist($ageGroup);
            $manager->flush();

            $this->addReference('age_group_' . $ageGroup->getName(), $ageGroup);
        }

        $manager->flush();
    }
}
