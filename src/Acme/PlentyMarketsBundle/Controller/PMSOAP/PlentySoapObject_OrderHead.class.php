<?php

namespace Acme\PlentyMarketsBundle\Controller\PMSOAP;

class PlentySoapObject_OrderHead
{
	/**
	 *
	 * @var int
	 */
	public $OrderID;

	/**
	 *
	 * @var string
	 */
	public $ExternalOrderID;

	/**
	 *
	 * @var string
	 */
	public $OrderType;

	/**
	 *
	 * @var int
	 */
	public $ParentOrderID;

	/**
	 *
	 * @var float
	 */
	public $OrderStatus;

	/**
	 *
	 * @var int
	 */
	public $OrderTimestamp;

	/**
	 *
	 * @var int
	 */
	public $CustomerID;

	/**
	 *
	 * @var int
	 */
	public $DeliveryAddressID;

	/**
	 *
	 * @var int
	 */
	public $MethodOfPaymentID;

	/**
	 *
	 * @var int
	 */
	public $ShippingMethodID;

	/**
	 *
	 * @var int
	 */
	public $ShippingProfileID;

	/**
	 *
	 * @var int
	 */
	public $ShippingID;

	/**
	 *
	 * @var float
	 */
	public $ShippingCosts;

	/**
	 *
	 * @var int
	 */
	public $ReferrerID;

	/**
	 *
	 * @var int
	 */
	public $Marking1ID;

	/**
	 *
	 * @var int
	 */
	public $ResponsibleID;

	/**
	 *
	 * @var int
	 */
	public $WarehouseID;

	/**
	 *
	 * @var int
	 */
	public $MultishopID;

	/**
	 *
	 * @var string
	 */
	public $Currency;

	/**
	 *
	 * @var int
	 */
	public $SalesAgentID;

	/**
	 *
	 * @var string
	 */
	public $PackageNumber;

	/**
	 *
	 * @var string
	 */
	public $EstimatedTimeOfShipment;

	/**
	 *
	 * @var string
	 */
	public $PaidTimestamp;

	/**
	 *
	 * @var string
	 */
	public $DoneTimestamp;

	/**
	 *
	 * @var string
	 */
	public $Invoice;

	/**
	 *
	 * @var int
	 */
	public $LastUpdate;

	/**
	 *
	 * @var int
	 */
	public $PaymentStatus;

	/**
	 *
	 * @var float
	 */
	public $TotalVAT;

	/**
	 *
	 * @var float
	 */
	public $TotalNetto;

	/**
	 *
	 * @var float
	 */
	public $TotalBrutto;

	/**
	 *
	 * @var boolean
	 */
	public $IsNetto;

	/**
	 *
	 * @var string
	 */
	public $InvoiceNumber;

	/**
	 *
	 * @var string
	 */
	public $RemoteIP;

	/**
	 *
	 * @var ArrayOfPlentysoapobject_orderinfo
	 */
	public $OrderInfos;

	/**
	 *
	 * @var string
	 */
	public $SellerAccount;

	/**
	 *
	 * @var string
	 */
	public $EbaySellerAccount;

	/**
	 *
	 * @var float
	 */
	public $ExchangeRatio;

	/**
	 *
	 * @var int
	 */
	public $DunningLevel;

	/**
	 *
	 * @var int
	 */
	public $TotalInvoice;

}
