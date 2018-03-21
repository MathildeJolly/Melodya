<?php

namespace Amama\MelodyaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Amama\MelodyaBundle\Repository\UserRepository")
 */
class User
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
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, unique=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, unique=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=500, nullable=true)
     * @Assert\File(
     *      mimeTypes={"image/jpg", "image/jpeg", "image/png"},
     *      maxSize = "10000k",
     *      maxSizeMessage = "Votre fichier est trop lourd, la taille maximale est {{ size }} {{ suffix }}.",
     *      mimeTypesMessage = "Votre fichier n'est pas au bon format, les formats valides sont {{ types }}.",
     *      uploadErrorMessage = "Votre fichier n'a pas pu être uploadé."
     * )
     */
    private $avatar;

    /**
     * @var smallint
     *
     * @ORM\Column(name="anneeNaissance", type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 1930,
     *      max = 2016,
     *      minMessage = "Veuillez entrer une année de naissance valide: pas moins que {{ limit }}",
     *      maxMessage = "Veuillez entrer une année de naissance valide: pas plus que {{ limit }}"
     * )
     */
    private $anneeNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="styleMusiqueFavoris", type="string", length=255, nullable=true)
     */
    private $styleMusiqueFavoris;

    /**
    * @ORM\ManyToOne(targetEntity="Amama\MelodyaBundle\Entity\Tag")
    * @ORM\JoinColumn(nullable=true)
    */
    private $tagFavoris;

    /**
    *@ORM\ManyToMany(targetEntity="Amama\MelodyaBundle\Entity\Tag", cascade={"persist"})
    */
    private $tagMusiquesUploadees;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        //$this->tagMusiquesUploadees = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tagFavoris
     *
     * @param \Amama\MelodyaBundle\Entity\Tag $tagFavoris
     *
     * @return User
     */
    public function setTagFavoris(\Amama\MelodyaBundle\Entity\Tag $tagFavoris = null)
    {
        $this->tagFavoris = $tagFavoris;

        return $this;
    }

    /**
     * Get tagFavoris
     *
     * @return \Amama\MelodyaBundle\Entity\Tag
     */
    public function getTagFavoris()
    {
        return $this->tagFavoris;
    }

    /**
     * Add tagMusiquesUploadee
     *
     * @param \Amama\MelodyaBundle\Entity\Tag $tagMusiquesUploadee
     *
     * @return User
     */
    public function addTagMusiquesUploadee(\Amama\MelodyaBundle\Entity\Tag $tagMusiquesUploadee)
    {
        $this->tagMusiquesUploadees[] = $tagMusiquesUploadee;

        return $this;
    }

    /**
     * Remove tagMusiquesUploadee
     *
     * @param \Amama\MelodyaBundle\Entity\Tag $tagMusiquesUploadee
     */
    public function removeTagMusiquesUploadee(\Amama\MelodyaBundle\Entity\Tag $tagMusiquesUploadee)
    {
        $this->tagMusiquesUploadees->removeElement($tagMusiquesUploadee);
    }

    /**
     * Get tagMusiquesUploadees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTagMusiquesUploadees()
    {
        return $this->tagMusiquesUploadees;
    }

    /**
     * Set anneeNaissance
     *
     * @param integer $anneeNaissance
     *
     * @return User
     */
    public function setAnneeNaissance($anneeNaissance)
    {
        $this->anneeNaissance = $anneeNaissance;

        return $this;
    }

    /**
     * Get anneeNaissance
     *
     * @return integer
     */
    public function getAnneeNaissance()
    {
        return $this->anneeNaissance;
    }

    /**
     * Set styleMusiqueFavoris
     *
     * @param string $styleMusiqueFavoris
     *
     * @return User
     */
    public function setStyleMusiqueFavoris($styleMusiqueFavoris)
    {
        $this->styleMusiqueFavoris = $styleMusiqueFavoris;

        return $this;
    }

    /**
     * Get styleMusiqueFavoris
     *
     * @return string
     */
    public function getStyleMusiqueFavoris()
    {
        return $this->styleMusiqueFavoris;
    }
}
