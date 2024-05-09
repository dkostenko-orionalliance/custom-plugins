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

interface ProductFlagsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get ProductFlags list.
     *
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagsInterface[]
     */
    public function getItems();

    /**
     * Set ProductFlags list.
     *
     * @param \OrionAlliance\NewModule\Api\Data\ProductFlagsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
