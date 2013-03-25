<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Florian Engler 
 * Mail: florian.engler@gmx.de
 * Date: 21.03.13
 * Time: 15:54
 */

namespace Acme\PlentyMarketsBundle\Controller;


class RequestAddOrders {

    /**
     *
     * @var array
     */
    public $Orders;

    function __construct(){
    
        $this->Orders = array();


    }

    function newItem(){

        /**
         *
         * @var PMOrderHead
         */
        $OrderHead =  new PMOrderHead();

        /**
         *
         * @var array
         */
        $OrderItems = array();

        /**
         *
         * @var PMDeliveryAddress
         */
        $OrderDeliveryAddress  = new PMDeliveryAddress();

        /**
         *
         * @var int
         */
        $TemplateID = 0;

        $this->Orders[] = array(
            'OrderHead'=>$OrderHead,
            'OrderItems'=>$OrderItems,
            'OrderDeliveryAddress'=>$OrderDeliveryAddress,
            'TemplateID'=> $TemplateID
        );
    }



}