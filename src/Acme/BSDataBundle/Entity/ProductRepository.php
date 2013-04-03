<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Florian Engler
 * Mail: florian.engler@gmx.de
 * Date: 15.02.13
 * Time: 17:06
 */
namespace Acme\BSDataBundle\Entity;

use Doctrine\ORM\EntityRepository;


class ProductRepository extends EntityRepository
{


    public function PMSoapProduct(Product $product,$item)
    {
        $product->setArticleId($item->ItemID);
        $product->setArticleNo( $item->ItemNo );
        // $product->setBotanical($item->FreeTextFields->Free2);
        // $this->setDescription($item->Texts->LongDescription);
        $product->setLabelText($item->FreeTextFields->Free3);
        $product->setName($item->Texts->Name);
        $product->setName2($item->Texts->Name2);
        $product->setPriceID($item->PriceSet->PriceID);
        $product->setPrice($item->PriceSet->Price);
        $product->setPrice6($item->PriceSet->Price6);
        $product->setAttributeVaueSetID(is_null($item->AttributeValueSets)? 0 :$item->AttributeValueSets);
        if($item->VATInternalID == 0) $product->setVAT(19);
        elseif($item->VATInternalID == 1 )$product->setVAT(7);
        else $product->setVAT(0);
        $product->setEAN($item->EAN1);
        $product->setLastupdate( $item->LastUpdate);
        // $product->setShortDescription($item->Texts->ShortDescription);
        //$product->setStockground();
        return $product;

    }

    public function newPMSoapOrderProduct(Product $product,OrdersItem $item)
    {
        $product->setArticleId($item->getArticleID());
        $product->setArticleNo($item->getArticleID());
        //$this->setBotanical($item->FreeTextFields->Free2);
        //$this->setDescription( $item->getItemText());

        $product->setName($item->getItemText());
        $product->setPrice($item->getPrice());
        //  $this->setShortDescription($item->getItemText());

        return $product;
    }


    /**
     * Returns the class name of the object managed by the repository
     *
     * @return string
     */
    function getClassName()
    {
        return "ProductRepository";
    }
}
