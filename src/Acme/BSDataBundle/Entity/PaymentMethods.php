<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $PaymentID
     *
     * @ORM\Column(name="PaymentID", type="integer")
     */
    private $PaymentID;

    /**
     * @var string $Name
     *
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $Name;


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
     * Set PaymentID
     *
     * @param integer $paymentID
     */
    public function setPaymentID($paymentID)
    {
        $this->PaymentID = $paymentID;
    }

    /**
     * Get PaymentID
     *
     * @return integer 
     */
    public function getPaymentID()
    {
        return $this->PaymentID;
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
}