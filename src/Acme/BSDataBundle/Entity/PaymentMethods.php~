<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Acme\BSDataBundle\Entity\PaymentMethods
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PaymentMethods
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id

     */
    private $id;

    /**
     * @var string $Name
     *
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $Name;

    /**
     * @var string $Debitor
     *
     * @ORM\Column(name="Debitor", type="integer")
     */
    private $Debitor;

    /**
     * @var string $BankAccount
     *
     * @ORM\Column(name="BankAccount", type="integer" )
     */
    private $BankAccount;

    /**
     * @var boolean $payment
     *
     * @ORM\Column(name="payment", type="boolean" )
     */
    private $payment;

    /**
     * @var boolean $invoice
     *
     * @ORM\Column(name="invoice", type="boolean" )
     */
    private $invoice;





    /**
    * @ORM\OneToMany(targetEntity="Orders", mappedBy="PaymentMethods")
    */
    protected $orders;


    public function __construct()
    {
        $this->orders = new ArrayCollection();
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
     * Set Name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->Name = $name;
    }

    /**
     * Get Name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Add orders
     *
     * @param Acme\BSDataBundle\Entity\Orders $orders
     */
    public function addOrders(\Acme\BSDataBundle\Entity\Orders $orders)
    {
        $this->orders[] = $orders;
    }

    /**
     * Get orders
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set Debitor
     *
     * @param integer $debitor
     */
    public function setDebitor($debitor)
    {
        $this->Debitor = $debitor;
    }

    /**
     * Get Debitor
     *
     * @return integer 
     */
    public function getDebitor()
    {
        return $this->Debitor;
    }

    /**
     * Set BankAccount
     *
     * @param integer $bankAccount
     */
    public function setBankAccount($bankAccount)
    {
        $this->BankAccount = $bankAccount;
    }

    /**
     * Get BankAccount
     *
     * @return integer 
     */
    public function getBankAccount()
    {
        return $this->BankAccount;
    }

    /**
     * Set payment
     *
     * @param boolean $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get payment
     *
     * @return boolean 
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set invoice
     *
     * @param boolean $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get invoice
     *
     * @return boolean 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}