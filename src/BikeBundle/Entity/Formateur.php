<?php

namespace BikeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Formateur
 *
 * @ORM\Table(name="formateur")
 * @ORM\Entity(repositoryClass="BikeBundle\Repository\FormateurRepository")
 */
class Formateur
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
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Length(min="3",max="20")
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\Length(min="3",max="20")
     * @Assert\NotBlank()
     */
    private $prenom;

    /**
    * @var string
    *
     * @ORM\Column(name="specialite", type="string", length=255)
     * @Assert\Length(min="3",max="20")
     * @Assert\NotBlank()
     */
    private $specialite;
    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="evenement")
     * @ORM\JoinColumn(name="evenement",referencedColumnName="id")
     */
    private $evenement;


    /**
     * @return string
     */
    public function getEvenement()
    {
        return $this->evenement;
    }

    /**
     * @param string $evenement
     */
    public function setEvenement($evenement)
    {
        $this->evenement = $evenement;
    }


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Formateur
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Formateur
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
     * Set specialite
     *
     * @param string $specialite
     *
     * @return Formateur
     */
    public function setSpecialite($specialite)
    {
        $this->specialite = $specialite;
    
        return $this;
    }

    /**
     * Get specialite
     *
     * @return string
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    public function __toString()
    {
        return $this->getNom()+" "+$this->getPrenom();
    }
}

