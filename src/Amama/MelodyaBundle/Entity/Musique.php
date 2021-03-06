<?php

namespace Amama\MelodyaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Musique
 *
 * @ORM\Table(name="musique")
 * @ORM\Entity(repositoryClass="Amama\MelodyaBundle\Repository\MusiqueRepository")
 */
class Musique
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
     * @ORM\ManyToOne(targetEntity="Amama\MelodyaBundle\Entity\User")
     * @ORM\Column(name="idUser", type="integer")
     */
    private $idUser;


    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=300)
     */
    private $lien;


    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=50)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="artiste", type="string", length=30)
     */
    private $artiste;

    /**
     * @var string
     *
     * @ORM\Column(name="album", type="string", length=50,nullable=true)
     */
    private $album;

    /**
     * @var int
     *
     * @ORM\Column(name="idPlaylist", type="integer", nullable=true)
     */
    private $idPlaylist;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=20)
     */
    private $tag;

    /**
     * @Assert\File(
     *      maxSize = "100M",
     *      mimeTypes = {"audio/mpeg3", "audio/x-mpeg-3", "audio/mpeg", "audio/mp3"},
     *      mimeTypesMessage = "Le format de fichier n'est pas valide, veuillez uplaoder un fichier .mp3",
     *      notFoundMessage = "Le fichier n'a pas été trouvé",
     *      uploadErrorMessage = "Le fichier n'a pas pu être uploadé."
     * )
     */
    private $file;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return Musique
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
     * Set idUser
     *
     * @param string $idUser
     *
     * @return Musique
     */
    public function setidUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return string
     */
    public function getidUser()
    {
        return $this->idUser;
    }


      /**
     * Set lien
     *
     * @param string $lien
     *
     * @return Musique
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * Set artiste
     *
     * @param string $artiste
     *
     * @return Musique
     */
    public function setArtiste($artiste)
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * Get artiste
     *
     * @return string
     */
    public function getArtiste()
    {
        return $this->artiste;
    }

    /**
     * Set album
     *
     * @param string $album
     *
     * @return Musique
     */
    public function setAlbum($album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return string
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set idPlaylist
     *
     * @param integer $idPlaylist
     *
     * @return Musique
     */
    public function setIdPlaylist($idPlaylist)
    {
        $this->idPlaylist = $idPlaylist;

        return $this;
    }

    /**
     * Get idPlaylist
     *
     * @return integer
     */
    public function getIdPlaylist()
    {
        return $this->idPlaylist;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return Musique
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile($file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function __toString(){
        return $this->titre;
    }

}
