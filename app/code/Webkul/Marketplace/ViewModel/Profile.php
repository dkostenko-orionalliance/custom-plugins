<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   OrionAlliance_NewModule
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace OrionAlliance\NewModule\ViewModel;

use OrionAlliance\NewModule\Model\SaleslistFactory;
use OrionAlliance\NewModule\Helper\Data as MpHelper;

class Profile implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var SaleslistFactory
     */
    public $saleslistFactory;

    /**
     * @var MpHelper
     */
    public $mpHelpder;

    /**
     * @param SaleslistFactory $saleslistFactory
     * @param MpHelper $mpHelper
     */
    public function __construct(
        SaleslistFactory $saleslistFactory,
        MpHelper $mpHelper
    ) {
        $this->saleslistFactory = $saleslistFactory;
        $this->mpHelpder = $mpHelper;
    }

    /**
     * GetUserInfo function
     *
     * @param int $productId
     * @param int $orderId
     * @return int
     */
    public function getUserInfo($productId, $orderId)
    {
        $sellerId = 0;
        $marketplaceSalesCollection = $this->saleslistFactory->create()
        ->getCollection()
        ->addFieldToFilter(
            'mageproduct_id',
            ['eq' => $productId]
        )
        ->addFieldToFilter(
            'order_id',
            ['eq' => $orderId]
        );
        if (count($marketplaceSalesCollection)) {
            foreach ($marketplaceSalesCollection as $mpSales) {
                $sellerId = $mpSales->getSellerId();
            }
        }
        return $sellerId;
    }
    /**
     * GetSellerDetails function
     *
     * @param int $sellerId
     * @return mixed[]
     */
    public function getSellerDetails($sellerId)
    {
        $rowsocial = $this->mpHelpder->getSellerDataBySellerId($sellerId);
        $shopTitle = '';
        $shopUrl = '';
        foreach ($rowsocial as $value) {
            $shopTitle = $value['shop_title'];
            $shopUrl = $value['shop_url'];
            if (!$shopTitle) {
                $shopTitle = $value->getShopUrl();
            }
        }
        return [$shopTitle, $shopUrl];
    }
    /**
     * GetShopUrl function
     *
     * @param string $shopUrl
     * @return string
     */
    public function getShopUrl($shopUrl)
    {
        return $this->mpHelpder->getRewriteUrl(
            'marketplace/seller/profile/shop/'.$shopUrl
        );
    }
}
