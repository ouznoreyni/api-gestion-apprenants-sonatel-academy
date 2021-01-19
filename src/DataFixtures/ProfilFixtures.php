<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
    public const ADMIN_PROFIL_REFERENCE = 'Admin';
    public const APPRENANT_PROFIL_REFERENCE = 'Apprenant';
    public const FORMATEUR_PROFIL_REFERENCE = 'Formateur';
    public const COMMUNITYMANAGER_PROFIL_REFERENCE = 'CommunityManager';

    public function load(ObjectManager $manager)
    {
        $adminProfil = new Profil;
        $adminProfil->setLibelle(self::ADMIN_PROFIL_REFERENCE);
        $manager->persist($adminProfil);

        $apprenantProfil = new Profil();
        $apprenantProfil->setLibelle(self::APPRENANT_PROFIL_REFERENCE);
        $manager->persist($apprenantProfil);

        $formateurProfil = new Profil();
        $formateurProfil->setLibelle(self::FORMATEUR_PROFIL_REFERENCE);
        $manager->persist($formateurProfil);

        $communityManagerProfil = new Profil();
        $communityManagerProfil->setLibelle(self::COMMUNITYMANAGER_PROFIL_REFERENCE);
        $manager->persist($communityManagerProfil);

        $manager->flush();

        $this->addReference(self::ADMIN_PROFIL_REFERENCE, $adminProfil);
        $this->addReference(self::APPRENANT_PROFIL_REFERENCE, $apprenantProfil);
        $this->addReference(self::COMMUNITYMANAGER_PROFIL_REFERENCE, $communityManagerProfil);
        $this->addReference(self::FORMATEUR_PROFIL_REFERENCE, $formateurProfil);
    }
}
