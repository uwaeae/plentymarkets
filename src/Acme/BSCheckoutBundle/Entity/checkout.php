<?php

namespace Acme\BSCheckoutBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Acme\BSCheckoutBundle\Entity\checkout
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\BSCheckoutBundle\Entity\checkoutRepository")
 */
class checkout
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
     * @var integer $cashbox
     *
     * @ORM\Column(name="cashbox", type="integer")
     */
    private $cashbox;

    /**
     * @var datetime $buydate
     *
     * @ORM\Column(name="buydate", type="datetime")
     */
    private $buydate;

    /**
     * @var boolean $finish
     *
     * @ORM\Column(name="finish", type="boolean")
     */
    private $finish;

    /**
     * @var integer $payment
     *
     * @ORM\Column(name="payment", type="integer")

     */
    private $payment;

    /**
     * @ORM\OneToMany(targetEntity="checkoutItem", mappedBy="checkout")
     */
    protected $checkoutItems;

    public function __construct()
    {
        $this->checkoutItems = new ArrayCollection();
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
     * Set cashbox
     *
     * @param integer $cashbox
     */
    public function setCashbox($cashbox)
    {
        $this->cashbox = $cashbox;
    }

    /**
     * Get cashbox
     *
     * @return integer 
     */
    public function getCashbox()
    {
        return $this->cashbox;
    }

    /**
     * Set buydate
     *
     * @param datetime $buydate
     */
    public function setBuydate($buydate)
    {
        $this->buydate = $buydate;
    }

    /**
     * Get buydate
     *
     * @return datetime 
     */
    public function getBuydate()
    {
        return $this->buydate;
    }

    /**
     * Set finish
     *
     * @param boolean $finish
     */
    public function setFinish($finish)
    {
        $this->finish = $finish;
    }

    /**
     * Get finish
     *
     * @return boolean 
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * Set payment
     *
     * @param integer $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get payment
     *
     * @return integer 
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Add checkout_items
     *
     * @param Acme\BSCheckoutBundle\Entity\checkout_item $checkoutItems
     */
    public function addcheckout_item(\Acme\BSCheckoutBundle\Entity\checkoutItem $checkoutItems)
    {
        $this->checkoutItems[] = $checkoutItems;
    }

    /**
     * Get checkout_items
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCheckoutItems()
    {
        return $this->checkoutItems;
    }

    /**
     * Add checkoutItems
     *
     * @param Acme\BSCheckoutBundle\Entity\checkoutItem $checkoutItems
     */
    public function addcheckoutItem(\Acme\BSCheckoutBundle\Entity\checkoutItem $checkoutItems)
    {
        $this->checkoutItems[] = $checkoutItems;
    }
}