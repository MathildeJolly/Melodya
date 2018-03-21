<?php

namespace Amama\MelodyaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe")
 * @ORM\Entity(repositoryClass="Amama\MelodyaBundle\Repository\GroupeRepository")
 */
class Groupe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="admin", type="integer")
     */
    private $admin;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="StyleDeMusiquePrincipal", type="string", length=255, nullable=true)
     */
    private $styleDeMusiquePrincipal;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set admin
     *
     * @param integer $admin
     *
     * @return Groupe
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    
        return $this;
    }

    /**
     * Get admin
     *
     * @return integer
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Groupe
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set styleDeMusiquePrincipal
     *
     * @param string $styleDeMusiquePrincipal
     *
     * @return Groupe
     */
    public function setStyleDeMusiquePrincipal($styleDeMusiquePrincipal)
    {
        $this->styleDeMusiquePrincipal = $styleDeMusiquePrincipal;
    
        return $this;
    }

    /**
     * Get styleDeMusiquePrincipal
     *
     * @return string
     */
    public function getStyleDeMusiquePrincipal()
    {
        return $this->styleDeMusiquePrincipal;
    }
}
