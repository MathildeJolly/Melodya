<?php

namespace Amama\MelodyaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Musique
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Amama\MelodyaBundle\Entity\Musique;
use Amama\MelodyaBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;

/*
/!\ POUR TELECHARGER DES MUSIQUES PLUS LONGUES, DEMANDER A REGIS DE MODIFIER LA TAILLE MAX DES FICHIERS UPLOAD DANS LE PHP.INI (ATTRIBUT : upload_max_filesize à 20M et post_max_size à 0 (-->illimité) ) /!\
*/
class MusiqueController extends Controller {

	public function uploadAction(Request $request) {

		$userSession = $this->get('session')->get('user');

		if($userSession == NULL){
			
			$url = $this->get('router')->generate('m_deconnexion');

	 		return new RedirectResponse($url);

		} else {

			// Création de l'objet Musique dans lequel on insére les informations 
			$musique = new Musique();
			$tags = new Tag();
			$em = $this->getDoctrine()->getManager();
			$repository = $em->getRepository('AmamaMelodyaBundle:Musique');
			$repositoryTags = $em->getRepository('AmamaMelodyaBundle:Tag');

			// Création du formulaire avec l'objet Musique
			$form='';
			$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $musique);
			$formBuilder
			->add('tag',    EntityType::class, array('class' => 'AmamaMelodyaBundle:Tag', 'choice_label' => function ($tags) { return $tags->getNom(); }, 'multiple' => false, 'label' => 'Tag'))
			->add('titre',   TextType::class, array('label' => 'Titre'))
			->add('album',   TextType::class, array('label' => 'Album'))
			->add('artiste',    TextType::class, array('label' => 'Artiste'))
			->add('file',	FileType::class, array('required' => true, 'label' => 'Sélectionner un bon son'))
			->add('Upload',      SubmitType::class);
			$form = $formBuilder->getForm();
			$messageErreur =  "";

			// Si le formumaire soumis est valide
			if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
				$file = $musique->getFile();

			// Création d'une clé unique pour le nom du fichier afin de limiter les risques d'avoir les mêmes noms de fichiers
		    $fileName = md5(uniqid()).'.'.$file->guessExtension(); // création clé unique
		    $file ->move( // on met musique+nom dans dossier musique
		    	$this->getParameter('musique_directory'),
		    	$fileName
		    );

		    $user = $this->get('session')->get('user');
	    	$musique->setIdUser($user->getId());
			$musique->setLien($fileName);
			
		    $em->persist($musique);
		    $em->flush();

		    return $this->liremusiqueAction($request);

		  } else {

		  	return $this->render('AmamaMelodyaBundle:Musique:upload.html.twig', array('form'=>$form->createView(), 'user'=>$userSession));
		  }

		}

	}

	public function liremusiqueAction(Request $request) {

	 	$userSession = $this->get('session')->get('user');

	 	if($userSession == NULL){
			$url = $this->get('router')->generate('m_deconnexion');
	 		return new RedirectResponse($url);

	 	} else {
	 		$em = $this->getDoctrine()->getManager();
	 		$repository = $em->getRepository('AmamaMelodyaBundle:Musique');
	 		$musiques = $repository->findBy(['idUser' => $userSession->getId()]);
	 		return $this->render('AmamaMelodyaBundle:Musique:musiques.html.twig',array('musiques'=>$musiques, 'user'=>$userSession));
	 }
	}

	public function lireMusique_pAction(Request $request, $id) {
		 // User actuellement connecté
        $userSession = $this->get('session')->get('user');

        // Récupération des entités
        $em = $this->getDoctrine()->getManager();
        $repositoryMusique = $em->getRepository('AmamaMelodyaBundle:Musique');
        $repositoryUser = $em->getRepository('AmamaMelodyaBundle:User');

        // User dont on a souhaité voir le profil
        $userP = $repositoryUser->findBy(['id' => $id]);

        // Si la session est vide, c'est-à-dire que si personne est actuellement connecté
        if($userSession == NULL){
			
			$url = $this->get('router')->generate('m_deconnexion');
	 		return new RedirectResponse($url);

		} else {            

            $em = $this->getDoctrine()->getManager();
	 		$musiques = $repositoryMusique->findBy(['idUser' => $id]);
	 		return $this->render('AmamaMelodyaBundle:Musique:musiques_p.html.twig',array('musiques'=>$musiques, 'user'=>$userSession));

        }
	}

	public function downloadFileAction($musique){

		/* Si une personne souhaite télécharger une musique à travers l'URL */

		/*if($musique == NULL){

			$messageErreur = "La musique que vous souhaitez télécharger n'existe pas sur notre platforme.."

			return $this->render('AmamaMelodyaBundle:Musique:musiques.html.twig',array('musiques'=>$musiques, 'user'=>$userSession, 'msgErreur' => $messageErreur));

		} else {*/

		$hello  = $this->getParameter('musique_directory')."/".$musique ;

		$response = new BinaryFileResponse($hello);
		$response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'test.mp3');
		return $response;
	}

}
