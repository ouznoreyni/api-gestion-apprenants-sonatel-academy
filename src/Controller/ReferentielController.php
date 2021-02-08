<?php

namespace App\Controller;

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

class ReferentielController extends AbstractController
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
        UtilsService $utils,
        FileHelperService $fileHelper
    ) {
        //get data and programme from the request
        $data = $utils->getData($request);
        $programme = $fileHelper->uploadFile($request->files->get("programme"));

        $groupeCompetencesCollection = [];
        $groupeCompetences = [];
        if (@$data["groupeCompetences"]) {
            $groupeCompetences = $data["groupeCompetences"];
            unset($data["groupeCompetences"]);
        }

        $referentiel = $serializer->denormalize($data, "App\Entity\Referentiel");

        foreach ($groupeCompetences as $groupeCompetenceId) {
            $groupeCompetence = $groupeCompetencesRepository->findOneById($groupeCompetenceId);
            if (@$groupeCompetence) {
                $referentiel->addGroupeCompetence($groupeCompetence);
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
