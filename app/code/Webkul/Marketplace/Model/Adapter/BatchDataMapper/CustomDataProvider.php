<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace OrionAlliance\NewModule\Model\Adapter\BatchDataMapper;
 
use Magento\AdvancedSearch\Model\Adapter\DataMapper\AdditionalFieldsProviderInterface;
 
/**
 * Provide data mapping for custom fields
 */
class CustomDataProvider implements AdditionalFieldsProviderInterface
{
    /**
     * @var \OrionAlliance\NewModule\Model\ResourceModel\Product\CollectionFactory
     */
    protected $mpProductCollectionFactory;
    /**
     * Construct
     *
     * @param \OrionAlliance\NewModule\Model\ResourceModel\Product\CollectionFactory $mpProductCollectionFactory
     */
    public function __construct(
        \OrionAlliance\NewModule\Model\ResourceModel\Product\CollectionFactory $mpProductCollectionFactory
    ) {
        $this->mpProductCollectionFactory = $mpProductCollectionFactory;
    }
 
    /**
     * @inheritdoc
     */
    public function getFields(array $productIds, $storeId)
    {
 
        $fields = [];
        
        foreach ($productIds as $productId) {
            $sellerProductCollection = $this->mpProductCollectionFactory->create()
            ->addFieldToFilter('mageproduct_id', $productId)->getFirstItem();
            
            if ($sellerProductCollection->getEntityId()) {
                $fields[$productId] = ["seller_id.keyword" => $sellerProductCollection->getSellerId()];
            }
           
        }
        return $fields;
    }
}
