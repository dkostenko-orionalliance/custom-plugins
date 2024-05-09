<?php
/**
 * Webkul Software
 *
 * @category Webkul
 * @package OrionAlliance_NewModule
 * @author Webkul
 * @copyright Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */

namespace OrionAlliance\NewModule\Model\ResourceModel\SellerFlagReason;

use OrionAlliance\NewModule\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \OrionAlliance\NewModule\Model\SellerFlagReason::class,
            \OrionAlliance\NewModule\Model\ResourceModel\SellerFlagReason::class
        );
    }
}
