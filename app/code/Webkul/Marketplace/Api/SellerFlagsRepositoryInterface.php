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

interface SellerFlagsRepositoryInterface
{
    /**
     * Save SellerFlags
     *
     * @param \OrionAlliance\NewModule\Api\Data\SellerFlagsInterface $sellerFlags
     * @return \OrionAlliance\NewModule\Api\Data\SellerFlagsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \OrionAlliance\NewModule\Api\Data\SellerFlagsInterface $sellerFlags
    );

    /**
     * Retrieve SellerFlags
     *
     * @param int $entityId
     * @return \OrionAlliance\NewModule\Api\Data\SellerFlagsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve SellerFlags matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \OrionAlliance\NewModule\Api\Data\SellerFlagsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete SellerFlags
     *
     * @param \OrionAlliance\NewModule\Api\Data\SellerFlagsInterface $sellerFlags
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \OrionAlliance\NewModule\Api\Data\SellerFlagsInterface $sellerFlags
    );

    /**
     * Delete SellerFlags by ID
     *
     * @param string $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
