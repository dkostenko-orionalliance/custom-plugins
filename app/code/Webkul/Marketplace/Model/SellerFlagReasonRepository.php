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

use Magento\Framework\Api\SortOrder;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\NoSuchEntityException;
use OrionAlliance\NewModule\Model\ResourceModel\SellerFlagReason as ResourceSellerFlagReason;
use Magento\Framework\Exception\CouldNotSaveException;
use OrionAlliance\NewModule\Api\Data\SellerFlagReasonSearchResultsInterfaceFactory;
use OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use OrionAlliance\NewModule\Api\SellerFlagReasonRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use OrionAlliance\NewModule\Model\ResourceModel\SellerFlagReason\CollectionFactory as SellerFlagReasonCollectionFactory;

class SellerFlagReasonRepository implements SellerFlagReasonRepositoryInterface
{

    /**
     * @var ResourceSellerFlagReason
     */
    protected $resource;

    /**
     * @var SellerFlagReasonCollectionFactory
     */
    protected $sellerFlagReasonCollectionFactory;

    /**
     * @var SellerFlagReasonInterfaceFactory
     */
    protected $dataSellerFlagReasonFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var SellerFlagReasonFactory
     */
    protected $sellerFlagReasonFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var SellerFlagReasonSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @param ResourceSellerFlagReason $resource
     * @param SellerFlagReasonFactory $sellerFlagReasonFactory
     * @param SellerFlagReasonInterfaceFactory $dataSellerFlagReasonFactory
     * @param SellerFlagReasonCollectionFactory $sellerFlagReasonCollectionFactory
     * @param SellerFlagReasonSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceSellerFlagReason $resource,
        SellerFlagReasonFactory $sellerFlagReasonFactory,
        SellerFlagReasonInterfaceFactory $dataSellerFlagReasonFactory,
        SellerFlagReasonCollectionFactory $sellerFlagReasonCollectionFactory,
        SellerFlagReasonSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->sellerFlagReasonFactory = $sellerFlagReasonFactory;
        $this->sellerFlagReasonCollectionFactory = $sellerFlagReasonCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSellerFlagReasonFactory = $dataSellerFlagReasonFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save
     *
     * @param \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface $sellerFlagReason
     */
    public function save(
        \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface $sellerFlagReason
    ) {
        try {
            $this->resource->save($sellerFlagReason);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the sellerFlagReason: %1',
                $exception->getMessage()
            ));
        }
        return $sellerFlagReason;
    }

   /**
    * Get data by id
    *
    * @param int $sellerFlagReasonId
    * @return void
    */
    public function getById($sellerFlagReasonId)
    {
        $sellerFlagReason = $this->sellerFlagReasonFactory->create();
        $this->resource->load($sellerFlagReason, $sellerFlagReasonId);
        if (!$sellerFlagReason->getId()) {
            throw new NoSuchEntityException(__('Seller Flag Reason with id "%1" does not exist.', $sellerFlagReasonId));
        }
        return $sellerFlagReason;
    }

    /**
     * Get by list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->sellerFlagReasonCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $fields[] = $filter->getField();
                $condition = $filter->getConditionType() ?: 'eq';
                $conditions[] = [$condition => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }

        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * Delete records
     *
     * @param \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface $sellerFlagReason
     */
    public function delete(
        \OrionAlliance\NewModule\Api\Data\SellerFlagReasonInterface $sellerFlagReason
    ) {
        try {
            $this->resource->delete($sellerFlagReason);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the SellerFlagReason: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete record byid
     *
     * @param int $sellerFlagReasonId
     */
    public function deleteById($sellerFlagReasonId)
    {
        return $this->delete($this->getById($sellerFlagReasonId));
    }
}
