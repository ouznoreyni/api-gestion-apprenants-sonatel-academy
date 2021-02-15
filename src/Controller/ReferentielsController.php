<?php

namespace App\Controller;

use App\Entity\Referentiel;
use App\Repository\GroupeCompetencesRepository;
use App\Repository\ReferentielRepository;
use App\Services\FileHelperService;
use App\Services\UtilsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReferentielsController extends AbstractController
{
    private $_entityManager;
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->_entityManager = $entityManager;
    }

    /**
     * @Route("/api/admin/referentiels", name="referentiels", methods={"POST"})
     */

    public function create(
        Request $request,
        GroupeCompetencesRepository $groupeCompetencesRepository,
        ReferentielRepository $referentielRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UtilsService $utils,
        FileHelperService $fileHelper
    ) {

        //get data and avatar from the request
        $data = $utils->getData($request);

        $programme = $fileHelper->uploadFile($request->files->get("programme"));

        $groupeCompetencesCollection = [];
        $groupeCompetences = [];

        if (@$data["competences"]) {
            $groupeCompetences = $data["competences"];
            unset($data["competences"]);
        }

        $referentiel = new Referentiel();
        $referentiel = $utils->updateField($data, $referentiel);
        foreach (json_decode($groupeCompetences) as $comp) {

            $groupeCompetence = $groupeCompetencesRepository->findOneById($comp->id);

            if (@$groupeCompetence) {
                $referentiel->addCompetence($groupeCompetence);

            }

        }

        if (isset($programme)) {
            $referentiel->setProgramme($programme);
        }

        $this->_entityManager->persist($referentiel);

        $this->_entityManager->flush();

        if ($programme) {
            fclose($programme);
        }

        return $this->json(['message' => "referentiel créé"], Response::HTTP_CREATED);

    }
}
