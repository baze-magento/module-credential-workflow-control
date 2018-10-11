<?php
namespace Baze\CredentialWorkflowControl\Block\Account;

use Magento\Framework\App\ObjectManager;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;

class CreateLogin extends \Magento\Customer\Block\Account\Resetpassword
{
    protected $customerRepository;
    protected $session;
    
    protected function getCustomerRepository()
    {
        if ($this->customerRepository === null) {
            $this->customerRepository = ObjectManager::getInstance()->get(CustomerRepositoryInterface::class);
        }
        return $this->customerRepository;
    }
    
    protected function getSession()
    {
        if ($this->session === null) {
            $this->session = ObjectManager::getInstance()->get(Session::class);
        }
        return $this->session;
    }
    
    protected function getCustomerDataObject($customerId)
    {
        return $this->getCustomerRepository()->getById($customerId);
    }
    
    public function getCurrentCustomerData()
    {
        return $this->getCustomerDataObject($this->getSession()->getCustomerId());
    }
    
    public function isEmailValid() {
        $email = $this->getCustomerDataObject($this->getSession()->getCustomerId())->getEmail();
        return (strtolower(substr($email, -11)) != '@null.email');
    }
}
