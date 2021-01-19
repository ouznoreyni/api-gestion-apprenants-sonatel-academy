<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class UtilsService
{

    public function getData(Request $request)
    {
        $data = null;
        if (count($request->request->all()) == 0) {
            $data = json_decode($request->getContent(), true);
        } else {
            $data = $request->request->all();
        }
        return $data;
    }

    public function hashPassword($encoder, $user, $password)
    {
        if (!empty($user) && !empty($password)) {
            return $encoder->encodePassword($user, $password);
        } else {
            return false;
        }
    }

    public function denormalizeUser($serializer, $data, $libelleProfil)
    {

        $klass = "App\Entity\\";
        if (strtolower($libelleProfil) == "cm" || strtolower($libelleProfil) == "communitymanager") {
            $klass = $klass . "CommunityManager";
        } else {
            $klass = $klass . ucfirst($libelleProfil);
        }
        if (class_exists($klass)) {
            //denormalize user and validate it
            return $serializer->denormalize($data, $klass);
        }
        dd('error');
        return null;
    }
}
