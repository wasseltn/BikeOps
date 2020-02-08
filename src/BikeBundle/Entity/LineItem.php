<?php

namespace BikeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LineItem
 *
 * @ORM\Table(name="line_item")
 * @ORM\Entity(repositoryClass="BikeBundle\Repository\LineItemRepository")
 */
class LineItem
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
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumn(name="panier_id", referencedColumnName="id")
     */
    private $panier;

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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return LineItem
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @return mixed
     */
    public function getPanier()
    {
        return $this->panier;
    }

    /**
     * @param mixed $panier
     */
    public function setPanier($panier)
    {
        $this->panier = $panier;
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

