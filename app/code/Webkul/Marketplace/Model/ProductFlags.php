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

namespace OrionAlliance\NewModule\Model;

use OrionAlliance\NewModule\Api\Data\ProductFlagsInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class ProductFlags extends \Magento\Framework\Model\AbstractModel implements ProductFlagsInterface
{
    /**
     * Marketplace ProductFlags cache tag.
     */
    public const CACHE_TAG = 'marketplace_productflags';

    /**
     * @var string
     */
    protected $_cacheTag = 'marketplace_productflags';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'marketplace_productflags';

    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\OrionAlliance\NewModule\Model\ResourceModel\ProductFlags::class);
    }

    /**
     * Get Entity Id
     *
     * @return string
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set Entity Id
     *
     * @param string $entityId
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagsInterface
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get Product Id
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Set Product Id
     *
     * @param string $productId
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagsInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->getData(self::REASON);
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagsInterface
     */
    public function setReason($reason)
    {
        return $this->setData(self::REASON, $reason);
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagsInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get Email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Set Email
     *
     * @param string $email
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagsInterface
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Get CreatedAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set CreatedAt
     *
     * @param string $timestamp
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagsInterface
     */
    public function setCreatedAt($timestamp)
    {
        return $this->setData(self::CREATED_AT, $timestamp);
    }
}
