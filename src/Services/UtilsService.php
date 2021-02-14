<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class UtilsService
{
    private $mailer;

    /**
     * SendEmail constructor.
     * @param Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

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
        return null;
    }
    public function updateField($data, $user)
    {
        foreach ($data as $name => $value) {
            $action = 'set' . ucfirst($name);
            if (method_exists($user, $action)) {
                $user->$action($value);
            }
        }
        return $user;
    }

    public function getDataFromContent($request)
    {
        if (empty($request)) {
            return null;
        }
        $content = $request->getContent();

        $data = [];

        $items = preg_split("/form-data; /", $content);
        unset($items[0]);

        foreach ($items as $value) {
            $item = preg_split("/\r\n/", $value);
            array_pop($item);
            array_pop($item);
            $key = explode('"', $item[0]);
            $data[$key[1]] = end($item);
        }
        if (!empty($data['avatar'])) {
            $stream = fopen('php://memory', 'r+');
            //dd($stream);
            fwrite($stream, $data['avatar']);
            rewind($stream);
            $data['avatar'] = $stream;
        }
        return $data;
    }

    public static function toArray($data)
    {
        $collection = [];
        $array = [];
        for ($name = 0; $name < count($data); $name++) {
            foreach ($data[0] as $key => $keyArray) {
                if (!empty($data[$name + 1])) {
                    $array[$keyArray] = $data[$name + 1][$key];
                }
            }
            array_push($collection, $array);
        }
        array_pop($collection);
        return $collection;
    }

    /**
     * @param  $destination
     * @param $subject
     * @param $message
     */
    public function sendMail($destination, $subject, $message)
    {
        $message = (new \Swift_Message($subject))
            ->setFrom("admin@gmail.com")
            ->setTo($destination)
            ->setBody($message);
        $this->mailer->send($message);
    }

}
