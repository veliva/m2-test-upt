<?php

namespace Veliva\CustomerLogger\Plugin\Model\Auth;

use Magento\Backend\Model\Auth\Session;
use Veliva\ConfigurableLogger\Logger\Logger as ConfigurableLogger;
use Veliva\ConfigurableLogger\Model\Config;

class SessionPlugin
{
    /**
     * @var ConfigurableLogger
     */
    protected ConfigurableLogger $configurableLogger;

    /**
     * @var Config
     */
    protected Config $configurableLoggerConfig;

    /**
     * @param ConfigurableLogger $configurableLogger
     * @param Config $configurableLoggerConfig
     */
    public function __construct(
        ConfigurableLogger $configurableLogger,
        Config $configurableLoggerConfig
    ) {
        $this->configurableLogger = $configurableLogger;
        $this->configurableLoggerConfig = $configurableLoggerConfig;
    }

    /**
     * @param Session $subject
     * @return void
     */
    public function beforeProcessLogout(Session $subject): void
    {
        if (!$this->configurableLoggerConfig->isLoggingEnabled()) return;

        $userIdentifier = empty($subject->getUser()) ? 'failed to get user ID' : $subject->getUser()->getId();
        $this->configurableLogger->info(sprintf('Backend User ID: %s - backend_auth_user_logout', $userIdentifier));
    }
}
