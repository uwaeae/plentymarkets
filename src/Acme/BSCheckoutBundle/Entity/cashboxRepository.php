<?php

namespace Acme\BSCheckoutBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * cashboxRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class cashboxRepository extends EntityRepository
{


    /**
     * Returns the class name of the object managed by the repository
     *
     * @return string
     */
    function getClassName()
    {
        return "cashboxRepository";
    }




}