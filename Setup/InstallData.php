<?php
namespace Baze\CredentialWorkflowControl\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 *
 * @package Baze\PasswordControl\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * Customer setup factory
     *
     * @var \Magento\Customer\Setup\CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * Init
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(\Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * Installs DB schema for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        // Add new customer attribute
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'password_change_required',
            [
                'label'      => 'Password Change Required On Next Login',
                'input'      => 'select',
                'source'     => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required'   => false,
                'default'    => '0',
                'sort_order' => 10,
                'visible'    => true,
                'system'     => false
            ]
        );

        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'password_change_required');
        $attribute->setData('used_in_forms', ['adminhtml_customer']);
        $attribute->save();

        $setup->endSetup();
    }
}
