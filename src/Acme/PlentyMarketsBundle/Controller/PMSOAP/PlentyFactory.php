<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Florian Engler 
 * Mail: florian.engler@gmx.de
 * Date: 20.03.13
 * Time: 19:41
 */

namespace Acme\PlentyMarketsBundle\Controller\PMSOAP;


class PlentyFactory {

    public static function get($class){

        return new PlentySoapRequest_AddOrders();

    }

}