<?php
namespace OrionAlliance\NewModule\Block;

use Magento\Customer\Model\Session;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Exception\LocalizedException;

class RegistrationForm extends Template
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var CustomerInterfaceFactory
     */
    protected $customerDataFactory;

    /**
     * @var FormKeyValidator
     */
    protected $formKeyValidator;

    /**
     * @var AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @param Template\Context $context
     * @param Session $customerSession
     * @param CustomerInterfaceFactory $customerDataFactory
     * @param FormKeyValidator $formKeyValidator
     * @param AccountManagementInterface $accountManagement
     * @param CustomerFactory $customerFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Session $customerSession,
        CustomerInterfaceFactory $customerDataFactory,
        FormKeyValidator $formKeyValidator,
        AccountManagementInterface $accountManagement,
        CustomerFactory $customerFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->customerDataFactory = $customerDataFactory;
        $this->formKeyValidator = $formKeyValidator;
        $this->accountManagement = $accountManagement;
        $this->customerFactory = $customerFactory;
    }

    /**
     * Check if customer is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * Parse values from form inputs
     *
     * @param array $formData
     * @param int $step
     * @return array
     */
    protected function parseFormValues(array $formData, $step)
    {
        switch ($step) {
            case 1:
                // Parse values from step 1 inputs
                $formData['firstname'] = $this->getRequest()->getParam('firstname');
                $formData['lastname'] = $this->getRequest()->getParam('lastname');
                $formData['email'] = $this->getRequest()->getParam('email');
                $formData['password'] = $this->getRequest()->getParam('password');
                break;
            case 2:
                // Parse values from step 2 inputs
                $formData['dob'] = $this->getRequest()->getParam('dob');
                $formData['gender'] = $this->getRequest()->getParam('gender');
                break;
            case 3:
                // Parse values from step 3 inputs
                $formData['telephone'] = $this->getRequest()->getParam('telephone');
                $formData['street'][0] = $this->getRequest()->getParam('street');
                $formData['city'] = $this->getRequest()->getParam('city');
                $formData['postcode'] = $this->getRequest()->getParam('postcode');
                $formData['country_id'] = $this->getRequest()->getParam('country');
                $formData['region'] = $this->getRequest()->getParam('region');
                $formData['is_subscribed'] = $this->getRequest()->getParam('is_subscribed', 0); // Default to 0 if not checked
                break;
            case 4:
                // No need to parse values for step 4
                break;
            default:
                // Handle invalid step
                break;
        }

        return $formData;
    }

   /**
     * Process customer registration
     *
     * @param int $step
     * @return bool|string
     */
    public function processRegistration($step)
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return false;
        }

        try {
            // Parse form values based on current step
            $formData = [];
            $formData = $this->parseFormValues($formData, $step);

            // Perform validation, data processing, and customer creation here
            switch ($step) {
                case 1:
                    // Process data for step 1
                    // No additional processing needed as form values are already parsed
                    break;
                case 2:
                    // Process data for step 2
                    // No additional processing needed as form values are already parsed
                    break;
                case 3:
                    // Process data for step 3
                    // No additional processing needed as form values are already parsed
                    break;
                case 4:
                    // Process data for step 4 and complete registration
                    $this->createCustomer($formData);
                    break;
                default:
                    // Handle invalid step
                    break;
            }

            // If registration is successful, return true
            return true;
        } catch (LocalizedException $e) {
            // Handle exception
            return $e->getMessage();
        }
    }

    /**
     * Create customer account
     *
     * @param array $formData
     * @return void
     */
    protected function createCustomer(array $formData)
    {
        $customer = $this->customerDataFactory->create();
        $customer->setFirstname($formData['firstname']);
        $customer->setLastname($formData['lastname']);
        $customer->setEmail($formData['email']);
        $customer->setPassword($formData['password']);
        $this->accountManagement->createAccount($customer);
    }
}
