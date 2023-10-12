<?php
namespace Product\ShipmentRestriction\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;

class ProductManufacturerList implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    protected $_productCollectionFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $storeCode = $args['storeCode'];

        $productCollection = $this->_productCollectionFactory->create();
        $productCollection->addAttributeToSelect('manufacturer');
        $productCollection->addStoreFilter($storeCode);

        $manufacturers = [];

        foreach ($productCollection as $product) {
            $manufacturer = $product->getAttributeText('manufacturer');
            if (!empty($manufacturer) && !in_array($manufacturer, $manufacturers)) {
                $manufacturers[] = $manufacturer;
            }
        }

        return $manufacturers;
    }
}
