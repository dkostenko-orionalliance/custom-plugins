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
use OrionAlliance\NewModule\Api\Data\SellerInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Marketplace Seller Model.
 *
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Seller _getResource()
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Seller getResource()
 */
class Seller extends AbstractModel implements SellerInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    public const NOROUTE_ENTITY_ID = 'no-route';

    /**#@+
     * Seller's Statuses
     */
    public const STATUS_ENABLED    = 1;
    public const STATUS_PENDING    = 0;
    public const STATUS_PROCESSING = 2;
    public const STATUS_DISABLED   = 3;
    public const STATUS_DENIED   = 4;
    /**#@-*/

    /**
     * Marketplace Seller cache tag.
     */
    public const CACHE_TAG = 'marketplace_seller';

    /**
     * @var string
     */
    protected $_cacheTag = 'marketplace_seller';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'marketplace_seller';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \OrionAlliance\NewModule\Model\ResourceModel\Seller::class
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
            return $this->noRouteSeller();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Seller.
     *
     * @return \OrionAlliance\NewModule\Model\Seller
     */
    public function noRouteSeller()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Prepare seller's statuses. Available event marketplace_seller_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
          self::STATUS_ENABLED => __('Approved'),
          self::STATUS_PENDING => __('Pending'),
          self::STATUS_PROCESSING => __('Processing'),
          self::STATUS_DISABLED  => __('Disapproved'),
          self::STATUS_DENIED => __('Denied')
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
     * @return \OrionAlliance\NewModule\Api\Data\SellerInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
