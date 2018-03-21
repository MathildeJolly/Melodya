<?php

namespace Amama\MelodyaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupeUser
 *
 * @ORM\Table(name="groupe_user")
 * @ORM\Entity(repositoryClass="Amama\MelodyaBundle\Repository\GroupeUserRepository")
 */
class GroupeUser
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
     * @ORM\ManyToOne(targetEntity="Amama\MelodyaBundle\Entity\Groupe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupe;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Amama\MelodyaBundle\Entity\User", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



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
     * Set groupe
     *
     * @param \Amama\MelodyaBundle\Entity\Groupe $groupe
     *
     * @return GroupeUser
     */
    public function setGroupe(\Amama\MelodyaBundle\Entity\Groupe $groupe)
    {
        $this->groupe = $groupe;
    
        return $this;
    }

    /**
     * Get groupe
     *
     * @return \Amama\MelodyaBundle\Entity\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set user
     *
     * @param \Amama\MelodyaBundle\Entity\User $user
     *
     * @return GroupeUser
     */
    public function setUser(\Amama\MelodyaBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Amama\MelodyaBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
