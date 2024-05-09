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
namespace OrionAlliance\NewModule\Plugin;

class ElasticsearchFilterPlugin
{
    /**
     *
     * @var \OrionAlliance\NewModule\Model\SellerIdDataMapper $sellerIdDataMapper
     */
    protected $sellerIdDataMapper;

    /**
     * Initialization
     *
     * @param \OrionAlliance\NewModule\Model\SellerIdDataMapper $sellerIdDataMapper
     */
    public function __construct(
        \OrionAlliance\NewModule\Model\SellerIdDataMapper $sellerIdDataMapper
    ) {
        $this->sellerIdDataMapper = $sellerIdDataMapper;
    }

    /**
     * Add seller id mapper
     *
     * @param mixed $subject
     * @param array $documents
     * @param int   $storeId
     * @param int   $mappedIndexerId
     *
     * @return array
     */
    public function beforeAddDocs($subject, array $documents, $storeId, $mappedIndexerId)
    {
        $documents = $this->sellerIdDataMapper->map($documents, $storeId, $mappedIndexerId);

        return [$documents, $storeId, $mappedIndexerId];
    }
}
