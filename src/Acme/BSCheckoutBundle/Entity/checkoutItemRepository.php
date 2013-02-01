<?php

namespace Acme\BSCheckoutBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * checkoutItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class checkoutItemRepository extends EntityRepository
{


    public function addItem($basket,$code,$price){

        $em = $this->getEntityManager();

        $qb = $this->createQueryBuilder('i');
        $qb->where('i.checkout = ?1');
        $qb->setParameter(1,$basket);
        $qb->andWhere('i.articleCode like ?2');
        $qb->setParameter(2,$code.'%');
        try{
            $item = $qb->getQuery()->setMaxResults(1)->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e) {
            $item = null;

        }


        if(!$item || $price){
            $qb = $em->createQueryBuilder();
            $qb->add('select', 'p')
                ->add('from', 'BSDataBundle:Product p')
                ->add('where',
                $qb->expr()->like('p.article_no', '?1')
            )->setParameter('1', $code.'%');

            try{
                $product =  $qb->getQuery()->setMaxResults(1)->getSingleResult();
            } catch (\Doctrine\Orm\NoResultException $e) {
                $product = null;

            }
            if($product){
                $co_item = new \Acme\BSCheckoutBundle\Entity\checkoutItem();
                $co_item->setArticleCode($product->getArticleNo());
                $co_item->setArticleId($product->getArticleId());
                $co_item->setCheckout($basket);
                $co_item->setDescription($product->getName().' '.$product->getName2());
                $co_item->setPrice(is_null($price)? $product->getPrice() : $price);
                $co_item->setQuantity(1);
                $em->persist($co_item);

            }

        }else{
            $item->setQuantity($item->getQuantity() + 1 );
            $em->persist($item);
        }

        $em->flush();


    }
}