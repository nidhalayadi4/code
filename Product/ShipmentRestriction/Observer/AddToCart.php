<?php
namespace Product\ShipmentRestriction\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use \Product\ShipmentRestriction\Helper\Data;

class AddToCart implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var Data
     */

    protected $helper;


    public function __construct(
        ManagerInterface $messageManager,
        Data $helper
    ) {
        $this->messageManager = $messageManager;
        $this->helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product->getData('non_eligible_countries')){
            $countryNameList = array();
            $countryList = explode(",",$product->getData('non_eligible_countries'));
            foreach ($countryList as $country){
                $countryNameList[] = $this->helper->getCountryName($country);
            }
            $this->messageManager->addNoticeMessage($product->getData('name').' cannot be delivered in those countries: '
                .implode(",",$countryNameList));
        }

    }
}
