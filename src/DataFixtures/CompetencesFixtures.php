<?php

namespace App\DataFixtures;

use App\DataFixtures\GrpeCompetencesFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CompetencesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // for ($i = 0; $i < 4; $i++) {
        //     $competences = new Competences();
        //     $competences->setTitle("competences " . $i + 1);
        //     $competences->setDescription('descripton competences 1' . $i + 1);
        //     $manager->persist($competences);
        // }

        // $manager->flush();
    }

    public function getDependencies()
    {
        return [GrpeCompetencesFixtures::class];
    }
}
