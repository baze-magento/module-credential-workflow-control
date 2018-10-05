<?php
namespace Baze\CredentialWorkflowControl\Model\Import;

use \Magento\CustomerImportExport\Model\Import\Address;

class CustomerComposite extends \Magento\CustomerImportExport\Model\Import\CustomerComposite
{
    protected $nullEmailsImported = 0;
    
    public function validateRow(array $rowData, $rowNumber)
    {
        if (strlen(trim($rowData['email'])) == 0) {
            $rowData['email'] = 'valid@null.email';
        }
        return parent::validateRow($rowData, $rowNumber);
    }
    
    /**
     * This doesn't actually do anything yet. It's SUPPOSED to assign a unique null address to each user, but the script just errors.
     */
    protected function _prepareRowForDb(array $rowData)
    {
        if (strlen(trim($this->_currentEmail)) == 0) {
            $this->_currentEmail = 'noemail_'.time().$this->nullEmailsImported.'@null.email';
            $rowData['email'] = $this->_currentEmail;
            $nullEmailsImported++;
        }
        return parent::_prepareRowForDb($rowData);
    }
}
