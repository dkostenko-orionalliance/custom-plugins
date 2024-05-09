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
namespace OrionAlliance\NewModule\Model\Plugin\Order;

/**
 * Marketplace Order PDF InvoicePdfHeader Plugin
 */
class InvoicePdfHeader
{
    /**
     * @var \Magento\Sales\Model\Order\InvoiceFactory
     */
    protected $invoiceModel;

    /**
     * @var OrionAlliance\NewModule\Helper\Data
     */
    protected $helper;

    /**
     * Construct
     *
     * @param \Magento\Sales\Model\Order\InvoiceFactory $invoiceModel
     * @param \OrionAlliance\NewModule\Helper\Data $helper
     */
    public function __construct(
        \Magento\Sales\Model\Order\InvoiceFactory $invoiceModel,
        \OrionAlliance\NewModule\Helper\Data $helper
    ) {
        $this->invoiceModel = $invoiceModel;
        $this->helper = $helper;
    }
    /**
     * Insert title and number for concrete document type
     *
     * @param \OrionAlliance\NewModule\Model\Order\Pdf\Invoice $pdfInvoice
     * @param \Zend_Pdf_Page $page
     * @param string $text
     * @return void
     */
    public function beforeInsertDocumentNumber(\OrionAlliance\NewModule\Model\Order\Pdf\Invoice $pdfInvoice, $page, $text)
    {
        $invoiceArr = explode(__('Invoice # '), $text);
        $invoiceIncrementedId = $invoiceArr[1];
        $invoice = $this->invoiceModel->create()->loadByIncrementId($invoiceIncrementedId);
        $payment = $invoice->getOrder()->getPayment();
        if (!empty($payment->getMethodInstance())) {
            $method = $payment->getMethodInstance();
            $paymentInfo = $method->getTitle();
        } else {
            $paymentData = $invoice->getOrder()->getPayment()->getData();
            if (!empty($paymentData['additional_information']['method_title'])) {
                $paymentInfo = $paymentData['additional_information']['method_title'];
            } else {
                $paymentInfo = $paymentData['method'];
            }
        }
        /* Payment */
        $yPayments = $pdfInvoice->y + 65;
        $helper = $this->helper;
        if ($helper->getSellerProfileDisplayFlag()) {
            if (!$invoice->getOrder()->getIsVirtual()) {
                $paymentLeft = 35;
            } else {
                $yPayments = $yPayments +15;
                $paymentLeft = 285;
            }
        } else {
            $paymentLeft = 35;
        }
        foreach ($pdfInvoice->getString()->split($paymentInfo, 45, true, true) as $_value) {
            $page->drawText(strip_tags(trim($_value)), $paymentLeft, $yPayments, 'UTF-8');
            $yPayments -= 15;
        }
    }
}
