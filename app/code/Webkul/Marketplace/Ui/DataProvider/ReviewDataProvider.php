<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   OrionAlliance_NewModule
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace OrionAlliance\NewModule\Ui\DataProvider;

use OrionAlliance\NewModule\Model\ResourceModel\Feedback\CollectionFactory;
use OrionAlliance\NewModule\Helper\Data as HelperData;

/**
 * Class Marketplace Ui DataProvider ReviewDataProvider
 */
class ReviewDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \OrionAlliance\NewModule\Model\ResourceModel\Feedback\Collection
     */
    protected $collection;

    /**
     * @var HelperData
     */
    public $helperData;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param HelperData $helperData
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        HelperData $helperData,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $sellerId = $helperData->getCustomerId();
        $collectionData = $collectionFactory->create()
        ->addFieldToFilter(
            'seller_id',
            $sellerId
        );
        $this->collection = $collectionData;
    }
}
