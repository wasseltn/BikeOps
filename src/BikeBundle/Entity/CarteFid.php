<?php

namespace BikeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarteFid
 *
 * @ORM\Table(name="carte_fid")
 * @ORM\Entity(repositoryClass="BikeBundle\Repository\CarteFidRepository")
 */
class CarteFid
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
     * @ORM\Column(name="nbPoint", type="integer")
     */
    private $nbPoint;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $utilisateur;


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
     * Set nbPoint
     *
     * @param integer $nbPoint
     *
     * @return CarteFid
     */
    public function setNbPoint($nbPoint)
    {
        $this->nbPoint = $nbPoint;

        return $this;
    }

    /**
     * Get nbPoint
     *
     * @return int
     */
    public function getNbPoint()
    {
        return $this->nbPoint;
    }

    /**
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param mixed $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }


}

