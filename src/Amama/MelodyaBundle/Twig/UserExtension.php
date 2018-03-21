<?php

namespace Amama\MelodyaBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Amama\MelodyaBundle\Repository\UserRepository;

class UserExtension extends \Twig_Extension {

    private $userRepository;

    public function __construct(UserRepository $ur){
        $this->$userRepository = $ur;
    }

    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('findUserMusic', array($this, 'findUserMusic')),
        );
    }

    public function findUserMusic($idUserMusic){

        $user = $this->$userRepository->findBy(['id' => $idUserMusic]);

        return $user;

    }
}

?>