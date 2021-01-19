<?php

namespace App\Controller;

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

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $this->_entityManager = $entityManager;
        $this->_passwordEncoder = $passwordEncoder;
        $this->_validator = $validator;
        $this->_serializer = $serializer;
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

        //denormalize user
        $user = $utils->denormalizeUser($this->_serializer, $data, "cm");

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
        // dd($user);
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
}
