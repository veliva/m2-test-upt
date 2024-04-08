<?php

namespace Veliva\CustomerLogger\Observer;

use Magento\Customer\Model\Customer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Veliva\ConfigurableLogger\Logger\Logger as ConfigurableLogger;
use Veliva\ConfigurableLogger\Model\Config;

class LogCustomerObserver implements ObserverInterface
{
    /**
     * @var ConfigurableLogger
     */
    protected ConfigurableLogger $configurableLogger;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var Config
     */
    protected Config $configurableLoggerConfig;

    /**
     * @param ConfigurableLogger $configurableLogger
     * @param LoggerInterface $logger
     * @param Config $configurableLoggerConfig
     */
    public function __construct(
        ConfigurableLogger $configurableLogger,
        LoggerInterface $logger,
        Config $configurableLoggerConfig
    ) {
        $this->configurableLogger = $configurableLogger;
        $this->logger = $logger;
        $this->configurableLoggerConfig = $configurableLoggerConfig;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if (!$this->configurableLoggerConfig->isLoggingEnabled()) return;

        $eventName = $observer->getEvent()->getName();
        try {
            if (str_starts_with($eventName, 'backend')) {
                $userIdentifier = empty($observer->getUser()) ? $observer->getUserName() : $observer->getUser()->getId();
                $textToLog = sprintf('Backend User ID: %s - %s', $userIdentifier, $eventName);
            } else {
                /** @var Customer $customer */
                $customer = $observer->getCustomer();
                $textToLog = sprintf('Frontend Customer ID: %s - %s', $customer->getId(), $observer->getEvent()->getName());
            }

            $this->configurableLogger->info($textToLog);
        } catch (\Exception $e) {
            $this->logger->error('Exception during ' . $observer->getEvent()->getName() . ' log: ' . $e->getMessage());
        }
    }
}
