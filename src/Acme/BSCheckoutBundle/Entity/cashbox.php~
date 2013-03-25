<?php

namespace Acme\BSCheckoutBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\BSCheckoutBundle\Entity\cashbox
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\BSCheckoutBundle\Entity\cashboxRepository")
 */
class cashbox
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var array $config
     *
     * @ORM\Column(name="config", type="array")
     */
    private $config;

    /**
     * @ORM\OneToMany(targetEntity="quickbutton", mappedBy="cashbox")
     */

    private $quickbuttons;

    /**
     * @ORM\OneToMany(targetEntity="checkout", mappedBy="cashbox")
     */

    private $checkouts;


    public function __construct()
    {
        $this->checkouts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString(){
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set config
     *
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Get config
     *
     * @return array 
     */
    public function getConfig()
    {
        return $this->config;
    }

   
    
    /**
     * Add checkouts
     *
     * @param Acme\BSCheckoutBundle\Entity\checkout $checkouts
     */
    public function addcheckout(\Acme\BSCheckoutBundle\Entity\checkout $checkouts)
    {
        $this->checkouts[] = $checkouts;
    }

    /**
     * Get checkouts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCheckouts()
    {
        return $this->checkouts;
    }

    /**
     * Add quickbuttons
     *
     * @param Acme\BSCheckoutBundle\Entity\quickbutton $quickbuttons
     */
    public function addquickbutton(\Acme\BSCheckoutBundle\Entity\quickbutton $quickbuttons)
    {
        $this->quickbuttons[] = $quickbuttons;
    }

    /**
     * Get quickbuttons
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getQuickbuttons()
    {
        return $this->quickbuttons;
    }
}