<?php

namespace Acme\PlentyMarketsBundle\Controller\PMSOAP;

class PlentySoapObject_Order
{
	/**
	 *
	 * @var PlentySoapObject_OrderHead
	 */
	public $OrderHead;

	/**
	 *
	 * @var ArrayOfPlentysoapobject_orderitem
	 */
	public $OrderItems;

	/**
	 *
	 * @var PlentySoapObject_DeliveryAddress
	 */
	public $OrderDeliveryAddress;

	/**
	 *
	 * @var int
	 */
	public $TemplateID;

}
