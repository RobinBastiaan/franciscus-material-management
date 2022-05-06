<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private array $categories = [
        [
            'name' => 'Tent',
            'icon' => 'fa-tent',
        ],
        [
            'name' => 'Krat',
            'icon' => 'fa-box-taped',
        ],
        [
            'name' => 'Gasfles',
            'icon' => 'fa-gas-pump',
        ],
        [
            'name' => 'Koken en Stoken',
            'icon' => 'fa-utensils',
        ],
    ];

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->categories as $aCategory) {
            $category = new Category();
            $category->setName($aCategory['name']);
            $category->setIcon($aCategory['icon']);

            $manager->persist($category);
            $manager->flush();

            $this->addReference('category_' . $category->getName(), $category);
        }

        $manager->flush();
    }
}
