<?php

namespace Amama\MelodyaBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Amama\MelodyaBundle\Repository\GroupeRepository;

class InvitationsExtension extends \Twig_Extension {

    private $groupeRepository;

    public function __construct(GroupeRepository $ur){
        $this->$groupeRepository = $ur;
    }

    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('findNomIdGroupe', array($this, 'findNomIdGroupe')),
        );
    }

    public function findNomIdGroupe($idGroupe){

        $groupe = $this->$GroupeRepository->findBy(['id' => $idGroupe]);

        return $groupe;

    }
}

?>