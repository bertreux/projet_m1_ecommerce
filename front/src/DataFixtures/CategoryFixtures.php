<?php

namespace App\Front\DataFixtures;

use App\Front\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $category = new Categorie();
            $category->setNom("category{$i}");
            $manager->persist($category);
            $this->addReference("category{$i}", $category);
        }

        $manager->flush();
    }
}
