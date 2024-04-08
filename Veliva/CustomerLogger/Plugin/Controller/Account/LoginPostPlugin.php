<?php

namespace Veliva\CustomerLogger\Plugin\Controller\Account;

use Magento\Customer\Controller\Account\LoginPost;
use Magento\Customer\Model\Session\Proxy;
use Veliva\ConfigurableLogger\Logger\Logger;
use Veliva\ConfigurableLogger\Model\Config;

class LoginPostPlugin
{
    /**
     * @var Proxy
     */
    protected Proxy $customerSession;

    /**
     * @var Logger
     */
    protected Logger $configurableLoggerLogger;

    /**
     * @var Config
     */
    protected Config $configurableLoggerConfig;

    /**
     * @param Proxy $customerSession
     * @param Logger $configurableLoggerLogger
     * @param Config $configurableLoggerConfig
     */
    public function __construct(
        Proxy $customerSession,
        Logger $configurableLoggerLogger,
        Config $configurableLoggerConfig
    ) {
        $this->customerSession = $customerSession;
        $this->configurableLoggerLogger = $configurableLoggerLogger;
        $this->configurableLoggerConfig = $configurableLoggerConfig;
    }

    /**
     * @param LoginPost $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(LoginPost $subject, $result): mixed
    {
        if ($this->configurableLoggerConfig->isLoggingEnabled() && $subject->getRequest()->isPost()) {
            $login = $subject->getRequest()->getPost('login');
            if (!$this->customerSession->isLoggedIn()) {
                $email = $login['username'] ?? 'null';
                $this->configurableLoggerLogger->info('Frontend Customer login failed, email: ' . $email);
            }
        }

        return $result;
    }
}
