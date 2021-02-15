<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Groupe;
use App\Entity\Promo;
use App\Entity\Promos;
use App\Repository\ProfilRepository;
use App\Services\FileHelperService;
use App\Services\UtilsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PromosController extends AbstractController
{
    private $_entityManager;
    private $_passwordEncoder;
    private $subjectEmail = 'Selection Sonatel Academy';
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->_entityManager = $entityManager;
        $this->_passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route(
     *      path="/api/admin/promos",
     *      methods={"POST"}
     * )
     */
    public function create(
        Request $request,
        ProfilRepository $profilRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator, UtilsService $utils,
        FileHelperService $fileHelper
    ) {
        $apprenantsColletions = [];
        $apprenantEmails = [];
        //get data and avatar from the request
        $data = $utils->getData($request);

        $image = $fileHelper->uploadFile($request->files->get("image"));

        $apprenantFiles = $fileHelper->uploadExcel($request->files->get("apprenantFiles"));

        $apprenantData = $utils->toArray($apprenantFiles);

        if (!@$apprenantData && @empty($data['apprenantEmails']) && @count($data['apprenantEmails']) == 0) {
            return $this->json(['message' => "Ajouter les apprenants"], Response::HTTP_BAD_REQUEST);
        }
        if (!@empty($data['apprenantEmails'])) {
            $apprenantEmails = json_decode($data['apprenantEmails']);
            unset($data['apprenantEmails']);
        }

        foreach (@$apprenantEmails as $email) {
            array_push($apprenantData, ['email' => $email]);
        }

        //creation du groupe principal
        $groupePrincil = new Groupe();
        $groupePrincil->setName('Groupe Principal');
        //validate email
        foreach (@$apprenantData as $apprenant) {
            $newApprenant = new Apprenant();
            $newApprenant = $utils->updateField($apprenant, $newApprenant);
            $profil = $profilRepository->findOneBy(["libelle" => "Apprenant"]);
            $newApprenant->setProfil($profil);
            $password = "sonatelacademy";
            $passwordHashed = $utils->hashPassword($this->_passwordEncoder, $newApprenant, $password);
            $newApprenant->setPassword($passwordHashed);

            $this->_entityManager->persist($newApprenant);

            $groupePrincil->addApprenant($newApprenant);
            $message = "Bonjour cher(e)" . $newApprenant->getPrenom() . " " . $newApprenant->getNom() . ".\n Félicitation! Vous avez été selectionné(e) suite à votre test d'entré à la Sonatel Academy.Veuillez utiliser ces informations pour vous connecter à votre promo.\n username: " . $newApprenant->getUsername() . " mot de passe:" . $password . ".\n A bientot.";

            $utils->sendMail($newApprenant->getEmail(), $this->subjectEmail, $message);
        }

        $this->_entityManager->persist($groupePrincil);
        $promo = new Promo();
        $promo = $utils->updateField($data, $promo);
        $errors = $validator->validate($promo);

        if (count($errors)) {
            $errors = $serializer->serialize($errors, "json");
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
        }
        $promo->addGroupe($groupePrincil);

        $this->_entityManager->persist($promo);

        $this->_entityManager->flush();

        return $this->json(['message' => 'promos cree']);
    }

}
