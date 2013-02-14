<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\BSDataBundle\Entity\OrdersItem
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class OrdersItem
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
     * @var integer $OrderID
     *
     * @ORM\Column(name="OrderID", type="integer")
     */
    private $OrderID;

    /**
     * @var integer $ArticleID
     *
     * @ORM\Column(name="ArticleID", type="integer")
     */
    private $ArticleID;

    /**
     * @var integer $VAT
     *
     * @ORM\Column(name="VAT", type="integer",nullable= true)
     */
    private $VAT;

    /**
     * @var integer $Quantity
     *
     * @ORM\Column(name="Quantity", type="integer")
     */
    private $Quantity;

    /**
     * @var string $ArticleCode
     *
     * @ORM\Column(name="ArticleCode", type="string", length=10)
     */
    private $ArticleCode;

    /**
     * @var string $SKU
     *
     * @ORM\Column(name="SKU", type="string", length=20)
     */
    private $SKU;

    /**
     * @var float $Price
     *
     * @ORM\Column(name="Price", type="float")
     */
    private $Price;

    /**
     * @var string $ItemText
     *
     * @ORM\Column(name="ItemText", type="string", length=255)
     */
    private $ItemText;


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
     * Set OrderID
     *
     * @param integer $orderID
     */
    public function setOrderID($orderID)
    {
        $this->OrderID = $orderID;
    }

    /**
     * Get OrderID
     *
     * @return integer 
     */
    public function getOrderID()
    {
        return $this->OrderID;
    }

    /**
     * Set ArticleID
     *
     * @param integer $articleID
     */
    public function setArticleID($articleID)
    {
        $this->ArticleID = $articleID;
    }

    /**
     * Get ArticleID
     *
     * @return integer 
     */
    public function getArticleID()
    {
        return $this->ArticleID;
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
     * Set SKU
     *
     * @param string $sKU
     */
    public function setSKU($sKU)
    {
        $this->SKU = $sKU;
    }

    /**
     * Get SKU
     *
     * @return string 
     */
    public function getSKU()
    {
        return $this->SKU;
    }

    /**
     * Set Price
     *
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->Price = $price;
    }

    /**
     * Get Price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->Price;
    }

    /**
     * Set ItemText
     *
     * @param string $itemText
     */
    public function setItemText($itemText)
    {
        $this->ItemText = $itemText;
    }

    /**
     * Get ItemText
     *
     * @return string 
     */
    public function getItemText()
    {
        return $this->ItemText;
    }
}