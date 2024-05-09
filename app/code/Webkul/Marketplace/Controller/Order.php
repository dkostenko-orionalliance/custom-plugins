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

namespace OrionAlliance\NewModule\Controller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Email\Sender\ShipmentSender;
use Magento\Sales\Model\Order\ShipmentFactory;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\Order\Email\Sender\CreditmemoSender;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;
use OrionAlliance\NewModule\Helper\Notification as NotificationHelper;
use OrionAlliance\NewModule\Model\Notification;
use OrionAlliance\NewModule\Helper\Data as HelperData;
use OrionAlliance\NewModule\Model\SaleslistFactory;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use OrionAlliance\NewModule\Model\OrdersFactory as MpOrdersModel;
use Magento\Sales\Model\ResourceModel\Order\Invoice\Collection as InvoiceCollection;
use OrionAlliance\NewModule\Model\SellerFactory as MpSellerModel;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;

abstract class Order extends Action
{
    /**
     * @var InvoiceSender
     */
    protected $_invoiceSender;

    /**
     * @var ShipmentSender
     */
    protected $_shipmentSender;

    /**
     * @var ShipmentFactory
     */
    protected $_shipmentFactory;

    /**
     * @var Shipment
     */
    protected $_shipment;

    /**
     * @var CreditmemoSender
     */
    protected $_creditmemoSender;

    /**
     * @var CreditmemoRepositoryInterface;
     */
    protected $_creditmemoRepository;

    /**
     * @var CreditmemoFactory;
     */
    protected $_creditmemoFactory;

    /**
     * @var \Magento\Sales\Api\InvoiceRepositoryInterface
     */
    protected $_invoiceRepository;

    /**
     * @var StockConfigurationInterface
     */
    protected $_stockConfiguration;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var OrderRepositoryInterface
     */
    protected $_orderRepository;

    /**
     * @var OrderManagementInterface
     */
    protected $_orderManagement;

    /**
     * @var \OrionAlliance\NewModule\Helper\Orders
     */
    protected $orderHelper;

    /**
     * @var NotificationHelper
     */
    protected $notificationHelper;

    /**
     * @var HelperData
     */
    protected $helper;

    /**
     * @var \Magento\Sales\Api\CreditmemoManagementInterface
     */
    protected $creditmemoManagement;

    /**
     * @var SaleslistFactory
     */
    protected $saleslistFactory;

    /**
     * @var CustomerUrl
     */
    protected $customerUrl;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var \OrionAlliance\NewModule\Model\Order\Pdf\Creditmemo
     */
    protected $creditmemoPdf;

    /**
     * @var \OrionAlliance\NewModule\Model\Order\Pdf\Invoice
     */
    protected $invoicePdf;

    /**
     * @var MpOrdersModel
     */
    protected $mpOrdersModel;

    /**
     * @var InvoiceCollection
     */
    protected $invoiceCollection;

    /**
     * @var \Magento\Sales\Api\InvoiceManagementInterface
     */
    protected $invoiceManagement;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productModel;

    /**
     * @var MpSellerModel
     */
    protected $mpSellerModel;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var InvoiceService
     */
    protected $invoiceService;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @param Context                                          $context
     * @param PageFactory                                      $resultPageFactory
     * @param InvoiceSender                                    $invoiceSender
     * @param ShipmentSender                                   $shipmentSender
     * @param ShipmentFactory                                  $shipmentFactory
     * @param Shipment                                         $shipment
     * @param CreditmemoSender                                 $creditmemoSender
     * @param CreditmemoRepositoryInterface                    $creditmemoRepository
     * @param CreditmemoFactory                                $creditmemoFactory
     * @param \Magento\Sales\Api\InvoiceRepositoryInterface    $invoiceRepository
     * @param StockConfigurationInterface                      $stockConfiguration
     * @param OrderRepositoryInterface                         $orderRepository
     * @param OrderManagementInterface                         $orderManagement
     * @param \Magento\Framework\Registry                      $coreRegistry
     * @param \Magento\Customer\Model\Session                  $customerSession
     * @param \OrionAlliance\NewModule\Helper\Orders                $orderHelper
     * @param NotificationHelper                               $notificationHelper
     * @param HelperData                                       $helper
     * @param \Magento\Sales\Api\CreditmemoManagementInterface $creditmemoManagement
     * @param SaleslistFactory                                 $saleslistFactory
     * @param CustomerUrl                                      $customerUrl
     * @param DateTime                                         $date
     * @param FileFactory                                      $fileFactory
     * @param \OrionAlliance\NewModule\Model\Order\Pdf\Creditmemo   $creditmemoPdf
     * @param \OrionAlliance\NewModule\Model\Order\Pdf\Invoice      $invoicePdf
     * @param MpOrdersModel                                    $mpOrdersModel
     * @param InvoiceCollection                                $invoiceCollection
     * @param \Magento\Sales\Api\InvoiceManagementInterface    $invoiceManagement
     * @param \Magento\Catalog\Model\ProductFactory            $productModel
     * @param MpSellerModel                                    $mpSellerModel
     * @param \Psr\Log\LoggerInterface                         $logger
     * @param InvoiceService                                   $invoiceService
     * @param JsonFactory                                      $resultJsonFactory
     * @param RawFactory                                       $resultRawFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        InvoiceSender $invoiceSender,
        ShipmentSender $shipmentSender,
        ShipmentFactory $shipmentFactory,
        Shipment $shipment,
        CreditmemoSender $creditmemoSender,
        CreditmemoRepositoryInterface $creditmemoRepository,
        CreditmemoFactory $creditmemoFactory,
        \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository,
        StockConfigurationInterface $stockConfiguration,
        OrderRepositoryInterface $orderRepository,
        OrderManagementInterface $orderManagement,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Customer\Model\Session $customerSession,
        \OrionAlliance\NewModule\Helper\Orders $orderHelper,
        NotificationHelper $notificationHelper,
        HelperData $helper,
        \Magento\Sales\Api\CreditmemoManagementInterface $creditmemoManagement,
        SaleslistFactory $saleslistFactory,
        CustomerUrl $customerUrl,
        DateTime $date,
        FileFactory $fileFactory,
        \OrionAlliance\NewModule\Model\Order\Pdf\Creditmemo $creditmemoPdf,
        \OrionAlliance\NewModule\Model\Order\Pdf\Invoice $invoicePdf,
        MpOrdersModel $mpOrdersModel,
        InvoiceCollection $invoiceCollection,
        \Magento\Sales\Api\InvoiceManagementInterface $invoiceManagement,
        \Magento\Catalog\Model\ProductFactory $productModel,
        MpSellerModel $mpSellerModel,
        \Psr\Log\LoggerInterface $logger,
        InvoiceService $invoiceService = null,
        JsonFactory $resultJsonFactory = null,
        RawFactory $resultRawFactory = null
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_invoiceSender = $invoiceSender;
        $this->_shipmentSender = $shipmentSender;
        $this->_shipmentFactory = $shipmentFactory;
        $this->_shipment = $shipment;
        $this->_creditmemoSender = $creditmemoSender;
        $this->_creditmemoRepository = $creditmemoRepository;
        $this->_creditmemoFactory = $creditmemoFactory;
        $this->_invoiceRepository = $invoiceRepository;
        $this->_stockConfiguration = $stockConfiguration;
        $this->_orderRepository = $orderRepository;
        $this->_orderManagement = $orderManagement;
        $this->_customerSession = $customerSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->orderHelper = $orderHelper;
        $this->notificationHelper = $notificationHelper;
        $this->helper = $helper;
        $this->creditmemoManagement = $creditmemoManagement;
        $this->saleslistFactory = $saleslistFactory;
        $this->customerUrl = $customerUrl;
        $this->date = $date;
        $this->fileFactory = $fileFactory;
        $this->creditmemoPdf = $creditmemoPdf;
        $this->invoicePdf = $invoicePdf;
        $this->mpOrdersModel = $mpOrdersModel;
        $this->invoiceCollection = $invoiceCollection;
        $this->invoiceManagement = $invoiceManagement;
        $this->productModel = $productModel;
        $this->mpSellerModel = $mpSellerModel;
        $this->logger = $logger;
        $this->invoiceService = $invoiceService ?: \Magento\Framework\App\ObjectManager::getInstance()->create(
            InvoiceService::class
        );
        $this->resultJsonFactory = $resultJsonFactory ?: \Magento\Framework\App\ObjectManager::getInstance()->create(
            JsonFactory::class
        );
        $this->resultRawFactory = $resultRawFactory ?: \Magento\Framework\App\ObjectManager::getInstance()->create(
            RawFactory::class
        );

        parent::__construct($context);
    }

    /**
     * Check customer authentication.
     *
     * @param RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $loginUrl = $this->customerUrl->getLoginUrl();

        if (!$this->_customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }

        return parent::dispatch($request);
    }

    /**
     * Initialize order model instance.
     *
     * @return \Magento\Sales\Api\Data\OrderInterface|false
     */
    protected function _initOrder()
    {
        $id = $this->getRequest()->getParam('id');
        try {
            $order = $this->_orderRepository->get($id);
            $tracking = $this->orderHelper->getOrderinfo($id);
            if ($tracking) {
                if ($tracking->getOrderId() == $id) {
                    if (!$id) {
                        $this->messageManager->addError(__('This order no longer exists.'));
                        $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                        return false;
                    }
                } else {
                    $this->messageManager->addError(__('You are not authorize to manage this order.'));
                    $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                    return false;
                }
            } else {
                $this->messageManager->addError(__('You are not authorize to manage this order.'));
                $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                return false;
            }
        } catch (NoSuchEntityException $e) {
            $this->helper->logDataInLogger(
                "Controller_Order execute : ".$e->getMessage()
            );
            $this->messageManager->addError(__('This order no longer exists.'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        } catch (InputException $e) {
            $this->helper->logDataInLogger(
                "Controller_Order execute : ".$e->getMessage()
            );
            $this->messageManager->addError(__('This order no longer exists.'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        }
        $this->_coreRegistry->register('sales_order', $order);
        $this->_coreRegistry->register('current_order', $order);

        return $order;
    }

    /**
     * Initialize invoice model instance.
     *
     * @return \Magento\Sales\Api\InvoiceRepositoryInterface|false
     */
    protected function _initInvoice()
    {
        $invoiceId = $this->getRequest()->getParam('invoice_id');
        $orderId = $this->getRequest()->getParam('order_id');
        if (!$invoiceId) {
            return false;
        }
        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
        $invoice = $this->_invoiceRepository->get($invoiceId);
        if (!$invoice) {
            return false;
        }
        try {
            $order = $this->_orderRepository->get($orderId);
            $tracking = $this->orderHelper->getOrderinfo($orderId);
            if ($tracking) {
                $invoiceIds = explode(',', $tracking->getInvoiceId());
                if (in_array($invoiceId, $invoiceIds)) {
                    if (!$invoiceId) {
                        $this->messageManager->addError(__('The invoice no longer exists.'));
                        $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                        return false;
                    }
                } else {
                    $this->messageManager->addError(__('You are not authorize to view this invoice.'));
                    $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                    return false;
                }
            } else {
                $this->messageManager->addError(__('You are not authorize to view this invoice.'));
                $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                return false;
            }
        } catch (NoSuchEntityException $e) {
            $this->helper->logDataInLogger(
                "Controller_Order execute : ".$e->getMessage()
            );
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        } catch (InputException $e) {
            $this->helper->logDataInLogger(
                "Controller_Order execute : ".$e->getMessage()
            );
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        }
        $this->_coreRegistry->register('sales_order', $order);
        $this->_coreRegistry->register('current_order', $order);
        $this->_coreRegistry->register('current_invoice', $invoice);

        return $invoice;
    }

    /**
     * Initialize shipment model instance.
     *
     * @return \Magento\Sales\Model\Order\Shipment|false
     */
    protected function _initShipment()
    {
        $shipmentId = $this->getRequest()->getParam('shipment_id');
        $orderId = $this->getRequest()->getParam('order_id');
        if (!$shipmentId) {
            return false;
        }
        /** @var \Magento\Sales\Model\Order\Shipment $shipment */
        $shipment = $this->_shipment->load($shipmentId);
        if (!$shipment) {
            return false;
        }
        try {
            $order = $this->_orderRepository->get($orderId);
            $tracking = $this->orderHelper->getOrderinfo($orderId);
            if ($tracking) {
                $shipmentIds = explode(',', $tracking->getShipmentId());
                if (in_array($shipmentId, $shipmentIds)) {
                    if (!$shipmentId) {
                        $this->messageManager->addError(__('The shipment no longer exists.'));
                        $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                        return false;
                    }
                } else {
                    $this->messageManager->addError(__('You are not authorize to view this shipment.'));
                    $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                    return false;
                }
            } else {
                $this->messageManager->addError(__('You are not authorize to view this shipment.'));
                $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                return false;
            }
        } catch (NoSuchEntityException $e) {
            $this->helper->logDataInLogger(
                "Controller_Order execute : ".$e->getMessage()
            );
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        } catch (InputException $e) {
            $this->helper->logDataInLogger(
                "Controller_Order execute : ".$e->getMessage()
            );
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        }
        $this->_coreRegistry->register('sales_order', $order);
        $this->_coreRegistry->register('current_order', $order);
        $this->_coreRegistry->register('current_shipment', $shipment);

        return $shipment;
    }

    /**
     * Initialize invoice model instance.
     *
     * @return \Magento\Sales\Api\InvoiceRepositoryInterface|false
     */
    protected function _initCreditmemo()
    {
        $creditmemo = false;
        $creditmemoId = $this->getRequest()->getParam('creditmemo_id');
        $orderId = $this->getRequest()->getParam('order_id');
        $order = $this->_orderRepository->get($orderId);

        $creditmemo = $this->_creditmemoRepository->get($creditmemoId);
        if (!$creditmemo) {
            return false;
        }
        try {
            $tracking = $this->orderHelper->getOrderinfo($orderId);
            if ($tracking) {
                $creditmemoArr = explode(',', $tracking->getCreditmemoId());
                if (in_array($creditmemoId, $creditmemoArr)) {
                    if (!$creditmemoId) {
                        $this->messageManager->addError(__('The creditmemo no longer exists.'));
                        $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                        return false;
                    }
                } else {
                    $this->messageManager->addError(__('You are not authorize to view this creditmemo.'));
                    $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                    return false;
                }
            } else {
                $this->messageManager->addError(__('You are not authorize to view this creditmemo.'));
                $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

                return false;
            }
        } catch (NoSuchEntityException $e) {
            $this->helper->logDataInLogger(
                "Controller_Order execute : ".$e->getMessage()
            );
            $this->messageManager->addError($e->getMessage());
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        } catch (InputException $e) {
            $this->helper->logDataInLogger(
                "Controller_Order execute : ".$e->getMessage()
            );
            $this->messageManager->addError($e->getMessage());
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        }
        $this->_coreRegistry->register('sales_order', $order);
        $this->_coreRegistry->register('current_order', $order);
        $this->_coreRegistry->register('current_creditmemo', $creditmemo);

        return $creditmemo;
    }

    /**
     * Gte merged array
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public function getMergedArray($array1, $array2)
    {
        return array_merge($array1, $array2);
    }
    /**
     * Get item quantities
     *
     * @param [array] $order
     * @param [array] $items
     * @return array
     */
    protected function _getItemQtys($order, $items)
    {
        $data = [];
        $subtotal = 0;
        $baseSubtotal = 0;
        foreach ($order->getAllItems() as $item) {
            if (in_array($item->getItemId(), $items)) {
                $data[$item->getItemId()] = (int)($item->getQtyOrdered() - ($item->getQtyInvoiced()
                + $item->getQtyCanceled()));

                $_item = $item;

                // for bundle product
                $bundleitems = $this->getMergedArray([$_item], $_item->getChildrenItems());

                if ($_item->getParentItem()) {
                    continue;
                }

                if ($_item->getProductType() == 'bundle') {
                    foreach ($bundleitems as $_bundleitem) {
                        if ($_bundleitem->getParentItem()) {
                            $data[$_bundleitem->getItemId()] = (int)(
                                $_bundleitem->getQtyOrdered() - ($_bundleitem->getQtyInvoiced()
                                 + $_bundleitem->getQtyCanceled())
                            );
                        }
                    }
                }
                $subtotal += $_item->getRowTotal();
                $baseSubtotal += $_item->getBaseRowTotal();
            } else {
                if (!$item->getParentItemId()) {
                    $data[$item->getItemId()] = 0;
                }
            }
        }

        return ['data' => $data,'subtotal' => $subtotal,'baseSubtotal' => $baseSubtotal];
    }

    /**
     * Get shipped item qty
     *
     * @param \Magento\Sales\Model\Order $order
     * @param array $items
     * @return array
     */
    protected function _getShippingItemQtys($order, $items)
    {
        $data = [];
        $subtotal = 0;
        $baseSubtotal = 0;
        foreach ($order->getAllItems() as $item) {
            $orderItemId = $item->getItemId();
            if (isset($items[$orderItemId])) {
                $availableQtyToShip = (int) ($item->getQtyOrdered() -
                    $item->getQtyShipped() -
                    $item->getQtyRefunded() -
                    $item->getQtyCanceled()
                );

                if ($items[$orderItemId] <= $availableQtyToShip) {
                    $data[$orderItemId] = $items[$orderItemId];
                } else {
                    $data[$orderItemId] = $availableQtyToShip;
                }

                $_item = $item;

                // for bundle product
                // $bundleitems = $this->getMergedArray([$_item], $_item->getChildrenItems());

                // if ($_item->getParentItem()) {
                //     continue;
                // }

                // if ($_item->getProductType() == 'bundle') {
                //     foreach ($bundleitems as $_bundleitem) {
                //         if ($_bundleitem->getParentItem()) {
                //             //$data[$_bundleitem->getItemId()] = (int)
                //                 //($_bundleitem->getQtyOrdered() - $item->getQtyShipped());
                //             $availableQtyToShip = (int) ($_bundleitem->getQtyOrdered() -
                //                 $item->getQtyShipped() -
                //                 $item->getQtyRefunded() -
                //                 $item->getQtyCanceled()
                //             );
            
                //             if ($items[$_bundleitem->getItemId()] <= $availableQtyToShip) {
                //                 $data[$_bundleitem->getItemId()] = $items[$_bundleitem->getItemId()];
                //             }
                //         }
                //     }
                // }
                $subtotal += $_item->getRowTotal();
                $baseSubtotal += $_item->getBaseRowTotal();
            } else {
                if (!$item->getParentItemId()) {
                    $data[$item->getItemId()] = 0;
                }
            }
        }

        return ['data' => $data,'subtotal' => $subtotal,'baseSubtotal' => $baseSubtotal];
    }

    /**
     * Is alll item invoiced
     *
     * @param \Magento\Sales\Model\Order $order
     * @return boolean
     */
    protected function isAllItemInvoiced($order)
    {
        $flag = 1;
        foreach ($order->getAllItems() as $item) {
            if ($item->getParentItem()) {
                continue;
            } elseif ($item->getProductType() == 'bundle') {
                // for bundle product
                $bundleitems = $this->getMergedArray([$item], $item->getChildrenItems());
                foreach ($bundleitems as $bundleitem) {
                    if ($bundleitem->getParentItem()) {
                        if ((int)($bundleitem->getQtyOrdered() - $item->getQtyInvoiced())) {
                            $flag = 0;
                        }
                    }
                }
            } else {
                if ((int)($item->getQtyOrdered() - $item->getQtyInvoiced())) {
                    $flag = 0;
                }
            }
        }

        return $flag;
    }

    /**
     * Updated notification, mark as read.
     */
    protected function _updateNotification()
    {
        $orderId = $this->_coreRegistry->registry('current_order')->getId();
        $orderData = $this->orderHelper->getOrderinfo($orderId);
        $type = Notification::TYPE_ORDER;
        $this->notificationHelper->updateNotification(
            $orderData,
            $type
        );
    }

    /**
     * Get Current Seller items to create invoice
     *
     * @param int $orderId
     * @param int $sellerId
     * @param string $paymentCode
     * @param array $invoiceItems
     * @return array
     */
    public function getCurrentSellerInvoiceItemsData($orderId, $sellerId, $paymentCode, $invoiceItems)
    {
        $items = [];
        foreach ($invoiceItems as $invoiceItemsId => $invoiceItemsQty) {
            if ($invoiceItemsQty) {
                array_push($items, $invoiceItemsId);
            }
        }
        // calculate charges for ordered items for current seller
        $codCharges = 0;
        $couponAmount = 0;
        $tax = 0;
        $currencyRate = 1;
        $sellerItemsToInvoice = [];
        $collection = $this->saleslistFactory->create()
                ->getCollection()
                ->addFieldToFilter(
                    'order_id',
                    ['eq' => $orderId]
                )
                ->addFieldToFilter(
                    'seller_id',
                    ['eq' => $sellerId]
                )
                ->addFieldToFilter(
                    'order_item_id',
                    ['in' => $items]
                );
        foreach ($collection as $saleproduct) {
            $orderItemId = $saleproduct->getOrderItemId();
            $orderedQty = $saleproduct->getMagequantity();
            $qtyToInvoice = $orderedQty;
            if (isset($invoiceItems[$orderItemId])) {
                $sellerItemsToInvoice[$orderItemId] = $invoiceItems[$orderItemId];
                $qtyToInvoice = $invoiceItems[$orderItemId];
            }
            $currencyRate = $saleproduct->getCurrencyRate();
            if ($paymentCode == 'mpcashondelivery') {
                $appliedCodCharges = $saleproduct->getCodCharges() / $orderedQty;
                $codCharges = $codCharges + ($appliedCodCharges * $qtyToInvoice);
            }
            $appliedTax = $saleproduct->getTotalTax() / $orderedQty;
            $tax = $tax + ($appliedTax * $qtyToInvoice);
            if ($saleproduct->getIsCoupon()) {
                $appliedAmount = $saleproduct->getAppliedCouponAmount() / $orderedQty;
                $couponAmount = $couponAmount + ($appliedAmount * $qtyToInvoice);
            }
        }

        // calculate shipment for the seller order if applied
        $shippingAmount = 0;
        $marketplaceOrder = $this->orderHelper->getOrderinfo($orderId);
        $savedInvoiceId = $marketplaceOrder->getInvoiceId();
        if (!$savedInvoiceId) {
            $trackingsdata = $this->mpOrdersModel->create()
            ->getCollection()
            ->addFieldToFilter(
                'order_id',
                $orderId
            )
            ->addFieldToFilter(
                'seller_id',
                $sellerId
            );
            foreach ($trackingsdata as $tracking) {
                $shippingAmount = $tracking->getShippingCharges();
            }
        }
        $data = [
            'items' => $sellerItemsToInvoice,
            'currencyRate' => $currencyRate,
            'codCharges' => $codCharges,
            'tax' => $tax,
            'couponAmount' => $couponAmount,
            'shippingAmount' => $shippingAmount
        ];
        return $data;
    }
}
