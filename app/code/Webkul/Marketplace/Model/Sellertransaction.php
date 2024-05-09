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
use OrionAlliance\NewModule\Api\Data\SellertransactionInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Marketplace Sellertransaction Model.
 *
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Sellertransaction _getResource()
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Sellertransaction getResource()
 */
class Sellertransaction extends AbstractModel implements SellertransactionInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    public const NOROUTE_ENTITY_ID = 'no-route';

    /**
     * Marketplace Sellertransaction cache tag.
     */
    public const CACHE_TAG = 'marketplace_sellertransaction';

    /**
     * @var string
     */
    protected $_cacheTag = 'marketplace_sellertransaction';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'marketplace_sellertransaction';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \OrionAlliance\NewModule\Model\ResourceModel\Sellertransaction::class
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
            return $this->noRouteSellertransaction();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Sellertransaction.
     *
     * @return \OrionAlliance\NewModule\Model\Sellertransaction
     */
    public function noRouteSellertransaction()
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
     * @return \OrionAlliance\NewModule\Api\Data\SellertransactionInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
