<?php
namespace Baze\CredentialWorkflowControl\Plugin;

use Magento\Framework\App\Response\Http as ResponseHttp;
use Baze\CredentialWorkflowControl\Observer\LoginInterceptCheck;

/**
 * Plugin for processing builtin cache.
 * @see Magento\PageCache\Model\App\FrontController\BuiltinPlugin for base functionality
 */
class Cache extends \Magento\PageCache\Model\App\FrontController\BuiltinPlugin
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $config;

    /**
     * @var \Magento\Framework\App\PageCache\Version
     */
    protected $version;

    /**
     * @var \Magento\Framework\App\PageCache\Kernel
     */
    protected $kernel;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Constructor
     *
     * @param \Magento\PageCache\Model\Config $config
     * @param \Magento\Framework\App\PageCache\Version $version
     * @param \Magento\Framework\App\PageCache\Kernel $kernel
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        \Magento\PageCache\Model\Config $config,
        \Magento\Framework\App\PageCache\Version $version,
        \Magento\Framework\App\PageCache\Kernel $kernel,
        \Magento\Framework\App\State $state,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->config = $config;
        $this->version = $version;
        $this->kernel = $kernel;
        $this->state = $state;
        $this->customerSession = $customerSession;
    }

    /**
     * Performs an intercept check to ensure the user isn't being intercepted, before
     * handing control off to the standard function.
     *
     * @param \Magento\Framework\App\FrontControllerInterface $subject
     * @param callable $proceed
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\Controller\ResultInterface|\Magento\Framework\App\Response\Http
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundDispatch(
        \Magento\Framework\App\FrontControllerInterface $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        if ($this->customerSession->isLoggedIn()) {
            $intercept = $this->customerSession->getData(LoginInterceptCheck::SESSION_VAR_NAME);
            if ($intercept === "1") {
                return $proceed($request);
            }
        }
        return parent::aroundDispatch($subject, $proceed, $request);
    }
}
