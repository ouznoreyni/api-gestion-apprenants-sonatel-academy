<?php

namespace App\Controller;

use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
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

class UsersController extends AbstractController
{

    private $_entityManager;
    private $_passwordEncoder;
    private $_validator;
    private $_serializer;
    private $_profilRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ProfilRepository $profilRepository
    ) {
        $this->_entityManager = $entityManager;
        $this->_passwordEncoder = $passwordEncoder;
        $this->_validator = $validator;
        $this->_serializer = $serializer;
        $this->_profilRepository = $profilRepository;
    }

    /**
     * @Route(path="/api/admin/users",  methods={"POST"})
     */
    public function create(
        Request $request, UtilsService $utils,
        FileHelperService $fileHelper,
        SerializerInterface $serializer
    ): Response {
        //get data and avatar from the request
        $data = $utils->getData($request);
        $avatar = $fileHelper->uploadFile($request->files->get("avatar"));
        $profil = $this->_profilRepository->findOneById($data['profil']);
        unset($data['profil']);

        if (!$profil) {
            return $this->json(['error' => "le profil n'existe pas"], Response::HTTP_NOT_FOUND);
        }

        //denormalize user
        $user = $utils->denormalizeUser($this->_serializer, $data, $profil->getLibelle());

        $passwordHashed = $utils->hashPassword($this->_passwordEncoder, $user, "sonatelacademy");
        $user->setPassword($passwordHashed);

        $errors = $this->_validator->validate($user);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, "json");
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        if ($avatar) {
            $user->setAvatar($avatar);
        }
        $user->setProfil($profil);

        $this->_entityManager->persist($user);
        $this->_entityManager->flush();

        if ($avatar) {
            fclose($avatar);
        }
        $userJson = $this->_serializer->serialize($user, "json", [
            "groups" => "user:read",
        ]);

        return new JsonResponse($userJson, Response::HTTP_OK, [], true);

    }

    /**
     * @Route(
     *      path="/api/admin/users/{id}",
     *      methods={"PUT"}
     * )
     */

    public function update(
        Request $request, UtilsService $utils,
        FileHelperService $fileHelper,
        SerializerInterface $serializer,
        UserRepository $userRepository,
        $id
    ) {
        $user = $userRepository->findOneById($id);
        if (!$user) {
            return $this->json(['error' => "User n'existe pas"], Response::HTTP_NOT_FOUND);
        }
        //get data and avatar from the request

        $data = $utils->getDataFromContent($request);

        $user = $utils->updateField($data, $user);

        $this->_entityManager->flush();

        $userJson = $this->_serializer->serialize($user, "json", [
            "groups" => "user:read",
        ]);

        return new JsonResponse($userJson, Response::HTTP_OK, [], true);

    }
}
