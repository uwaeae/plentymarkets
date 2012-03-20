<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Acme\BSDataBundle\Entity\Stock
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Stock
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity="Stockground", mappedBy="stock")
     */
    protected $stockground;

    public function __construct()
    {
        $this->stockground = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Get stockground
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getStockground()
    {
        return $this->stockground;
    }


    /**
     * Add stockground
     *
     * @param Acme\BSDataBundle\Entity\Stockground $stockground
     */
    public function addStockground(\Acme\BSDataBundle\Entity\Stockground $stockground)
    {
        $this->stockground[] = $stockground;
    }
}