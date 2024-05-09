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

interface SellerFlagsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get SellerFlags list.
     *
     * @return \OrionAlliance\NewModule\Api\Data\SellerFlagsInterface[]
     */
    public function getItems();

    /**
     * Set SellerFlags list.
     *
     * @param \OrionAlliance\NewModule\Api\Data\SellerFlagsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
