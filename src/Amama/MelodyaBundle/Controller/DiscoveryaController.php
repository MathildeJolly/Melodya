<?php

namespace Amama\MelodyaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DiscoveryaController extends Controller {

    public function indexAction(Request $request, $page){

        // Récupération de la session de l'user actuelle
        $userSession = $this->get('session')->get('user');

        // Récupération des users dans la base de données
        $em = $this->getDoctrine()->getManager();
        $repositoryUsers = $em->getRepository('AmamaMelodyaBundle:User');
        $repositoryMusic = $em->getRepository('AmamaMelodyaBundle:Musique');
        // Récuperation de tous les users sous la forme d'un tableau
        

        if($userSession == NULL){
            
            $url = $this->get('router')->generate('m_deconnexion');

            return new RedirectResponse($url);
             
        } else {
            $nbUserParPage = 1;
            //$page = 1;

            $favoriteMusicUser = $userSession->getStyleMusiqueFavoris();

            $users = $repositoryUsers->findAllUsersAndSort($page, $nbUserParPage, $favoriteMusicUser, $userSession);
            $musics = $repositoryMusic->findAllMusics($page, 1, $userSession);

            $pagination = array(
                'page' => $page,
                'nbPages' => ceil(count($musics) / $nbUserParPage),
                'nomRoute' => 'm_discoverya',
                'paramsRoute' => array()
            );

            return $this->render('AmamaMelodyaBundle:Discoverya:discoverya.html.twig', array('user' => $userSession, 'listeMusics' => $musics, 'listeUsers' => $users, 'pagination' => $pagination ));
        }

    }
}

?>