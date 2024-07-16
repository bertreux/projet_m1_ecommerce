<?php

namespace App\Front\DataFixtures;

use App\Front\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $url_image = [
            'images/Alter-Chronos.jpg',
            'images/Diablo-Nemesis-X-D.jpg',
            'images/Flash-Sagittario.jpg',
            'images/Galaxy-Pegasus.jpg',
            'images/Glide-Ragnaruk-Wheel-Revolve-1S.jpg',
            'images/Gravity-Destroyer.jpg',
            'images/Killer-Deathscyther.jpg',
            'images/Kreis-Cygnus.jpg',
            'images/Lucifer-the-End-Kou-Drift.jpg',
            'images/Meteo-L-Drago.jpg',
            'images/Phantom-Orion-BD.jpg',
            'images/Poison-Serpent.jpg',
            'images/Thermal-Lacerta.jpg',
            'images/Variant-Lucifer-Mobius-2D.jpg',
            'images/Venom-Diabolos-Vanguard-Bullet.jpg',
            'images/Beyblade-Metal-Fusion-S1.jpg',
            'images/Beyblades-saison-2.jpg',
            'images/Beyblades-saison-3.jpg',
            'images/Beyblades-saison-4.jpg',
            'images/Beyblades-S5.jpg'
        ];

        for ($i = 1; $i <= 10; $i++) {
            $image = new Image();
            $image->setProduit(null)
                ->setCategorie($this->getReference("category" . $i))
                ->setUrl($faker->randomElement($url_image))
                ->setPrincipal(1);
            $manager->persist($image);
        }

        for ($i = 1; $i <= 100; $i++) {
            $image = new Image();
            $image->setProduit($this->getReference("product" . $i))
                ->setCategorie(null)
                ->setUrl($faker->randomElement($url_image))
                ->setPrincipal(1);
            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class, ProductFixtures::class];
    }
}
