<?php

namespace App\Front\DataFixtures;

use App\Front\Entity\Ajouter;
use App\Front\Entity\Commande;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommandeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++) {
            $commande = new Commande();
            $commande->setUtilisateur($this->getReference("user{$i}"))
                ->setStatut('test')
                ->setAdresse($this->getReference("adress{$i}"));
            $manager->persist($commande);
            $ajouter = new Ajouter();
            $ajouter->setCommande($commande)
                ->setQte(rand(1,10))
                ->setDate(new \DateTime('now'))
                ->setProduit($this->getReference("product{$i}"));
            $manager->persist($ajouter);
        }
        $manager->flush();

    }

    public function getDependencies()
    {
        return [UtilisateurFixtures::class, ProductFixtures::class];
    }
}
