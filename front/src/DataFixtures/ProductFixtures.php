<?php

namespace App\Front\DataFixtures;

use App\Front\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 100; $i++) {
            $product = new Produit();
            $product->setNom("product{$i}")
                ->setPrix($faker->randomFloat(2,10,50))
                ->setStock($faker->randomNumber(3, true))
                ->setDescription($faker->paragraph);
            if($i<=5) {
                $product->setCarousel(true);
            } else {
                $product->setCarousel(false);
            }
            if($i<=10) {
                $product->setHighlander(true);
            } else {
                $product->setHighlander(false);
            }
            $product->setArriver($faker->dateTimeBetween('-2 years', 'now'))
                ->setPrioriter(rand(1, 5))
                ->setCategorie($this->getReference("category" . rand(1, 10)));
            $manager->persist($product);
            $this->addReference("product{$i}", $product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}
