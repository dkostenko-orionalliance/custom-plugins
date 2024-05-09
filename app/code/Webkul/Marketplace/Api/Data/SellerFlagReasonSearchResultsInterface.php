<?php
/**
 * Webkul Software
 *
 * @category Webkul
 * @package OrionAlliance_NewModule
 * @author Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */

namespace OrionAlliance\NewModule\Api\Data;

interface SellerFlagReasonSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get SellerFlag list.
     *
     * @return \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface[]
     */
    public function getItems();

    /**
     * Set SellerFlag list.
     *
     * @param \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
