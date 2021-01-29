<?php

namespace App\DataFixtures;

use App\Entity\Competences;
use App\Entity\GroupeCompetences;
use App\Entity\Niveaux;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GrpeCompetencesFixtures extends Fixture
{
    public const GCOMPTENCE = ["gcompetence1", "gcompetence2", "gcompetence3"];

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {

            $groupeCompetences = new GroupeCompetences();
            $groupeCompetences->setLibelle($faker->realText($maxNbChars = 100, $indexSize = 2));
            $groupeCompetences->setDescription($faker->text);

            for ($j = 0; $j < 5; $j++) {
                $competences = new Competences();
                $competences->setLibelle($faker->realText($maxNbChars = 100, $indexSize = 2));
                for ($k = 1; $k < 4; $k++) {
                    $niveau = new Niveaux();
                    $niveau->setCriteresEvaluation($faker->realText($maxNbChars = 100, $indexSize = 2));
                    $niveau->setGroupeAction($faker->realText($maxNbChars = 100, $indexSize = 2));
                    $manager->persist($niveau);
                    $competences->addNiveaux($niveau);
                }
                $manager->persist($competences);
                $groupeCompetences->addCompetence($competences);
            }

            $manager->persist($groupeCompetences);

        }
        $manager->flush();
    }

}
