<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\BSDataBundle\Entity\ProductBundle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\BSDataBundle\Entity\ProductBundleRepository")
 */
class ProductBundle
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
     * @var string $string
     *
     * @ORM\Column(name="string", type="string", length=255)
     */
    private $ArticleCode;

    /**
     * @var integer $ArticleID
     *
     * @ORM\Column(name="ArticleID", type="integer" )
     */
    private $ArticleID;
    /**
     * @var integer $Quantity
     *
     * @ORM\Column(name="Quantity", type="integer")
     */
    private $Quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="ProductBundles")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $Product;



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
     * Set string
     *
     * @param string $string
     */
    public function setString($string)
    {
        $this->string = $string;
    }

    /**
     * Get string
     *
     * @return string 
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * Set ArticleID
     *
     * @param string $articleID
     */
    public function setArticleID($articleID)
    {
        $this->ArticleID = $articleID;
    }

    /**
     * Get ArticleID
     *
     * @return string 
     */
    public function getArticleID()
    {
        return $this->ArticleID;
    }

    /**
     * Set ArticleCode
     *
     * @param string $articleCode
     */
    public function setArticleCode($articleCode)
    {
        $this->ArticleCode = $articleCode;
    }

    /**
     * Get ArticleCode
     *
     * @return string 
     */
    public function getArticleCode()
    {
        return $this->ArticleCode;
    }

    /**
     * Set Product
     *
     * @param Acme\BSDataBundle\Entity\Product $product
     */
    public function setProduct(\Acme\BSDataBundle\Entity\Product $product)
    {
        $this->Product = $product;
    }

    /**
     * Get Product
     *
     * @return Acme\BSDataBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->Product;
    }

    /**
     * Set Quantity
     *
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->Quantity = $quantity;
    }

    /**
     * Get Quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->Quantity;
    }
}