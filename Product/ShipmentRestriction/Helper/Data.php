<?php

namespace Product\ShipmentRestriction\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_countryFactory;

    public function __construct(
        \Magento\Directory\Model\CountryFactory $countryFactory
    ) {
        $this->_countryFactory = $countryFactory;
    }

    /**
     * Retrieves country name via country code
     *
     * @param string $countryCode
     * @return string
     */
    public function getCountryname(string $countryCode): string
    {
        $country = $this->_countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }
}
