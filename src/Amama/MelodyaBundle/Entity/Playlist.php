<?php

namespace Amama\MelodyaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Playlist
 *
 * @ORM\Table(name="playlist")
 * @ORM\Entity(repositoryClass="Amama\MelodyaBundle\Repository\PlaylistRepository")
 */
class Playlist
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
     * @ORM\Column(name="idUser", type="integer")
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="styleMusique", type="string", length=255)
     */
    private $styleMusique;

    /**
     * @var bool
     *
     * @ORM\Column(name="privacite", type="boolean")
     */
    private $privacite;

    /**
     * @ORM\ManyToMany(targetEntity="Amama\MelodyaBundle\Entity\Musique", cascade={"persist"})
     */
    private $musique;


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
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Playlist
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    
        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Playlist
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set styleMusique
     *
     * @param string $styleMusique
     *
     * @return Playlist
     */
    public function setStyleMusique($styleMusique)
    {
        $this->styleMusique = $styleMusique;
    
        return $this;
    }

    /**
     * Get styleMusique
     *
     * @return string
     */
    public function getStyleMusique()
    {
        return $this->styleMusique;
    }

    /**
     * Set privacite
     *
     * @param boolean $privacite
     *
     * @return Playlist
     */
    public function setPrivacite($privacite)
    {
        $this->privacite = $privacite;
    
        return $this;
    }

    /**
     * Get privacite
     *
     * @return boolean
     */
    public function getPrivacite()
    {
        return $this->privacite;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->musique = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add musique
     *
     * @param \Amama\MelodyaBundle\Entity\Musique $musique
     *
     * @return Playlist
     */
    public function addMusique(\Amama\MelodyaBundle\Entity\Musique $musique)
    {
        $this->musique[] = $musique;
    
        return $this;
    }

    /**
     * Remove musique
     *
     * @param \Amama\MelodyaBundle\Entity\Musique $musique
     */
    public function removeMusique(\Amama\MelodyaBundle\Entity\Musique $musique)
    {
        $this->musique->removeElement($musique);
    }

    /**
     * Get musique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMusique()
    {
        return $this->musique;
    }
}
