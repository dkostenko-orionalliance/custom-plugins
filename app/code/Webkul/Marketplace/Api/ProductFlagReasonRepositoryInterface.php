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

interface ProductFlagReasonRepositoryInterface
{
    /**
     * Save ProductFlag
     *
     * @param \OrionAlliance\NewModule\Api\Data\ProductFlagReasonInterface $productFlagReason
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagReasonInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \OrionAlliance\NewModule\Api\Data\ProductFlagReasonInterface $productFlagReason
    );

    /**
     * Retrieve ProductFlag
     *
     * @param int $entityId
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagReasonInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve ProductFlag matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \OrionAlliance\NewModule\Api\Data\ProductFlagSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ProductFlag
     *
     * @param \OrionAlliance\NewModule\Api\Data\ProductFlagReasonInterface $productFlagReason
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \OrionAlliance\NewModule\Api\Data\ProductFlagReasonInterface $productFlagReason
    );

    /**
     * Delete ProductFlag by ID
     *
     * @param string $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
