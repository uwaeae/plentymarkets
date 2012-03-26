<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\BSDataBundle\Entity\Plants
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Plant
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
     * @var integer $botanic
     *
     * @ORM\Column(name="botanic", type="integer")
     */
    private $botanic;

    /**
     * @var string $winter
     *
     * @ORM\Column(name="winter", type="string", length=255)
     */
    private $winter;

    /**
     * @var string $standort
     *
     * @ORM\Column(name="standort", type="string", length=255)
     */
    private $standort;

    /**
     * @var datetime $LastUpdate
     *
     * @ORM\Column(name="LastUpdate", type="datetime",nullable=true)
     */
    private $LastUpdate;



    /**
     * @var string $gattung
     *
     * @ORM\Column(name="gattung", type="string", length=255)
     */
    private $gattung;

    /**
     * @var string $synonym
     *
     * @ORM\Column(name="synonym", type="string", length=255)
     */
    private $synonym;

    /**
     * @var string $bluetezeit
     *
     * @ORM\Column(name="bluetezeit", type="string", length=255,nullable=true)
     */
    private $bluetezeit;


    /**
     * @var string $pflege
     *
     * @ORM\Column(name="pflege", type="string", length=255,nullable=true)
     */
    private $pflege;


    /**
     * @var integer $h_von
     * @orm\Column(name="h_von",type="integer",nullable=true)
     */
    private $h_von;

    /**
     * @var integer $h_bis
     * @orm\Column(name="h_bis",type="integer",nullable=true)
     */
    private $h_bis;

    /**
     * @var integer $pabstand
     * @orm\Column(name="pabstand",type="integer",nullable=true)
     */
    private $pabstand;

    /**
     * @var integer $b_von
     * @orm\Column(name="b_von",type="integer",nullable=true)
     */
    private $b_von;

    /**
     * @var integer $b_bis
     * @orm\Column(name="b_bis",type="integer",nullable=true)
     */
    private $b_bis;

    /**
     * @var string $b_farbe
     *
     *
     * @ORM\Column(name="b_farbe", type="string", length=255,nullable=true)
     */
    private $b_farbe;

    /**
     * @var string $duft
     *
     *
     * @ORM\Column(name="duft", type="string", length=255,nullable=true)
     */
    private $duft;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="plants")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="plants_id",nullable= true)
     */
    private $plant;



    //Zähler	Pflanzen_Id	Gattung_ID	Art	Sorte	D_Gattung	D_Präfix	D_Synonym	Standort	Blütezeit	Etikettentext
    //Nur_Mutterpflanze	Lat_Synonym	PflegeTxt_ID	Pflege	Höhe_von	Höhe_bis	PAbstand	Schl_Überwint_ID
    //Schl_Blütenfarbe_ID	Schl_SBereich_ID	Schl_SBoden_ID	Schl_SHabitus_ID	Schl_SLicht_ID	Schl_PGruppe_ID	P_Sort_Kat_Id
    //Sortiment	Duft	Marker	bl1	bl2	bl3	bl4	bl5	bl6	bl7	bl8	bl9	bl10	bl11	bl12	Blüte_von	Blüte_bis	Gattung_alt
    //Familie_ID_x	Deutscher Name_x


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
     * Set botanic
     *
     * @param integer $botanic
     */
    public function setBotanic($botanic)
    {
        $this->botanic = $botanic;
    }

    /**
     * Get botanic
     *
     * @return integer 
     */
    public function getBotanic()
    {
        return $this->botanic;
    }

    /**
     * Set winter
     *
     * @param string $winter
     */
    public function setWinter($winter)
    {
        $this->winter = $winter;
    }

    /**
     * Get winter
     *
     * @return string 
     */
    public function getWinter()
    {
        return $this->winter;
    }

    /**
     * Set standort
     *
     * @param string $standort
     */
    public function setStandort($standort)
    {
        $this->standort = $standort;
    }

    /**
     * Get standort
     *
     * @return string 
     */
    public function getStandort()
    {
        return $this->standort;
    }

    /**
     * Set LastUpdate
     *
     * @param datetime $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->LastUpdate = $lastUpdate;
    }

    /**
     * Get LastUpdate
     *
     * @return datetime 
     */
    public function getLastUpdate()
    {
        return $this->LastUpdate;
    }

    /**
     * Set gattung
     *
     * @param string $gattung
     */
    public function setGattung($gattung)
    {
        $this->gattung = $gattung;
    }

    /**
     * Get gattung
     *
     * @return string 
     */
    public function getGattung()
    {
        return $this->gattung;
    }

    /**
     * Set synonym
     *
     * @param string $synonym
     */
    public function setSynonym($synonym)
    {
        $this->synonym = $synonym;
    }

    /**
     * Get synonym
     *
     * @return string 
     */
    public function getSynonym()
    {
        return $this->synonym;
    }

    /**
     * Set bluetezeit
     *
     * @param string $bluetezeit
     */
    public function setBluetezeit($bluetezeit)
    {
        $this->bluetezeit = $bluetezeit;
    }

    /**
     * Get bluetezeit
     *
     * @return string 
     */
    public function getBluetezeit()
    {
        return $this->bluetezeit;
    }

    /**
     * Set pflege
     *
     * @param string $pflege
     */
    public function setPflege($pflege)
    {
        $this->pflege = $pflege;
    }

    /**
     * Get pflege
     *
     * @return string 
     */
    public function getPflege()
    {
        return $this->pflege;
    }

    /**
     * Set h_von
     *
     * @param integer $hVon
     */
    public function setHVon($hVon)
    {
        $this->h_von = $hVon;
    }

    /**
     * Get h_von
     *
     * @return integer 
     */
    public function getHVon()
    {
        return $this->h_von;
    }

    /**
     * Set h_bis
     *
     * @param integer $hBis
     */
    public function setHBis($hBis)
    {
        $this->h_bis = $hBis;
    }

    /**
     * Get h_bis
     *
     * @return integer 
     */
    public function getHBis()
    {
        return $this->h_bis;
    }

    /**
     * Set pabstand
     *
     * @param integer $pabstand
     */
    public function setPabstand($pabstand)
    {
        $this->pabstand = $pabstand;
    }

    /**
     * Get pabstand
     *
     * @return integer 
     */
    public function getPabstand()
    {
        return $this->pabstand;
    }

    /**
     * Set b_von
     *
     * @param integer $bVon
     */
    public function setBVon($bVon)
    {
        $this->b_von = $bVon;
    }

    /**
     * Get b_von
     *
     * @return integer 
     */
    public function getBVon()
    {
        return $this->b_von;
    }

    /**
     * Set b_bis
     *
     * @param integer $bBis
     */
    public function setBBis($bBis)
    {
        $this->b_bis = $bBis;
    }

    /**
     * Get b_bis
     *
     * @return integer 
     */
    public function getBBis()
    {
        return $this->b_bis;
    }

    /**
     * Set b_farbe
     *
     * @param string $bFarbe
     */
    public function setBFarbe($bFarbe)
    {
        $this->b_farbe = $bFarbe;
    }

    /**
     * Get b_farbe
     *
     * @return string 
     */
    public function getBFarbe()
    {
        return $this->b_farbe;
    }

    /**
     * Set duft
     *
     * @param string $duft
     */
    public function setDuft($duft)
    {
        $this->duft = $duft;
    }

    /**
     * Get duft
     *
     * @return string 
     */
    public function getDuft()
    {
        return $this->duft;
    }

    public function __construct()
    {
        $this->plant = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add plant
     *
     * @param Acme\BSDataBundle\Entity\Product $plant
     */
    public function addProduct(\Acme\BSDataBundle\Entity\Product $plant)
    {
        $this->plant[] = $plant;
    }

    /**
     * Get plant
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlant()
    {
        return $this->plant;
    }
}