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
use OrionAlliance\NewModule\Api\Data\FeedbackcountInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Marketplace Feedbackcount Model.
 *
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Feedbackcount _getResource()
 * @method \OrionAlliance\NewModule\Model\ResourceModel\Feedbackcount getResource()
 */
class Feedbackcount extends AbstractModel implements FeedbackcountInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    public const NOROUTE_ENTITY_ID = 'no-route';

    /**#@-*/

    /**
     * Marketplace Feedbackcount cache tag.
     */
    public const CACHE_TAG = 'marketplace_feedbackcount';

    /**
     * @var string
     */
    protected $_cacheTag = 'marketplace_feedbackcount';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'marketplace_feedbackcount';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\OrionAlliance\NewModule\Model\ResourceModel\Feedbackcount::class);
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
            return $this->noRouteFeedbackcount();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Feedbackcount.
     *
     * @return \OrionAlliance\NewModule\Model\Feedbackcount
     */
    public function noRouteFeedbackcount()
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
     * @return \OrionAlliance\NewModule\Api\Data\FeedbackcountInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
