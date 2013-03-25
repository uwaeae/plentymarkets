<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Florian Engler 
 * Mail: florian.engler@gmx.de
 * Date: 22.03.13
 * Time: 15:45
 */

namespace Acme\PlentyMarketsBundle\Controller;


class PMDeliveryAddress {

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
     * @var string
     */
    public $ExternalDeliveryAddressID;

    /**
     *
     * @var string
     */
    public $Evaluation;

    /**
     *
     * @var string
     */
    public $Company;

    /**
     *
     * @var string
     */
    public $AdditionalName;

    /**
     *
     * @var int
     */
    public $FormOfAddress;

    /**
     *
     * @var string
     */
    public $FirstName;

    /**
     *
     * @var string
     */
    public $Surname;

    /**
     *
     * @var string
     */
    public $Street;

    /**
     *
     * @var string
     */
    public $HouseNumber;

    /**
     *
     * @var string
     */
    public $ZIP;

    /**
     *
     * @var string
     */
    public $City;

    /**
     *
     * @var int
     */
    public $CountryID;

    /**
     *
     * @var string
     */
    public $CountryISO2;

    /**
     *
     * @var string
     */
    public $Telephone;

    /**
     *
     * @var string
     */
    public $Fax;

    /**
     *
     * @var string
     */
    public $Email;
}