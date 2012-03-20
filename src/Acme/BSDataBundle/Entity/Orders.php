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
     * @var integer $OrderStatus
     *
     * @ORM\Column(name="OrderStatus", type="integer")
     */
    private $OrderStatus;



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
     * @ORM\Column(name="Company", type="string", length=255)
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
     * @ORM\Column(name="City", type="string", length=255)
     */
    private $City;

    /**
     * @var string $Telephone
     *
     * @ORM\Column(name="Telephone", type="string", length=255)
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
     * @var float $TotalBrutto
     *
     * @ORM\Column(name="TotalBrutto", type="float")
     */
    private $TotalBrutto;


    /**
     * @ORM\OneToMany(targetEntity="OrdersInfo", mappedBy="Orders")
     * @var ArrayCollection $OrdersInfos
     */
    private $OrdersInfos;






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
     * @param integer $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        $this->OrderStatus = $orderStatus;
    }

    /**
     * Get OrderStatus
     *
     * @return integer 
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
}