<?php

namespace Amama\MelodyaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // avatar
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

// Entités
use Amama\MelodyaBundle\Entity\Tag;
use Amama\MelodyaBundle\Entity\Musique;
use Amama\MelodyaBundle\Entity\User;
use Amama\MelodyaBundle\Entity\GroupeUser;

class UserController extends Controller {

	/**
	 * Fonction principale, dès l'accès à Melodya
	 */
	public function indexAction(Request $request){

		$this->get('session')->set('user',NULL);

		$user = new User();

		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AmamaMelodyaBundle:User');

		// Gestion du formulaire d'inscription
		$formInscription='';

		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);
		$formBuilder
			->add('login',     TextType::class, array('label' => false,	'attr' => array('placeholder' => 'Pseudo')))
			->add('password',     PasswordType::class, array('label' => false, 'attr' => array('placeholder' => '********')))
	     	->add('confirmer',     PasswordType::class, array('mapped' => false, 'label' => false, 'attr' => array('placeholder' => '*********'))) // mapped false car le 2nd password n'est pas un attribut de la classe
	      	->add('mail',   TextType::class, array('label' => false,'attr' => array('placeholder' => 'Adresse mail')))
	      	->add('prenom',    TextType::class, array('required' => false, 'label' => false,'attr' => array('placeholder' => 'Prénom')))// car renseigner le prénom est facultatif
	      	->add('anneeNaissance',    NumberType::class, array('label' => false, 'attr' => array('placeholder' => 'Année de naissance')))
	      	->add('avatar', FileType::class, array('required' => false, 'label' => 'Sélectionner un avatar')) // facultatif
	      	->add('Inscription',      SubmitType::class);

	    $formInscription = $formBuilder->getForm();
	    $toutEstBon=true;
	    $messageErreur =  "";

	    if($request->isMethod('POST') && $formInscription->handleRequest($request)->isValid()) {

	    	$file = $user->getAvatar();
		    	if($file == '') { // si fichier vide, avatar par défaut
		    		$user->setAvatar('avatarParDefaut.png');
		    	} else {
		    		$fileName = md5(uniqid()).'.'.$file->guessExtension(); // création clé unique
		    		$file ->move( // on met img+nom dans dossier avatar
		    			$this->getParameter('avatar_directory'),
		    			$fileName
		    		);
		    		$user->setAvatar($fileName);
		    	}

		    	//$compteurLogin=count($repository->findOneByLogin($user->getLogin())); // compter le nombre de logins existants (on bloque si = 1)
				//$compteurMail=count($repository->findOneByMail($user->getMail())); // compter le nombre de mails existants (on bloque si = 1)
				$mailSaisi = $formInscription['mail']->getData();
				$loginSaisi = $formInscription['login']->getData();
				$compteurLogin = count($repository->findOneByLogin($loginSaisi)); // compter le nombre de logins existants (on bloque si = 1)
				$compteurMail = count($repository->findOneByMail($mailSaisi)); // compter le nombre de mails existants (on bloque si = 1)
				
				// Cryptage du mot de passe
				$mdpSaisiConfirmer = $formInscription['confirmer']->getData();
				$mdpBD = $user->getPassword();
				$mdpBD = sha1($mdpBD);
				$user->setPassword($mdpBD);
				$mdpSaisiConfirmer = sha1($mdpSaisiConfirmer);				

		    	if($compteurLogin == 1) {
		    		//$session->getFlashBag()->add("Login déjà utilisé");
		    		$toutEstBon = false;
		    		$messageErreur =  "Login déjà utilisé";
				}

		    	if($compteurMail == 1) {
		    		//$session->getFlashBag()->add("Email déjà utilisé");
		    		$toutEstBon = false;
		    		$messageErreur =  "Email déjà utilisé";
		    	}

		    	if (!filter_var($user->getMail(), FILTER_VALIDATE_EMAIL)) {
		    		$messageErreur =  "Format mail est incorrect";
		    		$toutEstBon = false;
		    	}

		    	if($mdpBD != $mdpSaisiConfirmer) {
		    		//$session->getFlashBag()->add("Mots de passe différents");
		    		$messageErreur =  "Mots de passe différents";

		    		$toutEstBon = false;
				}

		    	if($toutEstBon) {
		    		$em->persist($user);
					$em->flush();
					
					$request->getSession()->getFlashBag()->add('notice', 'Votre compte a bien été crée.');

		    		return $this->indexAction($request);
		    	}
		    }


		// Connexion
		$formConnexion='';

		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);
		$formBuilder
		->add('login',     TextType::class, array('label' => false, 'attr' => array('placeholder' => 'Pseudo')))
		->add('password',     PasswordType::class, array('label' => false, 'attr' => array('placeholder' => '********')))
		->add('Connexion',      SubmitType::class);
		$formConnexion = $formBuilder->getForm();

		if($request->isMethod('POST') && $formConnexion->handleRequest($request)->isValid()) {

			$userTmp = $repository->findOneByLogin($user->getLogin());

			// Si le login existe dans la base de données
			if(count($userTmp)==1) {

				// Décommenter quand les données de la base seront vides pour crypter les mdp
				$mdpSaisi = $user->getPassword();
				$mdpSaisi = sha1($mdpSaisi);

				// Si le mot de passe entré correspond au mot de passe utilisé dans la base de données
				if($userTmp->getPassword()== $mdpSaisi) {

					$this->get('session')->set('user', $userTmp);

					return $this->redirectToRoute('m_home');
				} else {
					echo "nn mec";
				}
			} else {
				echo "pas de login";
			}
		}

		    return $this->render('AmamaMelodyaBundle:User:index.html.twig', array('formConnexion'=>$formConnexion->createView(), 'formInscription' => $formInscription->createView(), 'messageErreur'=> $messageErreur,'toutEstBon'=> $toutEstBon) );
		}

		/**
		 * Page d'accueil après la connexion d'un membre 
		 */
		public function homeAction(Request $request){
			$userSession = $this->get('session')->get('user');

			if($userSession == NULL){

				return $this->indexAction($request);

			} else {

				$repositoryMusique = $this->getDoctrine()->getManager()->getRepository('AmamaMelodyaBundle:Musique');
				$musiques = $repositoryMusique->find3LastsMusics();

				return $this->render('AmamaMelodyaBundle:User:home.html.twig', array('user'=>$userSession, 'musiques' => $musiques));
			}
		}

		/**
		 * Page de profil d'un membre 
		 */
		public function profilAction(Request $request, $login){

			$userSession = $this->get('session')->get('user');

			// S'il y a pas de session
			if($userSession == NULL){	
				
				$url = $this->get('router')->generate('m_deconnexion');

				return new RedirectResponse($url);
				 
			}

			$em = $this->getDoctrine()->getManager();
			$repositoryUser = $em->getRepository('AmamaMelodyaBundle:User');
			$repositoryMusic = $em->getRepository('AmamaMelodyaBundle:Musique');
			$repositoryGroup = $em->getRepository('AmamaMelodyaBundle:Groupe');
			$repositoryPlaylist = $em->getRepository('AmamaMelodyaBundle:Playlist');
			$repositoryInvitations = $em->getRepository('AmamaMelodyaBundle:InvitationsGroupe');
			// En argument de find() : mettre ds la session l'id de l'user après sa connexion
			
			$musiques = $repositoryMusic->findBy( ["idUser" => $userSession->getId()], ['id' => 'desc'], 3);
			$groupes = $repositoryGroup->findBy( ['admin' => $userSession->getId()] );
			$playlists = $repositoryPlaylist->findBy( ["idUser" => $userSession->getId()] );
			$lastMusique = $repositoryMusic->findLastMusicByUser( ["idUser" => $userSession->getId()] );
			$invitations = $repositoryInvitations->findBy(['toIdMembre' => $userSession->getId()]);			

			return $this->render('AmamaMelodyaBundle:User:profil.html.twig', array('user'=>$userSession, 'musiques' => $musiques, 'groupes' => $groupes, 'playlists' => $playlists, 'lastMusique' => $lastMusique, 'invitations' => $invitations));			
			
		}

		/**
		 * Page de consultation des invitations du membre connecté
		 */
		public function invitationsAction(Request $request, $idUser){

			$userSession = $this->get('session')->get('user');

			// S'il y a pas de session
			if($userSession == NULL){	
				
				$url = $this->get('router')->generate('m_deconnexion');

				return new RedirectResponse($url);
				 
			}

			$membreGroupe = new GroupeUser();

			$em = $this->getDoctrine()->getManager();    		
			$invitations = $em->getRepository('AmamaMelodyaBundle:InvitationsGroupe')->findBy(['toIdMembre' => $idUser]);

			foreach($invitations as $invitation){
				$idGroupe = $invitation->getIdGroupe();	

				// Formulaire d'acceptation d'invitations
				$formulaire = $this->get('form.factory')->createBuilder(FormType::class, $invitation)
							->add('Accepter',  SubmitType::class)
							->add('Refuser',  SubmitType::class)
							->getForm();

				if($request->isMethod('POST')){

					$formulaire->handleRequest($request);
						
					if($formulaire->isValid()){

						if($formulaire['Accepter']->isClicked()){
							$invitation->setEtatInvitation(true);
							//$membreGroupe->setIdGroupe($idGroupe);
							//$membreGroupe->setIdUser($idUser);
							$groupe = $em->getRepository('AmamaMelodyaBundle:Groupe')->findBy(['id' => $idGroupe]);
							$membreGroupe->setGroupe($groupe[0]);
							$membreGroupe->setUser($userSession);
							
							var_dump($membreGroupe);

							$em->persist($invitation);
							$em->persist($membreGroupe);

							$em->flush();

							return $this->invitationsAction($request, $idUser);
						}							

						if($formulaire['Refuser']->isClicked()){
							$invitation->setEtatInvitation(false);
							$em->remove($invitation);

							$em->flush();

							return $this->invitationsAction($request, $idUser);
						}					
							
					}					
						
				}			
					
				return $this->render('AmamaMelodyaBundle:User:invitations.html.twig', array('formulaire' => $formulaire->createView(), 'user' => $userSession, 'groupe' => $groupe[0], 'invitations' => $invitation) );
					
			}		
			
			//var_dump($invitations);
					
			return $this->render('AmamaMelodyaBundle:User:invitations.html.twig', array('user' => $userSession, 'invitations' => $invitations) );

		}

		/**
		 * Page de profil du membre dont on souhaite voir le profil
		 */
		public function profilMusiquesAction(Request $request, $login) {
			$em = $this->getDoctrine()->getManager();
			$repositoryUser = $em->getRepository('AmamaMelodyaBundle:User');
			$repositoryMusic = $em->getRepository('AmamaMelodyaBundle:Musique');
			
			$userSession = $this->get('session')->get('user');
			$musiques = $repositoryMusic->findBy( ["idUser" => $userSession->getId()], ['id' => 'desc'], 3);
			$lastMusique = $repositoryMusic->findLastMusicByUser( ["idUser" => $userSession->getId()] );

			// S'il y a pas de session
			if($userSession == NULL){	
				$url = $this->get('router')->generate('m_deconnexion');
				 return new RedirectResponse($url);
			}

			return $this->render('AmamaMelodyaBundle:User:profilMusiques.html.twig', array('user'=>$userSession, 'musiques' => $musiques, 'lastMusique' => $lastMusique));
		}

		public function profil_pAction(Request $request, $login){

			$em = $this->getDoctrine()->getManager();
			$repositoryUser = $em->getRepository('AmamaMelodyaBundle:User');
			$repositoryMusic = $em->getRepository('AmamaMelodyaBundle:Musique');
			$repositoryGroup = $em->getRepository('AmamaMelodyaBundle:Groupe');
			$repositoryPlaylist = $em->getRepository('AmamaMelodyaBundle:Playlist');
			// En argument de find() : mettre ds la session l'id de l'user après sa connexion
			
			$userSession = $this->get('session')->get('user');
			$infosLogin = $repositoryUser->findBy( ['login' => $login] );

			// S'il y a pas de session
			if($userSession == NULL){	
				
				return $this->indexAction($request);
			 
			} else {
				$login = $infosLogin[0];
				$musiques = $repositoryMusic->findBy( ["idUser" => $login->getId()], ['id' => 'desc'], 3);
				$groupes = $repositoryGroup->findBy( ['admin' => $login->getId()] );
				$playlists = $repositoryPlaylist->findBy( ["idUser" => $login->getId()] );
				
			}

			return $this->render('AmamaMelodyaBundle:User:profil_p.html.twig', array('user'=>$login, 'musiques' => $musiques, 'groupes' => $groupes, 'playlists' => $playlists));			
			
		}

		public function parametresProfilAction(Request $request){

			$userSession = $this->get('session')->get('user');

			if($userSession == NULL){

				return $this->indexAction($request);

			} else {

				$em = $this->getDoctrine()->getManager();
				$repository = $em->getRepository('AmamaMelodyaBundle:User');

				$form='';
				$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $userSession);
				$formBuilder
				->add('mail',   TextType::class, array('label' => 'Adresse mail'))
				->add('Modifier',   SubmitType::class);
				$modificationForm = $formBuilder->getForm();

				$messageErreur =  "";

				if($request->isMethod('POST') && $modificationForm->handleRequest($request)->isValid()) {
					$em->persist($userSession);
					$em->flush();

	      	/*$file = $user->getAvatar();
		    	if($file != '') { // si fichier vide, avatar par défaut
		  		    $fileName = md5(uniqid()).'.'.$file->guessExtension(); // création clé unique
		    		$file ->move( // on met img+nom dans dossier avatar
		    			$this->getParameter('avatar_directory'),
		    			$fileName
		    		);
		    		$user->setAvatar($fileName);
		    	}*/
		    }
		}
		return $this->render('AmamaMelodyaBundle:User:parametres.html.twig', array('user'=>$userSession, 'modificationForm'=>$modificationForm->createView(), 'modificationForm' => $modificationForm->createView(), 'messageErreur'=> $messageErreur,));
	}

	public function parcoursProfilsAction(Request $request){

		$userSession = $this->get('session')->get('user');

		if($userSession == NULL){
			
			return $this->indexAction($request);

		} else {

			$em = $this->getDoctrine()->getManager();
			$repositoryUser = $em->getRepository('AmamaMelodyaBundle:User');

			$users = $repositoryUser->findAll();
			
			return $this->render('AmamaMelodyaBundle:User:parcours_profils.html.twig', array('user' => $userSession, 'users' => $users));
		}

	}

	public function rechercheMusiqueBarreAction(Request $request){

		$musique = new Musique();
		
		$form = "";
		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $musique);
		$formBuilder
			->add('titre',   SearchType::class, array('attr' => ['placeholder' => 'Rechercher une musique'], 'label' => false))
			//->add('album',   TextType::class, array('label' => 'Album'))
			//->add('artiste',    TextType::class, array('label' => 'Artiste'))
			//->add('file', FileType::class, array('required' => true, 'label' => 'Sélectionner un bon son'))
			->add('Recherche',      SubmitType::class, array('attr'=>['class' => 'btn btn-primary']));
			
			$form = $formBuilder->getForm();

			if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

				$search = $form['titre']->getData();

				$results = $search->getResult();

				print_r($search);

				return $this->redirectToRoute('m_home', array('results' => $results));
			}

			return $this->render('AmamaMelodyaBundle:Musique:form_recherche.html.twig', array('form'=>$form->createView()));
		
	}

	public function rechercheMusiqueParTitreAction(){

		$musique = new Musique();
		
		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $musique);
		$formBuilder
			->add('titre',   SearchType::class, array('label' => 'Titre'))
			->add('Recherche',      SubmitType::class);
		$form = $formBuilder->getForm();

		if($form->isValid()){
			$query = $this->getDoctrine()->getRepository('AmamaMelodyaBundle:Musique')->search($form->getData());
			$results = $query->getResult();
		}
		
	}

	public function rechercheMusiqueParArtisteAction(){

		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $musique);
		$formBuilder
			->add('artiste',    SearchType::class, array('label' => 'Artiste'))
			->add('Rechercher',      SubmitType::class);
		$form = $formBuilder->getForm();

		if($form->isValid()){
			$query = $this->getDoctrine()->getRepository('AmamaMelodyaBundle:Musique')->search($form->getData());
			$results = $query->getResult();
		}
		
	}

	public function rechercheMusiqueParAlbumAction(){

		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $musique);
		$formBuilder
			->add('album',   SearchType::class, array('label' => 'Album'))
			->add('Rechercher',      SubmitType::class);
		$form = $formBuilder->getForm();

		if($form->isValid()){
			$query = $this->getDoctrine()->getRepository('AmamaMelodyaBundle:Musique')->search($form->getData());
			$results = $query->getResult();
		}
	}
}
