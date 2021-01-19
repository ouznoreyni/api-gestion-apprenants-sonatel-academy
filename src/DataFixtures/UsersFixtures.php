<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\CommunityManager;
use App\Entity\Formateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        //admin
        for ($i = 0; $i < 3; $i++) {
            $admin = new Admin();
            $admin->setPrenom($faker->firstName);
            $admin->setNom($faker->lastName);
            $admin->setEmail($faker->email);
            $password = $this->passwordEncoder->encodePassword($admin, 'admin');
            $admin->setPassword($password);
            $admin->setRoles(['ROLE_ADMIN']);
            $manager->persist($admin);
        }
        //formateur
        for ($i = 0; $i < 3; $i++) {
            $formateur = new Formateur();
            $formateur->setPrenom($faker->firstName);
            $formateur->setNom($faker->lastName);
            $formateur->setEmail($faker->email);
            $password = $this->passwordEncoder->encodePassword($formateur, 'formateur');
            $formateur->setPassword($password);
            $formateur->setRoles(['ROLE_FORMATEUR']);
            $manager->persist($formateur);
        }
        //apprenant
        for ($i = 0; $i < 3; $i++) {
            $apprenant = new Apprenant();
            $apprenant->setPrenom($faker->firstName);
            $apprenant->setNom($faker->lastName);
            $apprenant->setEmail($faker->email);
            $password = $this->passwordEncoder->encodePassword($apprenant, 'apprenant');
            $apprenant->setPassword($password);
            $apprenant->setRoles(['ROLE_APPRENANT']);
            $manager->persist($apprenant);
        }
        //cm
        for ($i = 0; $i < 3; $i++) {
            $cm = new CommunityManager();
            $cm->setPrenom($faker->firstName);
            $cm->setNom($faker->lastName);
            $cm->setEmail($faker->email);
            $password = $this->passwordEncoder->encodePassword($cm, 'communitymanager');
            $cm->setPassword($password);
            $cm->setRoles(['ROLE_CM']);
            $manager->persist($cm);
        }
        $manager->flush();

    }
}
