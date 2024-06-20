<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Marketplace
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\Marketplace\Model;

use Webkul\Marketplace\Api\SellerRepositoryInterface;
use Webkul\Marketplace\Api\Data\SellerInterface;
use Webkul\Marketplace\Api\Data\SellerInterfaceFactory;
use Webkul\Marketplace\Model\ResourceModel\Seller as SellerResource;
use Webkul\Marketplace\Api\Data\BecomeSellerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;

class SellerRepository implements SellerRepositoryInterface
{
    protected $sellerFactory;
    protected $resource;

    public function __construct(
        SellerResource $resource,
        SellerInterfaceFactory $sellerFactory
    ) {
        $this->resource = $resource;
        $this->sellerFactory = $sellerFactory;
    }

    public function save(SellerInterface $seller, $passwordHash = null)
    {
        try {
            $this->resource->save($seller);
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }
        return $seller;
    }

    public function get($email, $websiteId = null)
    {
        // Implement get logic if needed
    }

    public function getById($customerId)
    {
        $seller = $this->sellerFactory->create();
        $this->resource->load($seller, $customerId, SellerInterface::CUSTOMER_ID);
        if (!$seller->getId()) {
            throw new NoSuchEntityException(__('Seller with customer id "%1" does not exist.', $customerId));
        }
        return $seller;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        // Implement getList logic if needed
    }

    public function delete(SellerInterface $seller)
    {
        try {
            $this->resource->delete($seller);
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }
        return true;
    }

    public function deleteById($customerId)
    {
        $seller = $this->getById($customerId);
        return $this->delete($seller);
    }


    public function becomeSeller(BecomeSellerInterface $customerData)
    {
        $customerId = $customerData->getCustomerId();
        $customer = $this->sellerFactory->create();
        $this->resource->load($customer, $customerId, BecomeSellerInterface::CUSTOMER_ID);

        if (!$customer->getId()) {
            throw new NoSuchEntityException(__('Customer with ID %1 does not exist.', $customerId));
        }

        $customer->setData('is_seller', 1); // Assuming 'is_seller' is a flag you use
        $customer->setData('shop_title', $customerData->getShopTitle());
        $customer->setData('contact_number', $customerData->getContactNumber());
        $customer->setData('country', $customerData->getCountry());
        $customer->setData('state', $customerData->getState());
        $customer->setData('email', $customerData->getEmail());
        $customer->setData('firstname', $customerData->getFirstname());
        $customer->setData('lastname', $customerData->getLastname());

        try {
            $this->resource->save($customer);
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }

        return $customer;
    }
}
