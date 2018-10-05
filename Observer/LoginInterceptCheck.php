<?php
namespace Baze\CredentialWorkflowControl\Observer;

class LoginInterceptCheck implements \Magento\Framework\Event\ObserverInterface
{
    public const SESSION_VAR_NAME = "Baze_InterceptToken";
    public const DESTINATION_PATH = "customer/account/createlogin";
    public const WHITELIST_REGEXP = "/^\/?customer\/?/";
    
    protected $customerSession;
    protected $request;
    protected $urlInterface;
    
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->customerSession = $customerSession;
        $this->request = $request;
        $this->urlInterface = $urlInterface;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->customerSession->isLoggedIn()) {
            $path = $this->request->getPathInfo();
            $inWhitelist = (preg_match(self::WHITELIST_REGEXP, $path) == 1);
            if (!$inWhitelist) {
                $intercept = $this->customerSession->getData(self::SESSION_VAR_NAME);
                if ($intercept === "1") {
                    $url = $this->urlInterface->getUrl(self::DESTINATION_PATH);
                    $observer->getControllerAction()->getResponse()->setRedirect($url);
                }
            }
        }
    }
}
