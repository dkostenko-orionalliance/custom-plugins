<?php
namespace Webkul\Marketplace\Model;

use Webkul\Marketplace\Api\BecomeSellerInterface;
use Webkul\Marketplace\Api\Data\BecomeSellerResponseInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\State;
use Webkul\Marketplace\Controller\Account\BecomesellerPost;
use Magento\Framework\Exception\LocalizedException;

class BecomeSeller implements BecomeSellerInterface
{
    /**
     * @var State
     */
    protected $appState;

    public function __construct(
        State $appState
    ) {
        $this->appState = $appState;
    }

    /**
     * Register a customer as a seller.
     *
     * @param string $profileUrl
     * @param string $country
     * @param string $number
     * @return BecomeSellerResponseInterface
     * @throws LocalizedException
     */
    public function execute($profileUrl, $country, $number)
    {
        try {
            // Set the area code if not already set
            if (!$this->appState->getAreaCode()) {
                $this->appState->setAreaCode('frontend');
            }

            // Create a new request object
            $request = ObjectManager::getInstance()->create(HttpRequest::class);
            $request->setParams([
                'profileurl' => $profileUrl,
                'country_pic' => $country,
                'contact_number' => $number
            ]);

            // Dispatch the controller
            $controller = ObjectManager::getInstance()->create(BecomesellerPost::class);
            $controller->dispatch($request);

            // Insert seller data into marketplace_userdata
            $customerSession = ObjectManager::getInstance()->get(\Magento\Customer\Model\Session::class);
            $customerId = $customerSession->getCustomerId();
            
            $userdata = ObjectManager::getInstance()->create(\Webkul\Marketplace\Model\Userdata::class);
            $userdata->setData([
                'seller_id' => $customerId,
                'shop_url' => $profileUrl,
                'country_pic' => $country,
                'contact_number' => $number,
                'is_seller' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'admin_notification' => 1
            ]);
            $userdata->save();

            // Prepare response
            $response = ObjectManager::getInstance()->create(BecomeSellerResponseInterface::class);
            $response->setMessage(__('Seller registration request has been processed.'));

            return $response;
        } catch (\Exception $e) {
            throw new LocalizedException(__('Error processing seller registration: %1', $e->getMessage()));
        }
    }
}
