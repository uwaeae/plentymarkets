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
     * @ORM\ManyToOne(targetEntity="cashbox", inversedBy="checkout")
     * @ORM\JoinColumn(name="cashbox_id", referencedColumnName="id")
     */

    private $cashbox;

    /**
     * @var datetime $buydate
     *
     * @ORM\Column(name="buydate", type="datetime",nullable = true)
     */
    private $buydate;

    /**
     * @var boolean $finish
     *
     * @ORM\Column(name="finish", type="boolean")
     */
    private $finish;

    /**
     * @var boolean $closed
     *
     * @ORM\Column(name="closed", type="boolean")
     */
    private $closed;

    /**
     * @var string $exported
     *
     * @ORM\Column(name="exported", type="string",nullable = true)
     */
    private $exported;


    /**
     * @var string $bontext
     *
     * @ORM\Column(name="bontext", type="string", length=255,nullable = true)

     */
    private $bontext;

    /**
     * @var integer $payment
     *
     * @ORM\Column(name="payment", type="integer")

     */
    private $payment;

    /**
     * @var float $summary
     *
     * @ORM\Column(name="summary", type="float")

     */
    private $summary;

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

    /**
     * Set summary
     *
     * @param float $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * Get summary
     *
     * @return float 
     */
    public function getSummary()
    {
        return $this->summary;
    }



    /**
     * Set cashbox
     *
     * @param Acme\BSCheckoutBundle\Entity\cashbox $cashbox
     */
    public function setCashbox(\Acme\BSCheckoutBundle\Entity\cashbox $cashbox)
    {
        $this->cashbox = $cashbox;
    }

    /**
     * Get cashbox
     *
     * @return Acme\BSCheckoutBundle\Entity\cashbox 
     */
    public function getCashbox()
    {
        return $this->cashbox;
    }



    /**
     * Set closed
     *
     * @param boolean $closed
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;
    }

    /**
     * Get closed
     *
     * @return boolean 
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Set bontext
     *
     * @param string $bontext
     */
    public function setBontext($bontext)
    {
        $this->bontext = $bontext;
    }

    /**
     * Get bontext
     *
     * @return string 
     */
    public function getBontext()
    {
        return $this->bontext;
    }

    /**
     * Set exported
     *
     * @param string $exported
     */
    public function setExported($exported)
    {
        $this->exported = $exported;
    }

    /**
     * Get exported
     *
     * @return string 
     */
    public function getExported()
    {
        return $this->exported;
    }
}