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

namespace OrionAlliance\NewModule\Model;

use Magento\Framework\Model\AbstractModel;
use OrionAlliance\NewModule\Api\Data\ProductInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Marketplace Product Model.
 *
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Product _getResource()
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Product getResource()
 */
class Product extends AbstractModel implements ProductInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    public const NOROUTE_ENTITY_ID = 'no-route';

    /**#@+
     * Product's Statuses
     */
    public const STATUS_PENDING = 0;
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 2;
    public const STATUS_DENIED = 3;
    /**#@-*/

    /**
     * Marketplace Product cache tag.
     */
    public const CACHE_TAG = 'marketplace_product';

    /**
     * @var string
     */
    protected $_cacheTag = 'marketplace_product';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'marketplace_product';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \OrionAlliance\NewModule\Model\ResourceModel\Product::class
        );
    }

    /**
     * Load object data.
     *
     * @param int|null $id
     * @param string   $field
     *
     * @return $this
     */
    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRouteProduct();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Product.
     *
     * @return \OrionAlliance\NewModule\Model\Product
     */
    public function noRouteProduct()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Prepare product's statuses.Available event marketplace_product_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_ENABLED => __('Approved'),
            self::STATUS_DISABLED => __('Disapproved'),
            self::STATUS_DENIED => __('Denied'),
        ];
    }

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * Get ID.
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \OrionAlliance\NewModule\Api\Data\ProductInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
