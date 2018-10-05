<?php
namespace Baze\CredentialWorkflowControl\Helper;

use \Magento\Customer\Model\ResourceModel\CustomerRepository;

class CustomerAttributeReader
{
    protected $customerRepository;
    
    protected $_customer;
    protected $_id;
    
    public function __construct(
        CustomerRepository $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }
    
    public function getAttribute($customerId, $attributeName, $isCustom = false)
    {
        $customer = $this->getCustomer($customerId);
        if ($isCustom) {
            $attr = $customer->getCustomAttribute($attributeName);
        } else {
            $attr = $customer->getAttribute($attributeName);
        }
        if (is_null($attr)) {
            return null;
        } else {
            return $attr->getValue();
        }
    }
    
    protected function getCustomer($id)
    {
        if (is_null($this->_customer) || $id != $this->_id) {
            $this->_customer = $this->customerRepository->getById($id);
            $this->_id = $id;
        }
        return $this->_customer;
    }
}
