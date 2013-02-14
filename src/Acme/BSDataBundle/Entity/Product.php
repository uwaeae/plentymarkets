<?php

namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\BSDataBundle\Entity\Product
 *
 * @ORM\Table()
 * @ORM\Entity()
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
     * @ORM\ManyToOne(targetEntity="Stock", inversedBy="products")
     * @ORM\JoinColumn(name="stock_id", referencedColumnName="id")
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

    public function PMSoapProduct($item)
    {
        $this->setArticleId($item->ItemID);
        $this->setArticleNo( $item->ItemNo );
       // $this->setBotanical($item->FreeTextFields->Free2);
       // $this->setDescription($item->Texts->LongDescription);
        $this->setLabelText($item->FreeTextFields->Free3);
        $this->setName($item->Texts->Name);
        $this->setName2($item->Texts->Name2);
        $this->setPrice($item->PriceSet->Price);
        $this->setPrice6($item->PriceSet->Price6);
        if($item->VATInternalID == 0) $this->setVAT(19);
        elseif($item->VATInternalID == 1 )$this->setVAT(7);
        else $this->setVAT(0);
        $this->setEAN($item->EAN1);
        $this->setLastupdate( $item->LastUpdate);
       // $this->setShortDescription($item->Texts->ShortDescription);
        //$this->setStockground();

    }

    public function newPMSoapOrderProduct(OrdersItem $item)
    {
        $this->setArticleId($item->getArticleID());
        $this->setArticleNo($item->getArticleID());
        //$this->setBotanical($item->FreeTextFields->Free2);
        //$this->setDescription( $item->getItemText());

        $this->setName($item->getItemText());
        $this->setPrice($item->getPrice());
      //  $this->setShortDescription($item->getItemText());
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
    public function __construct()
    {
        $this->plants = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add plants
     *
     * @param Acme\BSDataBundle\Entity\Plant $plants
     */
    public function addPlant(\Acme\BSDataBundle\Entity\Plant $plants)
    {
        $this->plants[] = $plants;
    }

    /**
     * Get plants
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlants()
    {
        return $this->plants;
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
}