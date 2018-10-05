<?php
namespace Baze\CredentialWorkflowControl\Observer;

use \Baze\CredentialWorkflowControl\Helper\CustomerAttributeReader;

class LoginAccountAnalysis implements \Magento\Framework\Event\ObserverInterface
{
    protected $customerSession;
    protected $customerAttributeReader;
    
    protected $_customer;
    
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        CustomerAttributeReader $customerAttributeReader
    ) {
        $this->customerSession = $customerSession;
        $this->customerAttributeReader = $customerAttributeReader;
    }
    
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        if ($this->customerSession->isLoggedIn()) {
            $email = $this->customerSession->getCustomer()->getEmail();
            $intercept = false;
            if (strtolower(substr($email, -11)) == '@null.email') {
                $intercept = true;
            } else if ($this->customerAttributeReader->getAttribute($this->customerSession->getCustomer()->getId(), 'password_change_required', true) == "1") {
                $intercept = true;
            }
            $this->customerSession->setData(LoginInterceptCheck::SESSION_VAR_NAME, $intercept ? "1" : "0");
        }
        
        return $this;
	}
}
