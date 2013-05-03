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
     * @var integer $number
     *
     * @ORM\Column(name="number", type="integer")
    */

    private $number;

    /**
     * @var integer $PlentyStockID
     *
     * @ORM\Column(name="PlentyStockID", type="integer")
     */

    private $PlentyStockID;


    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="Stock")
     */

    private $products;



    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * Set parant
     *
     * @param Acme\BSDataBundle\Entity\Stock $parant
     */
    public function setParant(\Acme\BSDataBundle\Entity\Stock $parant)
    {
        $this->parant = $parant;
    }

    /**
     * Get parant
     *
     * @return Acme\BSDataBundle\Entity\Stock 
     */
    public function getParant()
    {
        return $this->parant;
    }

    /**
     * Set number
     *
     * @param integer $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }
    public function setProducts($products)
    {
        $this->products = $products;
    }

    public function getProducts()
    {
        return $this->products;
    }



    /**
     * Add products
     *
     * @param Acme\BSDataBundle\Entity\Product $products
     */
    public function addProduct(\Acme\BSDataBundle\Entity\Product $products)
    {
        $this->products[] = $products;
    }

    /**
     * Set PlentyStockID
     *
     * @param integer $plentyStockID
     */
    public function setPlentyStockID($plentyStockID)
    {
        $this->PlentyStockID = $plentyStockID;
    }

    /**
     * Get PlentyStockID
     *
     * @return integer 
     */
    public function getPlentyStockID()
    {
        return $this->PlentyStockID;
    }
}