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

interface ProductFlagReasonSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get ProductFlag list.
     *
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagReasonInterface[]
     */
    public function getItems();

    /**
     * Set ProductFlag list.
     *
     * @param \OrionAlliance\NewModule\Api\Data\ProductFlagReasonInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
