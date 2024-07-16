<?php

namespace App\Front\DataFixtures;

use App\Front\Entity\Adresse;
use App\Front\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{

    public const ADMIN = 'ADMIN_USER';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $user = new Utilisateur();
        $user->setNom('admin')
            ->setPrenom('admin')
            ->setTel($faker->phoneNumber())
            ->setEmail('admin@doe.fr')
            ->setPassword($this->hasher->hashPassword($user, 'admin0'))
            ->setRoles(['ROLE_ADMIN'])
            ->setIsVerified(true);
        $this->addReference(self::ADMIN, $user);
        $manager->persist($user);

        for ($i = 1; $i <= 10; $i++) {
            $user = new Utilisateur();
            $user->setEmail("user{$i}@doe.fr")
                ->setRoles([])
                ->setNom($faker->name())
                ->setPrenom($faker->firstName())
                ->setTel($faker->phoneNumber())
                ->setPassword($this->hasher->hashPassword($user, '000000'))
                ->setPrenom(true);
            $manager->persist($user);
            $adresse = new Adresse();
            $code_postal = $faker->postcode;
            $adresse->setUtilisateur($user)
                ->setIntitule($faker->streetAddress)
                ->setVille($faker->city)
                ->setRegion("region {$code_postal}")
                ->setCodePostal($code_postal)
                ->setPays($faker->country);
        }

        $manager->flush();
    }
}
