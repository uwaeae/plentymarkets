<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Florian Engler 
 * Mail: florian.engler@gmx.de
 * Date: 22.03.13
 * Time: 15:46
 */

namespace Acme\PlentyMarketsBundle\Controller;


class PMOrderItem {

    /**
     *
     * @var int
     */
    public $OrderID;

    /**
     *
     * @var int
     */
    public $OrderRowID;

    /**
     *
     * @var int
     */
    public $ItemID;

    /**
     *
     * @var string
     */
    public $SKU;

    /**
     *
     * @var int
     */
    public $BundleItemID;

    /**
     *
     * @var string
     */
    public $ExternalOrderItemID;

    /**
     *
     * @var int
     */
    public $ReferrerID;

    /**
     *
     * @var float
     */
    public $Quantity;

    /**
     *
     * @var string
     */
    public $ItemText;

    /**
     *
     * @var float
     */
    public $VAT;

    /**
     *
     * @var float
     */
    public $Price;

    /**
     *
     * @var int
     */
    public $WarehouseID;

    /**
     *
     * @var string
     */
    public $Currency;

    /**
     *
     * @var string
     */
    public $NeckermannItemNo;

    /**
     *
     * @var string
     */
    public $ItemNo;

    /**
     *
     * @var string
     */
    public $ExternalItemID;

    /**
     *
     * @var ArrayOfPlentysoapobject_salesorderproperty
     */
    //public $SalesOrderProperties;


}