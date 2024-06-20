<?php
namespace React\React\Model;

class CustomerSessionManager implements \React\React\Api\CustomerSessionManagementInterface
{
    protected $customerRepository;
    protected $customerSession;

    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
    }

    public function decodeJWT($jwt) {
        // Split the JWT into its three parts: Header, Payload, Signature
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new Exception('Invalid JWT format');
        }
    
        // Decode the Payload
        $payload = $parts[1];
        // Fix characters that are different in URL-safe Base64 encoding
        $payload = str_replace(['-', '_'], ['+', '/'], $payload);
        // Decode Base64 string and convert JSON to an associative array
        $decodedPayload = base64_decode($payload);
        $jsonPayload = json_decode($decodedPayload, true);
    
        if (!$jsonPayload) {
            throw new Exception('Invalid payload encoding');
        }
    
        return $jsonPayload;
    }

    public function setCustomerSession($token)
    {
        try {
            $decodedToken = $this->decodeJWT($token); // Decode the JWT token
            $customerId = $decodedToken['uid']; // Assuming 'uid' is the key that holds the customer ID in the JWT
            $customer = $this->customerRepository->getById($customerId);
            $this->customerSession->setCustomerDataAsLoggedIn($customer);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
