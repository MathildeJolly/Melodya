<?php

namespace Amama\MelodyaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Amama\MelodyaBundle\Repository\MusiqueRepository;

// Entités
use Amama\MelodyaBundle\Entity\Playlist;
use Amama\MelodyaBundle\Entity\Musique;
use Amama\MelodyaBundle\Entity\Tag;

class PlaylistController extends Controller {

    public function creerPlaylistAction(Request $request) {

		$userSession = $this->get('session')->get('user');

		if($userSession == NULL){
			
			return $this->render('AmamaMelodyaBundle:Musique:index.html.twig');

		} else {
            
            $playlists = new Playlist();
            $musiques = new Musique();
            $tags = new Tag();

            $em = $this->getDoctrine()->getManager();
            $repositoryPlaylist = $em->getRepository('AmamaMelodyaBundle:Playlist');
            $repositoryMusique = $em->getRepository('AmamaMelodyaBundle:Musique');

            $musiquesUserActuel = $repositoryMusique->findBy(['idUser' => $userSession->getId()]);

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $playlists);
            $formBuilder
                ->add('titre',  TextType::class, array('label' => 'Titre de la playlist'))
                ->add('styleMusique',   EntityType::class, array(
                    'class' => 'AmamaMelodyaBundle:Tag',
                    'choice_label' => function ($tags) { return $tags->getNom(); },
                    'multiple' => false,
                    'label' => 'Tag'))
                ->add('privacite',  RadioType::class, array('label' => 'Playlist privée', 'required' => false))
                ->add('musique',    EntityType::class, array(
                    'class' => 'AmamaMelodyaBundle:Musique',
                    'choice_label' => 'titre',
                    'multiple' => true,
                    'expanded' => false,
                    'label' => 'Musiques',
                    'query_builder' => function(MusiqueRepository $repository) use($userSession){
                        return $repository->findMusicByUser($userSession->getId());
                    }))
                ->add('Creer',  SubmitType::class);

            $form = $formBuilder->getForm();
            $messageErreur = "";

            // Si le formumaire soumis est valide
			if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                
                $playlists->setIdUser($userSession->getId());
			
		        $em->persist($playlists);
                $em->flush();
                
                return $this->listePlaylistsAction($request);

            } 
            
            return $this->render('AmamaMelodyaBundle:Musique:creation_playlist.html.twig', array('form'=>$form->createView(), 'user'=>$userSession));

        }
    }

    public function listePlaylistsAction(Request $request){

        $userSession = $this->get('session')->get('user');

        if($userSession == NULL){
			
			$url = $this->get('router')->generate('m_deconnexion');

	 		return new RedirectResponse($url);

		} else {

            $em = $this->getDoctrine()->getManager();

	 	    $repository = $em->getRepository('AmamaMelodyaBundle:Playlist');

            $playlists = $repository->findBy(['idUser' => $userSession->getId()]);

	 		return $this->render('AmamaMelodyaBundle:Musique:playlists.html.twig',array('playlists'=>$playlists, 'user'=>$userSession));

        }
    }

    public function listePlaylists_pAction(Request $request, $id){
        // User actuellement connecté
        $userSession = $this->get('session')->get('user');

        // Récupération des entités
        $em = $this->getDoctrine()->getManager();
        $repositoryPlaylist = $em->getRepository('AmamaMelodyaBundle:Playlist');
        $repositoryUser = $em->getRepository('AmamaMelodyaBundle:User');

        // User dont on a souhaité voir le profil
        $userP = $repositoryUser->findBy(['id' => $id]);

        // Si la session est vide, c'est-à-dire que si personne est actuellement connecté
        if($userSession == NULL){
			
			$url = $this->get('router')->generate('m_deconnexion');

	 		return new RedirectResponse($url);

		} else {            

            $playlists = $repositoryPlaylist->findBy(['idUser' => $id]);

	 		return $this->render('AmamaMelodyaBundle:Musique:playlists_p.html.twig',array('playlists'=>$playlists, 'user'=>$userSession));

        }
    }

    public function afficherPlaylistAction(Request $request, $id){

        $userSession = $this->get('session')->get('user');
    
            if($userSession == NULL){
                
                $url = $this->get('router')->generate('m_deconnexion');

	 		    return new RedirectResponse($url);
    
            } else {
    
                $em = $this->getDoctrine()->getManager();
    
                $repository = $em->getRepository('AmamaMelodyaBundle:Playlist');
    
                $playlist = $repository->findBy(['id' => $id]);

                $musiques = $playlist[0]->getMusique();
    
                return $this->render('AmamaMelodyaBundle:Musique:playlist.html.twig',array('playlist'=>$playlist[0], 'user'=>$userSession, 'musiques' => $musiques));
    
        }
    }
}