<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Acme\BSDataBundle\Entity\Orders
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Orders
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
     * @var string $Street
     *
     * @ORM\Column(name="Street", type="string", length=255)
     */
    private $Street;

    /**
     * @var integer $OrderID
     *
     * @ORM\Column(name="OrderID", type="integer", unique="true")
     */
    private $OrderID;

    /**
     * @var decimal $OrderStatus
     * @ORM\Column(name="OrderStatus",type="decimal", scale=2)
     */
    private $OrderStatus;
    /**
     * @var string $OrderType
     *
     * @ORM\Column(name="OrderType", type="string", length=255)
     */
    private $OrderType;


    /**
     * @var string $Firstname
     *
     * @ORM\Column(name="Firstname", type="string", length=255)
     */
    private $Firstname;

    /**
     * @var string $Lastname
     *
     * @ORM\Column(name="Lastname", type="string", length=255)
     */
    private $Lastname;

    /**
     * @var integer $LastUpdate
     *
     * @ORM\Column(name="LastUpdate", type="integer")
     */
    private $LastUpdate;

    /**
     * @var string $HouseNumber
     *
     * @ORM\Column(name="HouseNumber", type="string", length=255)
     */
    private $HouseNumber;

    /**
     * @var string $Company
     *
     * @ORM\Column(name="Company", type="string", length=255,nullable=true)
     */
    private $Company;

    /**
     * @var string $AdditionalName
     *
     * @ORM\Column(name="AdditionalName", type="string", length=255,nullable=true)
     */
    private $AdditionalName;

    /**
     * @var string $ZIP
     *
     * @ORM\Column(name="ZIP", type="string", length=255,nullable=true)
     */
    private $ZIP;

    /**
     * @var string $City
     *
     * @ORM\Column(name="City", type="string", length=255,nullable=true)
     */
    private $City;



    /**
     * @var string $Telephone
     *
     * @ORM\Column(name="Telephone", type="string", length=255,nullable=true)
     */
    private $Telephone;

    /**
     * @var string $Email
     *
     * @ORM\Column(name="Email", type="string", length=255)
     */
    private $Email;

    /**
     * @var string $Title
     *
     * @ORM\Column(name="Title", type="string", length=255,nullable=true)
     */
    private $Title;

    /**
     * @var string $CountryID
     *
     * @ORM\Column(name="CountryID", type="string", length=255 ,nullable=true)
     */
    private $CountryID;

    /**
     * @var integer $CustomerID
     *
     * @ORM\Column(name="CustomerID", type="integer")
     */
    private $CustomerID;

    /**
     * @var string $PackageNumber
     *
     * @ORM\Column(name="PackageNumber", type="string", length=255,nullable=true)
     */
    private $PackageNumber;

    /**
     * @var integer $Picklist
     *
     * @ORM\Column(name="Picklist", type="integer", nullable=true)
     */
    private $Picklist;

    /**
     * @var float $TotalBrutto
     *
     * @ORM\Column(name="TotalBrutto", type="float",nullable=true)
     */
    private $TotalBrutto;

    /**
     * @var float $ShippingCosts
     *
     * @ORM\Column(name="ShippingCosts", type="float",nullable=true)
     */
    private $ShippingCosts;

    /**
 * @var float $DoneTimestamp
 *
 * @ORM\Column(name="DoneTimestamp", type="string",length=255,nullable=true)
 */
    private $DoneTimestamp;


    /**
     * @var float $PaidTimestamp
     *
     * @ORM\Column(name="PaidTimestamp", type="string",length=255,nullable=true)
     */
    private $PaidTimestamp;

    /**
     * @var float $PaidStatus
     *
     * @ORM\Column(name="PaidStatus", type="string",length=255,nullable=true)
     */
    private $PaidStatus;





    /**
     * @var string $invoiceNumber
     *
     * @ORM\Column(name="invoice", type="string", length=255,nullable=true)
     */
    private $invoiceNumber;





    /**
     *
    
     *
     * @ORM\ManyToOne(targetEntity="PaymentMethods", inversedBy="Orders")
     * @ORM\JoinColumn(name="payment_id", referencedColumnName="id")
     */
    private $PaymentMethods;

    /**
     * @var string $exportDate
     *
     * @ORM\Column(name="exportDate", type="string", length=255,nullable=true)
     */



    private $exportDate;






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
     * Set Street
     *
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->Street = $street;
    }

    /**
     * Get Street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->Street;
    }

    /**
     * Set Number
     *
     * @param integer $number
     */
    public function setNumber($number)
    {
        $this->Number = $number;
    }

    /**
     * Get Number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->Number;
    }

    /**
     * Set Firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->Firstname = $firstname;
    }

    /**
     * Get Firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->Firstname;
    }

    /**
     * Set Lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->Lastname = $lastname;
    }

    /**
     * Get Lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->Lastname;
    }

    /**
     * Set LastUpdate
     *
     * @param integer $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->LastUpdate = $lastUpdate;
    }

    /**
     * Get LastUpdate
     *
     * @return integer
     */
    public function getLastUpdate()
    {
        return $this->LastUpdate;
    }

    /**
     * Set HouseNumber
     *
     * @param string $houseNumber
     */
    public function setHouseNumber($houseNumber)
    {
        $this->HouseNumber = $houseNumber;
    }

    /**
     * Get HouseNumber
     *
     * @return string 
     */
    public function getHouseNumber()
    {
        return $this->HouseNumber;
    }

    /**
     * Set Company
     *
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->Company = $company;
    }

    /**
     * Get Company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->Company;
    }

    /**
     * Set AdditionalName
     *
     * @param string $additionalName
     */
    public function setAdditionalName($additionalName)
    {
        $this->AdditionalName = $additionalName;
    }

    /**
     * Get AdditionalName
     *
     * @return string 
     */
    public function getAdditionalName()
    {
        return $this->AdditionalName;
    }

    /**
     * Set ZIP
     *
     * @param string $zIP
     */
    public function setZIP($zIP)
    {
        $this->ZIP = $zIP;
    }

    /**
     * Get ZIP
     *
     * @return string 
     */
    public function getZIP()
    {
        return $this->ZIP;
    }

    /**
     * Set City
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->City = $city;
    }

    /**
     * Get City
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->City;
    }

    /**
     * Set Telephone
     *
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->Telephone = $telephone;
    }

    /**
     * Get Telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->Telephone;
    }

    /**
     * Set Email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->Email = $email;
    }

    /**
     * Get Email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * Set Title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->Title = $title;
    }

    /**
     * Get Title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     * Set CountryID
     *
     * @param string $countryID
     */
    public function setCountryID($countryID)
    {
        $this->CountryID = $countryID;
    }

    /**
     * Get CountryID
     *
     * @return string 
     */
    public function getCountryID()
    {
        return $this->CountryID;
    }

    /**
     * Set CustomerID
     *
     * @param integer $customerID
     */
    public function setCustomerID($customerID)
    {
        $this->CustomerID = $customerID;
    }

    /**
     * Get CustomerID
     *
     * @return integer 
     */
    public function getCustomerID()
    {
        return $this->CustomerID;
    }

    /**
     * Set PackageNumber
     *
     * @param string $packageNumber
     */
    public function setPackageNumber($packageNumber)
    {
        $this->PackageNumber = $packageNumber;
    }

    /**
     * Get PackageNumber
     *
     * @return string 
     */
    public function getPackageNumber()
    {
        return $this->PackageNumber;
    }

    /**
     * Set TotalBrutto
     *
     * @param float $totalBrutto
     */
    public function setTotalBrutto($totalBrutto)
    {
        $this->TotalBrutto = $totalBrutto;
    }

    /**
     * Get TotalBrutto
     *
     * @return float 
     */
    public function getTotalBrutto()
    {
        return $this->TotalBrutto;
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
     * Set OrderStatus
     *
     * @param double $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        $this->OrderStatus = $orderStatus;
    }

    /**
     * Get OrderStatus
     *
     * @return double
     */
    public function getOrderStatus()
    {
        return $this->OrderStatus;
    }

  
    public function __construct()
    {
        $this->OrdersInfos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add OrdersInfos
     *
     * @param Acme\BSDataBundle\Entity\OrdersInfo $ordersInfos
     */
    public function addOrdersInfo(\Acme\BSDataBundle\Entity\OrdersInfo $ordersInfos)
    {
        $this->OrdersInfos[] = $ordersInfos;
    }

    /**
     * Get OrdersInfos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrdersInfos()
    {
        return $this->OrdersInfos;
    }

  

    /**
     * Set OrderType
     *
     * @param string $orderType
     */
    public function setOrderType($orderType)
    {
        $this->OrderType = $orderType;
    }

    /**
     * Get OrderType
     *
     * @return string 
     */
    public function getOrderType()
    {
        return $this->OrderType;
    }

    /**
     * Set Picklist
     *
     * @param integer $picklist
     */
    public function setPicklist($picklist)
    {
        $this->Picklist = $picklist;
    }

    /**
     * Get Picklist
     *
     * @return integer
     */
    public function getPicklist()
    {
        return $this->Picklist;
    }

    /**
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * Get invoiceNumber
     *
     * @return string 
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * Set exportDate
     *
     * @param string $exportDate
     */
    public function setExportDate($exportDate)
    {
        $this->exportDate = $exportDate;
    }

    /**
     * Get exportDate
     *
     * @return string 
     */
    public function getExportDate()
    {
        return $this->exportDate;
    }

    /**
     * Set PaymentMethods
     *
     * @param Acme\BSDataBundle\Entity\PaymentMethods $paymentMethods
     */
    public function setPaymentMethods(\Acme\BSDataBundle\Entity\PaymentMethods $paymentMethods)
    {
        $this->PaymentMethods = $paymentMethods;
    }

    /**
     * Get PaymentMethods
     *
     * @return Acme\BSDataBundle\Entity\PaymentMethods 
     */
    public function getPaymentMethods()
    {
        return $this->PaymentMethods;
    }

    /**
     * Set ShippingCosts
     *
     * @param float $shippingCosts
     */
    public function setShippingCosts($shippingCosts)
    {
        $this->ShippingCosts = $shippingCosts;
    }

    /**
     * Get ShippingCosts
     *
     * @return float 
     */
    public function getShippingCosts()
    {
        return $this->ShippingCosts;
    }

    /**
     * Set DoneTimestamp
     *
     * @param string $doneTimestamp
     */
    public function setDoneTimestamp($doneTimestamp)
    {
        $this->DoneTimestamp = $doneTimestamp;
    }

    /**
     * Get DoneTimestamp
     *
     * @return string 
     */
    public function getDoneTimestamp()
    {
        return $this->DoneTimestamp;
    }

    /**
     * Set PaidTimestamp
     *
     * @param string $paidTimestamp
     */
    public function setPaidTimestamp($paidTimestamp)
    {
        $this->PaidTimestamp = $paidTimestamp;
    }

    /**
     * Get PaidTimestamp
     *
     * @return string 
     */
    public function getPaidTimestamp()
    {
        return $this->PaidTimestamp;
    }

    /**
     * Set PaidStatus
     *
     * @param string $paidStatus
     */
    public function setPaidStatus($paidStatus)
    {
        $this->PaidStatus = $paidStatus;
    }

    /**
     * Get PaidStatus
     *
     * @return string 
     */
    public function getPaidStatus()
    {
        return $this->PaidStatus;
    }
}