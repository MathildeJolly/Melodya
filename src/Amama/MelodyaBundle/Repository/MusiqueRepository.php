<?php

namespace Amama\MelodyaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * MusiqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MusiqueRepository extends \Doctrine\ORM\EntityRepository{

    /**
   * Retourne la liste de toutes les musiques de la plateforme.
   * Source: http://www.christophe-meneses.fr/article/creer-une-pagination-sur-un-projet-symfony
   * 
   * @param int $page
   *  Le numéro de la "page" actuelle
   * @param int $nbMaxParPage
   *  Le nombre maximum de membre par "page", c'est-à-dire 1.
   * @param string $tagMusiquePrefere
   *  Le tag de musique préférée du user connecté (userSession) 
   * 
   * @throws InvalidArgumentException
   * @throws NotFoundHttpException
   * 
   * @return Paginator
   */
  public function findAllMusics($page, $nbMaxParPage, $userActuel){

    if(!is_numeric($page)){
      throw new InvalidArgumentException("La valeur de l'argument $page est incorrecte (valeur: '".$page."').");
    }

    if ($page < 0){
      throw new NotFoundHttpException("La page demandée n'existe pas");
    }

    // Récupération de la liste des users en fonction du style de musique en commun
    $qb = $this->createQueryBuilder('m')
        ->where("m.idUser != :idUserActuel")
        ->setParameter('idUserActuel', $userActuel);
        
    $query = $qb->getQuery();

    $premiereMusique = ($page - 1) * $nbMaxParPage;
    $query->setFirstResult($premiereMusique)->setMaxResults($nbMaxParPage);
    $paginator = new Paginator($query);

    if( ($paginator->count() <= $premiereMusique) && $page != 1 ){
      throw new NotFoundHttpException("La page demandée n'existe pas.");
    }

    return $paginator;
  }


  public function findMusicByUser($idUserActuel){
    // Récupération de la liste de musiques en fonction du nom d'utilisateur
    return $this->createQueryBuilder('m')
                  ->where('m.idUser = :id')
                  ->setParameter('id', $idUserActuel);
  }

  public function findLastMusicByUser($idUserActuel) {
    // Récupération de la dernière musique uploadé en fonction du nom d'utilisateur

   // select * from musique where idUser = 3 order by id desc limit 1

    return $this->createQueryBuilder('m')
                ->where('m.idUser = :id')
                ->orderBy('m.id', 'DESC')
                ->setMaxResults(1)
                ->setParameter('id', $idUserActuel);
  }

  public function find3LastsMusics() {
    // Récupération des 3 dernieres musiques ajoutées sur la plateforme

   // select * from musique order by id desc limit 3

    $qb = $this->createQueryBuilder('m')
                ->orderBy('m.id', 'DESC');

        return $qb->getQuery()->setMaxResults(3);
  }
}
