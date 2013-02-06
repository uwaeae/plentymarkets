<?php

namespace Acme\BSCheckoutBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\BSCheckoutBundle\Entity\checkoutItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\BSCheckoutBundle\Entity\checkoutItemRepository")
 */
class checkoutItem
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
     * @var string $article_code
     *
     * @ORM\Column(name="article_code", type="string", length=255)
     */
    private $articleCode;

    /**
     * @var integer $article_id
     *
     * @ORM\Column(name="article_id", type="integer")
     */
    private $articleId;

    /**
     * @var integer $quantity
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description",type="string", length=255)
     */
    private $description;

    /**
     * @var float $price
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;


    /**
     * @var integer $VAT
     *
     * @ORM\Column(name="vat", type="integer")
     */
    private $VAT;


    /**
     * @ORM\ManyToOne(targetEntity="checkout", inversedBy="checkoutItems")
     * @ORM\JoinColumn(name="checkoutitem_id", referencedColumnName="id")
     */
    private $checkout;



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
     * Set article_code
     *
     * @param string $articleCode
     */
    public function setArticleCode($articleCode)
    {
        $this->articleCode = $articleCode;
    }

    /**
     * Get article_code
     *
     * @return string 
     */
    public function getArticleCode()
    {
        return $this->articleCode;
    }

    /**
     * Set article_id
     *
     * @param integer $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * Get article_id
     *
     * @return integer 
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return decimal 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set checkout
     *
     * @param Acme\BSCheckoutBundle\Entity\checkout $checkout
     */
    public function setCheckout(\Acme\BSCheckoutBundle\Entity\checkout $checkout)
    {
        $this->checkout = $checkout;
    }

    /**
     * Get checkout
     *
     * @return Acme\BSCheckoutBundle\Entity\checkout 
     */
    public function getCheckout()
    {
        return $this->checkout;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set VAT
     *
     * @param integer $vAT
     */
    public function setVAT($vAT)
    {
        $this->VAT = $vAT;
    }

    /**
     * Get VAT
     *
     * @return integer
     */
    public function getVAT()
    {
        return $this->VAT;
    }
}