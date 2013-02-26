<?php

namespace Acme\BSDataBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Acme\BSDataBundle\Entity\Product
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\BSDataBundle\Entity\ProductRepository")
 */
class Product
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
     * @var sting $article_id
     *
     * @ORM\Column(name="article_id", type="integer", length=10)
     */
    private $article_id;

    /**
     * @var sting $article_no
     *
     * @ORM\Column(name="article_no", type="string", length=10)
     */
    private $article_no;

    /**
     * @var sting $EAN
     *
     * @ORM\Column(name="EAN", type="string", length=10,nullable= true )
     */
    private $EAN;


    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $name2
     *
     * @ORM\Column(name="name2", type="string", length=255,nullable= true)
     */
    private $name2;

    /**
     * @var string $name3
     *
     * @ORM\Column(name="name3", type="string", length=255,nullable= true)
     */
    private $name3;

    /**
     * @var string $lastupdate
     *
     * @ORM\Column(name="lastupdate", type="string", length=255,nullable= true)
     */
    private $lastupdate;


    /**
     * @var float $price
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;


    /**
     * @var float $price6
     *
     * @ORM\Column(name="price6", type="float",nullable= true)
     */
    private $price6;

    /**
     * @var integer $VAT
     *
     * @ORM\Column(name="VAT", type="integer",nullable= true)
     */
    private $VAT;



    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text",nullable= true)
     */

    private $label_text;

    /**
     * @var text $quantity
     *
     * @ORM\Column(name="quantity", type="integer",nullable= true)
     */

    private $quantity;

    /**
     * @var integer $PriceID
     *
     * @ORM\Column(name="PriceID", type="integer",nullable= false)
     */

    private $PriceID;

    /**
     * @var integer $AttributeVaueSetID
     *
     * @ORM\Column(name="AttributeVaueSetID", type="integer",nullable= false)
     */

    private $AttributeVaueSetID;



    // todo Many to Many mit Anzahl der im Packt enthaltenen Produkte
    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="bundle")
     */
    protected $bundleitems;

    /**
    * @ORM\ManyToOne(targetEntity="Product", inversedBy="bundleitems")
    * @ORM\JoinColumn(name="bundle_id", referencedColumnName="id")
    **/
    protected $bundle;


    /**
     * @ORM\ManyToOne(targetEntity="Stock", inversedBy="products")
     * @ORM\JoinColumn(name="stock_id", referencedColumnName="id")
     */
    protected $stock;


    public function __construct()
    {
        $this->bundleItems = new ArrayCollection();
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
     * Set article_no
     *
     * @param string $articleNo
     */
    public function setArticleNo( $articleNo)
    {
        $this->article_no = $articleNo;
    }

    /**
     * Get article_no
     *
     * @return string
     */
    public function getArticleNo()
    {
        return $this->article_no;
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
     * Set price
     *
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }



    /**
     * Set label_text
     *
     * @param text $labelText
     */
    public function setLabelText($labelText)
    {
        $this->label_text = $labelText;
    }

    /**
     * Get label_text
     *
     * @return text 
     */
    public function getLabelText()
    {
        return $this->label_text;
    }




    /**
     * Set article_id
     *
     * @param integer $articleId
     */
    public function setArticleId($articleId)
    {
        $this->article_id = $articleId;
    }

    /**
     * Get article_id
     *
     * @return integer 
     */
    public function getArticleId()
    {
        return $this->article_id;
    }



    public function  __toString(){

        return $this->getName();

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


    /**
     * Set name2
     *
     * @param string $name2
     */
    public function setName2($name2)
    {
        $this->name2 = $name2;
    }

    /**
     * Get name2
     *
     * @return string 
     */
    public function getName2()
    {
        return $this->name2;
    }

    /**
     * Set name3
     *
     * @param string $name3
     */
    public function setName3($name3)
    {
        $this->name3 = $name3;
    }

    /**
     * Get name3
     *
     * @return string 
     */
    public function getName3()
    {
        return $this->name3;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set lastupdate
     *
     * @param string $lastupdate
     */
    public function setLastupdate($lastupdate)
    {
        $this->lastupdate = $lastupdate;
    }

    /**
     * Get lastupdate
     *
     * @return string 
     */
    public function getLastupdate()
    {
        return $this->lastupdate;
    }

    /**
     * Set EAN
     *
     * @param string $eAN
     */
    public function setEAN($eAN)
    {
        $this->EAN = $eAN;
    }

    /**
     * Get EAN
     *
     * @return string 
     */
    public function getEAN()
    {
        return $this->EAN;
    }

    /**
     * Set price6
     *
     * @param float $price6
     */
    public function setPrice6($price6)
    {
        $this->price6 = $price6;
    }

    /**
     * Get price6
     *
     * @return float 
     */
    public function getPrice6()
    {
        return $this->price6;
    }

    /**
     * Set VAT
     *
     * @param integer $vAT
     */
    public function setVAT($vAT)
    {
        $this->VAT = $vAT;
    }

    /**
     * Get VAT
     *
     * @return integer 
     */
    public function getVAT()
    {
        return $this->VAT;
    }

    /**
     * Add bundleitems
     *
     * @param Acme\BSDataBundle\Entity\Product $bundleitems
     */
    public function addProduct(\Acme\BSDataBundle\Entity\Product $bundleitems)
    {
        $this->bundleitems[] = $bundleitems;
    }

    /**
     * Clear bundleitems
     *
     */


    public function clearBundleitems()
    {
        $this->bundleItems = new ArrayCollection();
    }


    /**
     * Get bundleitems
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBundleitems()
    {
        return $this->bundleitems;
    }

    /**
     * Set bundle
     *
     * @param Acme\BSDataBundle\Entity\Product $bundle
     */
    public function setBundle(\Acme\BSDataBundle\Entity\Product $bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * Get bundle
     *
     * @return Acme\BSDataBundle\Entity\Product 
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * Set PriceID
     *
     * @param integer $priceID
     */
    public function setPriceID($priceID)
    {
        $this->PriceID = $priceID;
    }

    /**
     * Get PriceID
     *
     * @return integer 
     */
    public function getPriceID()
    {
        return $this->PriceID;
    }

    /**
     * Set AttributeVaueSetID
     *
     * @param integer $attributeVaueSetID
     */
    public function setAttributeVaueSetID($attributeVaueSetID)
    {
        $this->AttributeVaueSetID = $attributeVaueSetID;
    }

    /**
     * Get AttributeVaueSetID
     *
     * @return integer 
     */
    public function getAttributeVaueSetID()
    {
        return $this->AttributeVaueSetID;
    }
}