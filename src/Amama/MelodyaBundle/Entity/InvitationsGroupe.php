<?php

namespace Amama\MelodyaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvitationsGroupe
 *
 * @ORM\Table(name="invitations_groupe")
 * @ORM\Entity(repositoryClass="Amama\MelodyaBundle\Repository\InvitationsGroupeRepository")
 */
class InvitationsGroupe
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
     * @ORM\Column(name="idGroupe", type="integer")
     */
    private $idGroupe;

    /**
     * @var int
     *
     * @ORM\Column(name="toIdMembre", type="integer")
     */
    private $toIdMembre;

    /**
     * @var bool
     *
     * @ORM\Column(name="etatInvitation", type="boolean")
     */
    private $etatInvitation;


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
     * Set idGroupe
     *
     * @param integer $idGroupe
     *
     * @return InvitationsGroupe
     */
    public function setIdGroupe($idGroupe)
    {
        $this->idGroupe = $idGroupe;
    
        return $this;
    }

    /**
     * Get idGroupe
     *
     * @return integer
     */
    public function getIdGroupe()
    {
        return $this->idGroupe;
    }

    /**
     * Set toIdMembre
     *
     * @param integer $toIdMembre
     *
     * @return InvitationsGroupe
     */
    public function setToIdMembre($toIdMembre)
    {
        $this->toIdMembre = $toIdMembre;
    
        return $this;
    }

    /**
     * Get toIdMembre
     *
     * @return integer
     */
    public function getToIdMembre()
    {
        return $this->toIdMembre;
    }

    /**
     * Set etatInvitation
     *
     * @param boolean $etatInvitation
     *
     * @return InvitationsGroupe
     */
    public function setEtatInvitation($etatInvitation)
    {
        $this->etatInvitation = $etatInvitation;
    
        return $this;
    }

    /**
     * Get etatInvitation
     *
     * @return boolean
     */
    public function getEtatInvitation()
    {
        return $this->etatInvitation;
    }
}
