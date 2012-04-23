<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\BSDataBundle\Entity\OrdersInfo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class OrdersInfo
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
     * @var string $Text
     *
     * @ORM\Column(name="Text", type="string", length=255)
     */
    private $Text;

    /**
     * @var date $iscreated
     *
     * @ORM\Column(name="iscreated", type="date" )
     */
    private $iscreated;


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
     * Set Text
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->Text = $text;
    }

    /**
     * Get Text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->Text;
    }

   

    /**
     * Set iscreated
     *
     * @param date $iscreated
     */
    public function setIscreated($iscreated)
    {
        $this->iscreated = $iscreated;
    }

    /**
     * Get iscreated
     *
     * @return date 
     */
    public function getIscreated()
    {
        return $this->iscreated;
    }

    /**
     * Set orders
     *
     * @param Acme\BSDataBundle\Entity\orders $orders
     */
    public function setOrders(\Acme\BSDataBundle\Entity\orders $orders)
    {
        $this->orders = $orders;
    }

    /**
     * Get orders
     *
     * @return Acme\BSDataBundle\Entity\orders 
     */
    public function getOrders()
    {
        return $this->orders;
    }
}