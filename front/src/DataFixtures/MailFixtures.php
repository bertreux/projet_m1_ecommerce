<?php

namespace App\Front\DataFixtures;

use App\Front\Entity\Mail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MailFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++) {
             $mail = new Mail();
             $mail->setUtilisateur($this->getReference("user{$i}"))
                 ->setObjet($faker->name)
                 ->setText($faker->paragraph);
             $manager->persist($mail);
        }
            $manager->flush();
    }

    public function getDependencies()
    {
        return [UtilisateurFixtures::class];
    }
}
