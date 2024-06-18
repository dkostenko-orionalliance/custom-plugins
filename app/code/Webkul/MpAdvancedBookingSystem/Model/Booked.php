<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpAdvancedBookingSystem
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpAdvancedBookingSystem\Model;

use Webkul\MpAdvancedBookingSystem\Api\Data\BookedInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Booked extends \Magento\Framework\Model\AbstractModel implements BookedInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    public const NOROUTE_ENTITY_ID = 'no-route';

    /**
     * MpAdvancedBookingSystem booked cache tag.
     */
    public const CACHE_TAG = 'mpadvancedbookingsystem_booked';

    /**
     * @var string
     */
    protected $_cacheTag = 'mpadvancedbookingsystem_booked';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'mpadvancedbookingsystem_booked';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\Webkul\MpAdvancedBookingSystem\Model\ResourceModel\Booked::class);
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
            return $this->noRoutePreorder();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Items.
     *
     * @return \Webkul\MpAdvancedBookingSystem\Model\Booked
     */
    public function noRouteItems()
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
     * @return \Webkul\MpAdvancedBookingSystem\Api\Data\BookedInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}