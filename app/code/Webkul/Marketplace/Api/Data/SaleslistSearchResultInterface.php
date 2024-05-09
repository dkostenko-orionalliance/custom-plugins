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

interface SaleslistSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get items.
     *
     * @return \OrionAlliance\NewModule\Api\Data\SaleslistInterface[] Array of collection items.
     */
    public function getItems();

    /**
     * Set items.
     *
     * @param \OrionAlliance\NewModule\Api\Data\SaleslistInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);
}
