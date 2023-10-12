<?php
namespace Product\ShipmentRestriction\Plugin;

use Magento\Checkout\Model\ShippingInformationManagement as BaseShippingInformationManagement;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Framework\Exception\InputException;
use Magento\Catalog\Api\ProductRepositoryInterface;

class ShippingInformationManagementPlugin {

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function afterSaveAddressInformation(
        BaseShippingInformationManagement $subject,
                                          $result,
                                          $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $quote = $this->cartRepository->get($cartId);

        $quoteAddress = $quote->getShippingAddress()->getCountryId();

        $quoteItems = $quote->getAllItems();
        foreach ($quoteItems as $item){
            $productId = $item->getProductId();
            $product = $this->productRepository->getById($productId);
            if (strpos($product->getData('non_eligible_countries'),$quoteAddress) !== false) {
                throw new InputException(
                    __('%1 is not eligible for shipment in this country', $product->getName())
                );
            }

        }
        return $result;
    }

}
