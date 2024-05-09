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
use OrionAlliance\NewModule\Api\Data\SaleslistInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Marketplace Saleslist Model.
 *
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Saleslist _getResource()
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Saleslist getResource()
 */
class Saleslist extends AbstractModel implements SaleslistInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    public const NOROUTE_ENTITY_ID = 'no-route';

    /**
     * Paid Order status.
     */
    public const PAID_STATUS_PENDING = '0';
    public const PAID_STATUS_COMPLETE = '1';
    public const PAID_STATUS_HOLD = '2';
    public const PAID_STATUS_REFUNDED = '3';
    public const PAID_STATUS_CANCELED = '4';

    /**
     * Marketplace Saleslist cache tag.
     */
    public const CACHE_TAG = 'marketplace_saleslist';

    /**
     * @var string
     */
    protected $_cacheTag = 'marketplace_saleslist';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'marketplace_saleslist';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \OrionAlliance\NewModule\Model\ResourceModel\Saleslist::class
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
            return $this->noRouteSaleslist();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Saleslist.
     *
     * @return \OrionAlliance\NewModule\Model\Saleslist
     */
    public function noRouteSaleslist()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
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
     * @return \OrionAlliance\NewModule\Api\Data\SaleslistInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
