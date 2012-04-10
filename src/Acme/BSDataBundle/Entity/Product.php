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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float $price
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text",nullable= true)
     */

    private $label_text;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Plant", inversedBy="products")
     * @ORM\JoinColumn(name="plant_id", referencedColumnName="produkt_id",nullable= true)

     */
    private $plants;




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
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set short_description
     *
     * @param text $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;
    }

    /**
     * Get short_description
     *
     * @return text 
     */
    public function getShortDescription()
    {
        return $this->short_description;
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
     * Set botanical
     *
     * @param text $botanical
     */
    public function setBotanical($botanical)
    {
        $this->botanical = $botanical;
    }

    /**
     * Get botanical
     *
     * @return text 
     */
    public function getBotanical()
    {
        return $this->botanical;
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

    public function newPMSoapProduct($item)
    {


        $this->setArticleId($item->ItemID);
        $this->setArticleNo( $item->ItemNo );
        $this->setBotanical($item->FreeTextFields->Free2);
        $this->setDescription($item->Texts->LongDescription);
        $this->setLabelText($item->FreeTextFields->Free3);
        $this->setName($item->Texts->Name);
        $this->setPrice($item->PriceSet->Price);
        $this->setShortDescription($item->Texts->ShortDescription);
        //$this->setStockground();

    }

    public function newPMSoapOrderProduct(OrdersItem $item)
    {
        $this->setArticleId($item->getArticleID());
        $this->setArticleNo($item->getArticleID());
        //$this->setBotanical($item->FreeTextFields->Free2);
        $this->setDescription($item->getItemText());
        //$this->setLabelText($item->FreeTextFields->Free3);
        $this->setName($item->getItemText());
        $this->setPrice($item->getPrice());
        $this->setShortDescription($item->getItemText());
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
}