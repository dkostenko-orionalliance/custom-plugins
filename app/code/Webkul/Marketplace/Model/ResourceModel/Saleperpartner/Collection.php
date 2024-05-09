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
namespace OrionAlliance\NewModule\Model\ResourceModel\Saleperpartner;

use \OrionAlliance\NewModule\Model\ResourceModel\AbstractCollection;

/**
 * Webkul Marketplace ResourceModel Saleperpartner collection
 */
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
            \OrionAlliance\NewModule\Model\Saleperpartner::class,
            \OrionAlliance\NewModule\Model\ResourceModel\Saleperpartner::class
        );
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    /**
     * Get total reminaing amount
     */
    public function getTotalAmountRemain()
    {
        $this->getSelect()
        ->columns('SUM(amount_remain) AS amount_remain')
        ->group('seller_id');
        return $this;
    }
}
