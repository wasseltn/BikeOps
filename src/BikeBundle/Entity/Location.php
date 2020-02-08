<?php

namespace BikeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="BikeBundle\Repository\LocationRepository")
 */
class Location
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date")
     */
    private $dateFin;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;


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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Location
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Location
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Location
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Location
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean
     */
    public function getEtat()
    {
        return $this->etat;
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

    /**
     * @return mixed
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param mixed $produit
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;
    }


}

