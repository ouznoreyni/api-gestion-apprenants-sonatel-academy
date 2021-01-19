<?php

namespace App\DataFixtures;

use App\DataFixtures\ProfilFixtures;
use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\CommunityManager;
use App\Entity\Formateur;
use App\Entity\User;
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
        for ($i = 0; $i < 20; $i++) {
            $admin = new Admin();
            $admin = $this->setUsersFields($admin, $faker);
            $password = $this->passwordEncoder->encodePassword($admin, 'admin');
            $admin->setProfil($this->getReference(ProfilFixtures::ADMIN_PROFIL_REFERENCE));

            $admin->setPassword($password);
            $admin->setRoles(['ROLE_ADMIN']);
            if ($i == 1) {
                $admin->setEmail("admin@odc.sn");
            }
            $manager->persist($admin);
        }
        //formateur
        for ($i = 0; $i < 20; $i++) {
            $formateur = new Formateur();
            $formateur = $this->setUsersFields($formateur, $faker);
            $formateur->setProfil($this->getReference(ProfilFixtures::FORMATEUR_PROFIL_REFERENCE));

            $password = $this->passwordEncoder->encodePassword($formateur, 'formateur');
            $formateur->setPassword($password);
            $formateur->setRoles(['ROLE_FORMATEUR']);
            if ($i == 1) {
                $formateur->setEmail("formateur@odc.sn");
            }
            $manager->persist($formateur);
        }
        //apprenant
        for ($i = 0; $i < 20; $i++) {
            $apprenant = new Apprenant();
            $apprenant = $this->setUsersFields($apprenant, $faker);
            $apprenant->setProfil($this->getReference(ProfilFixtures::APPRENANT_PROFIL_REFERENCE));
            $password = $this->passwordEncoder->encodePassword($apprenant, 'apprenant');
            $apprenant->setPassword($password);
            $apprenant->setRoles(['ROLE_APPRENANT']);
            if ($i == 1) {
                $apprenant->setEmail("apprenant@odc.sn");
            }

            $manager->persist($apprenant);
        }
        //cm
        for ($i = 0; $i < 20; $i++) {
            $cm = new CommunityManager();
            $cm = $this->setUsersFields($cm, $faker);
            $password = $this->passwordEncoder->encodePassword($cm, 'communitymanager');
            $cm->setPassword($password);
            $cm->setProfil($this->getReference(ProfilFixtures::COMMUNITYMANAGER_PROFIL_REFERENCE));
            $cm->setRoles(['ROLE_CM']);
            if ($i == 1) {
                $cm->setEmail("cm@odc.sn");
            }

            $manager->persist($cm);
        }
        $manager->flush();

    }

    public function setUsersFields(User $user, $faker): ?User
    {
        $user->setPrenom($faker->firstName);
        $user->setNom($faker->lastName);
        $user->setEmail($faker->email);
        return $user;
    }
}
