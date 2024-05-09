<?php
/**
 * Webkul Software.
 *
 * @category Webkul
 * @package OrionAlliance_NewModule
 * @author Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */

namespace OrionAlliance\NewModule\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use OrionAlliance\NewModule\Api\Data\FeedbackSearchResultInterfaceFactory as SearchResultFactory;

/**
 * Marketplace FeedbackRepository Class
 */
class FeedbackRepository implements \OrionAlliance\NewModule\Api\FeedbackRepositoryInterface
{
    /**
     * @var \OrionAlliance\NewModule\Model\SaleslistFactory
     */
    protected $modelFactory;

    /**
     * @var \OrionAlliance\NewModule\Model\ResourceModel\Saleslist\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var SearchResultFactory
     */
    protected $searchResultFactory;
    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * FeedbackRepository constructor.
     *
     * @param \OrionAlliance\NewModule\Model\FeedbackFactory $modelFactory
     * @param \OrionAlliance\NewModule\Model\ResourceModel\Feedback\CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultFactory $searchResultFactory
     */
    public function __construct(
        \OrionAlliance\NewModule\Model\FeedbackFactory $modelFactory,
        \OrionAlliance\NewModule\Model\ResourceModel\Feedback\CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultFactory $searchResultFactory
    ) {
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * Get by id
     *
     * @param int $id
     * @return \OrionAlliance\NewModule\Model\Feedback
     */
    public function getById($id)
    {
        $model = $this->modelFactory->create()->load($id);
        if (!$model->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The record with the "%1" ID doesn\'t exist.', $id)
            );
        }
        return $model;
    }

    /**
     * Save record
     *
     * @param \OrionAlliance\NewModule\Model\Feedback $subject
     * @return \OrionAlliance\NewModule\Model\Feedback
     */
    public function save(\OrionAlliance\NewModule\Model\Feedback $subject)
    {
        try {
            $subject->save();
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($exception->getMessage()));
        }
        return $subject;
    }

    /**
     * Get list
     *
     * @param Magento\Framework\Api\SearchCriteriaInterface $creteria
     * @return Magento\Framework\Api\SearchResults
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $creteria)
    {
        /** @var \OrionAlliance\NewModule\Model\ResourceModel\Saleslist\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($creteria, $collection);

        $collection->load();

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($creteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * Delete record
     *
     * @param \OrionAlliance\NewModule\Model\Feedback $subject
     * @return boolean
     */
    public function delete(\OrionAlliance\NewModule\Model\Feedback $subject)
    {
        try {
            $subject->delete();
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete record by id
     *
     * @param int $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
