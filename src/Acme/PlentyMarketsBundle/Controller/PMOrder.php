<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Florian Engler 
 * Mail: florian.engler@gmx.de
 * Date: 18.04.13
 * Time: 09:02
 */

namespace Acme\PlentyMarketsBundle\Controller;


class PMOrder {

    /**
     *
     * @var PMOrderHead
     */
    public $OrderHead;

    /**
     *
     *
     */
    public $OrderItems;

    /**
     *
     *
     */
    public $OrderDeliveryAddress;

    /**
     *
     * @var int
     */
    public $TemplateID;

}