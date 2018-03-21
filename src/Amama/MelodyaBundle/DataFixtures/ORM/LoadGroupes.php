<?php

namespace Amama\MelodyaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Groupe;
use OC\PlatformBundle\Entity\User;
use OC\PlatformBundle\Entity\Admin;

class LoadGroupes extends FixtureInterface {

  public function load(ObjectManager $manager){

    $currentUser = new User();
    $currentUser = $this->get('session')->get('user');

    $admin = new Admin();

    $groupe = new Groupe();

  }

}

?>
