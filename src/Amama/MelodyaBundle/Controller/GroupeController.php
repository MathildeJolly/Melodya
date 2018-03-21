<?php

namespace Amama\MelodyaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Form\Type\CollectionType;
use Amama\MelodyaBundle\Form\GroupeType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Amama\MelodyaBundle\Repository\UserRepository;

//Entités
use Amama\MelodyaBundle\Entity\Groupe;
use Amama\MelodyaBundle\Entity\User;
use Amama\MelodyaBundle\Entity\Tag;
use Amama\MelodyaBundle\Entity\GroupeUser;
use \Amama\MelodyaBundle\Entity\InvitationsGroupe;

class GroupeController extends Controller {

  public function creationGroupeAction(Request $request){

    $userSession = $this->get('session')->get('user');

    $groupe = new Groupe();
    $tags = new Tag();
    $users = new User();

    $em = $this->getDoctrine()->getManager();

    // Constructeur de formulaire
    $formulaire = $this->get('form.factory')->createBuilder(FormType::class, $groupe)
        ->add('nom', TextType::class)
        ->add('StyleDeMusiquePrincipal', EntityType::class, array('class' => 'AmamaMelodyaBundle:Tag', 'choice_label' => function ($tags) { return $tags->getNom(); }, 'multiple' => false, 'label' => 'Tag'))
        ->add('Enregistrer', SubmitType::class)
        ->getForm();

    if($userSession == NULL){
      
      $url = $this->get('router')->generate('m_deconnexion');

      return new RedirectResponse($url);

    }

    if($request->isMethod('POST')){

      $formulaire->handleRequest($request);

      if($formulaire->isValid()){

        $groupe->setAdmin($userSession->getId());

        $em->persist($groupe);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "Le groupe a été crée, let's go!");

        return $this->render('AmamaMelodyaBundle:Groupes:home_groupe.html.twig', ['groupe' => $groupe, 'user' => $userSession]);

      }
    }

    return $this->render('AmamaMelodyaBundle:Groupes:creation_groupe.html.twig', array('formulaire' => $formulaire->createView(), 'user' => $userSession) );

  }

  public function homeGroupeAction($id){

    $userSession = $this->get('session')->get('user');

    if($userSession == NULL){

      $url = $this->get('router')->generate('m_deconnexion');

	 		return new RedirectResponse($url);

    }

    $infosGroupe = $this->getDoctrine()->getManager()->getRepository('AmamaMelodyaBundle:Groupe')->findBy(['id' => $id]);

    return $this->render('AmamaMelodyaBundle:Groupes:home_groupe.html.twig', ['groupe' => $infosGroupe[0], 'user' => $userSession]);

  }

  public function ajouterMembreAction(Request $request, $id){   

    $userSession = $this->get('session')->get('user');

    if($userSession == NULL){

      $url = $this->get('router')->generate('m_deconnexion');

	 		return new RedirectResponse($url);

    }

    $em = $this->getDoctrine()->getManager();
    $groupe = $em->getRepository('AmamaMelodyaBundle:Groupe')->findBy(['id' => $id]);
    $idAdmin = $groupe[0]->getAdmin();
    $admin = $this->getDoctrine()->getManager()->getRepository('AmamaMelodyaBundle:User')->findBy(['id' => $idAdmin]);
    $invitations = new InvitationsGroupe();
    $users = new User();

    $invitations->setIdGroupe($id);
    // Formulaire d'ajout de membres
    $formulaire = $this->get('form.factory')->createBuilder(FormType::class, $invitations)
                  ->add('toIdMembre',  EntityType::class, array('class' => 'AmamaMelodyaBundle:User', 'choice_label' => function ($users) { return $users->getLogin(); }, 'query_builder' => function (UserRepository $repository) use($userSession) { return $repository->findAllUsersExceptCurrent($userSession); }, 'multiple' => true, 'label' => 'Membres'))
                  ->add('Envoyer',  SubmitType::class)
                  ->getForm();

    if($request->isMethod('POST')){

      $formulaire->handleRequest($request);
              
        if($formulaire->isValid()){
              
          $invitations->setEtatInvitation(false);
          
          $membres = $formulaire['toIdMembre']->getData();

          foreach($membres as $membre){

            $idMembre = $membre->getId();

            // Erreur : invitation déjà envoyée à un membre
            if($invitations->getToIdMembre == $idMembre && $invitations->getIdGroupe == $id){

              $msgErreur = "Vous avez déjà envoyé une invitation à ce membre";

              return $this->ajouterMembreAction($request, $id);
            }

            $invitations->setToIdMembre($idMembre);
            $em->persist($invitations);

          }
                              
          $em->flush();
              
          $request->getSession()->getFlashBag()->add('info', "Les invitations à rejoindre le groupe ont été envoyées !");
             
          return $this->render('AmamaMelodyaBundle:Groupes:liste_membres.html.twig', ['groupe' => $groupe[0], 'admin' => $admin[0]]);
              
        }
    }
              
    return $this->render('AmamaMelodyaBundle:Groupes:ajouter_membres.html.twig', array('formulaire' => $formulaire->createView(), 'user' => $userSession, 'groupe' => $groupe[0]) );

  }

  public function listeMembresAction($id){

    $userSession = $this->get('session')->get('user');

    if($userSession == NULL){
      
      $url = $this->get('router')->generate('m_deconnexion');

	 		return new RedirectResponse($url);

    }

    $groupes = $this->getDoctrine()->getManager()->getRepository('AmamaMelodyaBundle:Groupe')->findBy(['id' => $id]);

    $idAdmin = $groupes[0]->getAdmin();

    $admin = $this->getDoctrine()->getManager()->getRepository('AmamaMelodyaBundle:User')->findBy(['id' => $idAdmin]);

    //$grp = $this->getDoctrine()->getManager()->getRepository('AmamaMelodyaBundle:GroupeUser')->findId($id);

    return $this->render('AmamaMelodyaBundle:Groupes:liste_membres.html.twig', array('user' => $userSession, 'groupe' => $groupes[0], 'admin' => $admin[0]));

  }

  public function tchatGroupeAction($id){

    $userSession = $this->get('session')->get('user');

    if($userSession == NULL){
      
      $url = $this->get('router')->generate('m_deconnexion');

	 		return new RedirectResponse($url);

    }

    $groupes = $this->getDoctrine()->getManager()->getRepository('AmamaMelodyaBundle:Groupe')->findBy(['id' => $id]);

    $idAdmin = $groupes[0]->getAdmin();

    $admin = $this->getDoctrine()->getManager()->getRepository('AmamaMelodyaBundle:User')->findBy(['id' => $idAdmin]);

    return $this->render('AmamaMelodyaBundle:Groupes:liste_membres.html.twig', array('user' => $userSession, 'groupe' => $groupes[0], 'admin' => $admin[0]));
  }

}

?>
