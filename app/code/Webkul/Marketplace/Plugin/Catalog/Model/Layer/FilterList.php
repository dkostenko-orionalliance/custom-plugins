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
namespace OrionAlliance\NewModule\Plugin\Catalog\Model\Layer;

class FilterList
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \OrionAlliance\NewModule\Helper\Data
     */
    protected $_mpHelper;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * Construct
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \OrionAlliance\NewModule\Helper\Data $mpHelper
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \OrionAlliance\NewModule\Helper\Data $mpHelper,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->_objectManager = $objectManager;
        $this->_mpHelper = $mpHelper;
        $this->request = $request;
    }

    /**
     * AroundGetFilters Plugin
     *
     * @param \Magento\Catalog\Model\Layer\FilterList $subject
     * @param \Closure $proceed
     * @param \Magento\Catalog\Model\Layer $layer
     * @return array
     */
    public function aroundGetFilters(
        \Magento\Catalog\Model\Layer\FilterList $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Layer $layer
    ) {
        $result = $proceed($layer);
        if ($this->_mpHelper->allowSellerFilter() &&
            $this->request->getFullActionName() != 'marketplace_seller_collection' &&
            $this->request->getFullActionName() != 'marketplace_seller_profile'
        ) {
            $result[] = $this->_objectManager->create(
                \OrionAlliance\NewModule\Model\Layer\Filter\Seller::class,
                ['layer' => $layer]
            );
        }

        return $result;
    }
}
