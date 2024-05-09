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

namespace OrionAlliance\NewModule\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SellerFlagReasonRepositoryInterface
{
    /**
     * Save SellerFlagReason
     *
     * @param \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface $sellerFlagReason
     * @return \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface $sellerFlagReason
    );

    /**
     * Retrieve SellerFlagReason
     *
     * @param int $entityId
     * @return \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve SellerFlagReason matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \OrionAlliance\NewModule\Api\Data\SellerFlagReasonSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete SellerFlagReason
     *
     * @param \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface $sellerFlagReason
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface $sellerFlagReason
    );

    /**
     * Delete SellerFlagReason by ID
     *
     * @param string $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
