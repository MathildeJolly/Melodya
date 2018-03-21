<?php

namespace Amama\MelodyaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// Classe de sécurité : gérant tous les composants et interactions en rapport avec la sécurité/authentification/droits d'accès, etc. --> Voir chap Sécurité sur OpenClassroom
class SecurityController extends Controller {

  // Fonction vérifiant si les attributs sont bons, grant les erreurs du formulaire de connexion
  public function loginAction(){

    // Si le visiteur est déjà identifié, on le redirige vers l'accueil
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

      return $this->redirectToRoute('m_home');

    }

    /* Le service authentication_utils permet de récupérer le nom d'utilisateur
     et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
     (mauvais mot de passe par exemple) */
    $authenticationUtils = $this->get('security.authentication_utils');

    return $this->render('AmamaMelodyaBundle:Security:login.html.twig', array(
      'last_username' => $authenticationUtils->getLastUsername(),
      'error'         => $authenticationUtils->getLastAuthenticationError(),
    ));

  }


}

?>
