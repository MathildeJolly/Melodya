<?php

namespace Amama\MelodyaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // avatar
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

// Entités
use Amama\MelodyaBundle\Entity\Tag;
use Amama\MelodyaBundle\Entity\User;
use Amama\MelodyaBundle\Entity\Tchat;
class TchatController extends Controller {

  public function indexAction(Request $request){

    $user = $this->get('session')->get('user');

    if($request->request->get('tag') && $request->request->get('message')){

      preg_match_all('!\d+!', $request->request->get('tag'), $matches);

      $result = array();

      array_push($result, $matches[0][0]);

      $idUser= $user->getId();

      $tchat = new Tchat();
      $tchat->setMessage($request->request->get('message'));
      $tchat->setTag($matches[0][0]);
      $tchat->setIdUser($idUser);


      $em = $this->getDoctrine()->getManager();
      $em->persist($tchat);
      $em->flush();

      return new JsonResponse($result);

      var_dump($result);
    }
    
    if($request->request->get('tag')){

      preg_match_all('!\d+!', $request->request->get('tag'), $matches);

      $result = array();
      $messages = $this->getDoctrine()->getRepository('AmamaMelodyaBundle:Tchat')->findBy(
        array('tag'=> $matches[0][0])
      );
      foreach ($messages as $message) {
        array_push($result, array('message' =>  $message->getMessage(),'user' =>  $message->getIdUser()));

      }
      return new JsonResponse($result);
    }

    $tags = $this->getDoctrine()->getRepository('AmamaMelodyaBundle:Tag')->findAll();

    return $this->render('AmamaMelodyaBundle:Tchat:home.html.twig', array('tags'=>$tags, 'user'=>$user));
  }


  public function salonTchatAction($tag){
    
    $em = $this->getDoctrine();

    $nom = $em->getRepository('AmamaMelodyaBundle:Tag')->findById($tag);
    $nom = $nom[0]->getNom();

    $tchats = $em->getRepository('AmamaMelodyaBundle:Tchat')->findAll();

    $user = $this->get('session')->get('user');
    $idUser= $user->getId();    

    return $this->render('AmamaMelodyaBundle:Tchat:tchat.html.twig', array('tchats'=>$tchats,'tag'=>$tag,'idUser'=>$idUser, 'nom'=>$nom));
  }

  public function messageTchatAction($tag){

    $tchats = $this->getDoctrine()->getRepository('AmamaMelodyaBundle:Tchat')->findAll();

    if($request->request->get('tag')){
    	//make something curious, get some unbelieveable data
      $arrData = ['output' => 'here the result which will appear in div'];
      return new JsonResponse($arrData);
    }  

   return new JsonResponse($tchats);
 }
}
