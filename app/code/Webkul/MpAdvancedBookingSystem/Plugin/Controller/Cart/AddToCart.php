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
namespace Webkul\MpAdvancedBookingSystem\Plugin\Controller\Cart;

use Magento\Framework\App\RequestInterface;

class AddToCart
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    private $response;

    /**
     * @var \Webkul\MpAdvancedBookingSystem\Helper\Data
     */
    private $helper;

    /**
     * @param RequestInterface $request
     * @param \Webkul\MpAdvancedBookingSystem\Helper\Data $helper
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(
        RequestInterface $request,
        \Webkul\MpAdvancedBookingSystem\Helper\Data $helper,
        \Magento\Framework\App\ResponseInterface $response
    ) {
        $this->request = $request;
        $this->helper = $helper;
        $this->response = $response;
    }
    
    /**
     * After Execute
     *
     * @param \Magento\Checkout\Controller\Cart\Configure $subject
     * @param mixed $result
     */
    public function afterExecute(\Magento\Checkout\Controller\Cart\Add $subject, $result)
    {
        try {
            if ($this->request->isAjax()) {
                $params = $this->request->getParams();
                if (isset($params['hotel_qty'])) {
                    $this->response->representJson(
                        json_encode([])
                    );
                    return $this->response;
                }
                return $result;
            }
        } catch (\Exception $e) {
            $this->helper->logDataInLogger(
                "Plugin_Controller_Cart_Add afterExecute : ".$e->getMessage()
            );
        }
        return $result;
    }
}
