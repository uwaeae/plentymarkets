<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\BSDataBundle\Entity\Plants
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 *
$em = $this->get('doctrine.orm.entity_manager');
$dql = "SELECT a FROM BSDataBundle:Plant a";
$query = $em->createQuery($dql);

$paginator = $this->get('knp_paginator');
$pagination = $paginator->paginate(
$query,
$this->get('request')->query->get('page', 1),
10
);
return array('pagination' => $pagination);

 *
 *
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
     * @ORM\Column(name="name", type="string", length=255,nullable=true)
     */
    private $name;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var integer $latein
     *
     * @ORM\Column(name="latein",  type="string", length=255)
     */
    private $latein;

    /**
     * @var string $hardy
     *
     * @ORM\Column(name="hardy", type="string", length=255,nullable=true)
     */
    private $hardy;

    /**
     * @var string $place
     *
     * @ORM\Column(name="place", type="string", length=255,nullable=true)
     */
    private $place;

    /**
     * @var datetime $LastUpdate
     *
     * @ORM\Column(name="LastUpdate", type="datetime",nullable=true)
     */
    private $LastUpdate;


    /**
     * @var string $synonym
     *
     * @ORM\Column(name="synonym", type="string", length=255,nullable=true)
     */
    private $synonym;


    /**
     * @var string $instructions
     *
     * @ORM\Column(name="instructions", type="text",nullable=true)
     */
    private $instructions;


    /**
     * @var integer $h_from
     * @orm\Column(name="h_from",type="integer",nullable=true)
     */
    private $h_from;

    /**
     * @var integer $h_to
     * @orm\Column(name="h_to",type="integer",nullable=true)
     */
    private $h_to;


    /**
     * @var integer $b_from
     * @orm\Column(name="b_from",type="integer",nullable=true)
     */
    private $b_from;

    /**
     * @var integer $b_to
     * @orm\Column(name="b_to",type="integer",nullable=true)
     */
    private $b_to;

    /**
     * @var string $b_color
     *
     *
     * @ORM\Column(name="b_color", type="string", length=255,nullable=true)
     */
    private $b_color;

    /**
     * @var string $flavour
     *
     *
     * @ORM\Column(name="flavour", type="string", length=255,nullable=true)
     */
    private $flavour;

    /**
     * @var string $light
     *
     *
     * @ORM\Column(name="light", type="string", length=255,nullable=true)
     */
    private $light;

    /**
     * @var string $base
     *
     *
     * @ORM\Column(name="base", type="string", length=255,nullable=true)
     */
    private $base;

    /**
     * @var string $labeltext
     *
     *
     * @ORM\Column(name="labeltext", type="text", nullable=true)
     */
    private $labeltext;

    /**
 * @var string $habitus
 *
 *
 * @ORM\Column(name="habitus", type="string", length=255,nullable=true)
 */
    private $habitus;

    /**
     * @var string $pricegroup
     *
     *
     * @ORM\Column(name="pricegroup", type="integer",nullable=true)
     */
    private $pricegroup;

    /**
     * @var string $potsize
     *
     *
     * @ORM\Column(name="potsize", type="string", length=255,nullable=true)
     */
    private $potsize;


    /**
     * @var string $aviable
     *
     *
     * @ORM\Column(name="aviable", type="string", length=255,nullable=true)
     */
    private $aviable;


    /**
     * @var string $comment
     *
     *
     * @ORM\Column(name="comment", type="string", length=255,nullable=true)
     */

    private $comment;


    /**
     * @ORM\ManyToOne(targetEntity="Stock", inversedBy="plant")
     * @ORM\JoinColumn(name="stock_id", referencedColumnName="id",nullable= true)
     */
    protected $stock;



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
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set latein
     *
     * @param string $latein
     */
    public function setLatein($latein)
    {
        $this->latein = $latein;
    }

    /**
     * Get latein
     *
     * @return string 
     */
    public function getLatein()
    {
        return $this->latein;
    }

    /**
     * Set hardy
     *
     * @param string $hardy
     */
    public function setHardy($hardy)
    {
        $this->hardy = $hardy;
    }

    /**
     * Get hardy
     *
     * @return string 
     */
    public function getHardy()
    {
        return $this->hardy;
    }

    /**
     * Set place
     *
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * Get place
     *
     * @return string 
     */
    public function getPlace()
    {
        return $this->place;
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
     * Set instructions
     *
     * @param string $instructions
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;
    }

    /**
     * Get instructions
     *
     * @return string 
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set h_from
     *
     * @param integer $hFrom
     */
    public function setHFrom($hFrom)
    {
        $this->h_from = $hFrom;
    }

    /**
     * Get h_from
     *
     * @return integer 
     */
    public function getHFrom()
    {
        return $this->h_from;
    }

    /**
     * Set h_to
     *
     * @param integer $hTo
     */
    public function setHTo($hTo)
    {
        $this->h_to = $hTo;
    }

    /**
     * Get h_to
     *
     * @return integer 
     */
    public function getHTo()
    {
        return $this->h_to;
    }

    /**
     * Set b_from
     *
     * @param integer $bFrom
     */
    public function setBFrom($bFrom)
    {
        $this->b_from = $bFrom;
    }

    /**
     * Get b_from
     *
     * @return integer 
     */
    public function getBFrom()
    {
        return $this->b_from;
    }

    /**
     * Set b_to
     *
     * @param integer $bTo
     */
    public function setBTo($bTo)
    {
        $this->b_to = $bTo;
    }

    /**
     * Get b_to
     *
     * @return integer 
     */
    public function getBTo()
    {
        return $this->b_to;
    }

    /**
     * Set b_color
     *
     * @param string $bColor
     */
    public function setBColor($bColor)
    {
        $this->b_color = $bColor;
    }

    /**
     * Get b_color
     *
     * @return string 
     */
    public function getBColor()
    {
        return $this->b_color;
    }

    /**
     * Set flavour
     *
     * @param string $flavour
     */
    public function setFlavour($flavour)
    {
        $this->flavour = $flavour;
    }

    /**
     * Get flavour
     *
     * @return string 
     */
    public function getFlavour()
    {
        return $this->flavour;
    }

    /**
     * Set light
     *
     * @param string $light
     */
    public function setLight($light)
    {
        $this->light = $light;
    }

    /**
     * Get light
     *
     * @return string 
     */
    public function getLight()
    {
        return $this->light;
    }

    /**
     * Set base
     *
     * @param string $base
     */
    public function setBase($base)
    {
        $this->base = $base;
    }

    /**
     * Get base
     *
     * @return string 
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set labeltext
     *
     * @param text $labeltext
     */
    public function setLabeltext($labeltext)
    {
        $this->labeltext = $labeltext;
    }

    /**
     * Get labeltext
     *
     * @return text 
     */
    public function getLabeltext()
    {
        return $this->labeltext;
    }

    /**
     * Set habitus
     *
     * @param string $habitus
     */
    public function setHabitus($habitus)
    {
        $this->habitus = $habitus;
    }

    /**
     * Get habitus
     *
     * @return string 
     */
    public function getHabitus()
    {
        return $this->habitus;
    }

    /**
     * Set pricegroup
     *
     * @param integer $pricegroup
     */
    public function setPricegroup($pricegroup)
    {
        $this->pricegroup = $pricegroup;
    }

    /**
     * Get pricegroup
     *
     * @return integer 
     */
    public function getPricegroup()
    {
        return $this->pricegroup;
    }

    /**
     * Set potsize
     *
     * @param string $potsize
     */
    public function setPotsize($potsize)
    {
        $this->potsize = $potsize;
    }

    /**
     * Get potsize
     *
     * @return string 
     */
    public function getPotsize()
    {
        return $this->potsize;
    }

    /**
     * Set aviable
     *
     * @param string $aviable
     */
    public function setAviable($aviable)
    {
        $this->aviable = $aviable;
    }

    /**
     * Get aviable
     *
     * @return string 
     */
    public function getAviable()
    {
        return $this->aviable;
    }

    /**
     * Set comment
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set stock
     *
     * @param Acme\BSDataBundle\Entity\Stock $stock
     */
    public function setStock(\Acme\BSDataBundle\Entity\Stock $stock)
    {
        $this->stock = $stock;
    }

    /**
     * Get stock
     *
     * @return Acme\BSDataBundle\Entity\Stock 
     */
    public function getStock()
    {
        return $this->stock;
    }
}