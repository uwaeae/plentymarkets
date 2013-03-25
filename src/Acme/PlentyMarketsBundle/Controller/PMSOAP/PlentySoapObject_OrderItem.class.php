<?php

namespace Acme\PlentyMarketsBundle\Controller\PMSOAP;

class PlentySoapObject_OrderItem
{
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
	public $SalesOrderProperties;

}
