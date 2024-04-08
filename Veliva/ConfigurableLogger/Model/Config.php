<?php

namespace Veliva\ConfigurableLogger\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Config
{
    public const CONFIGURABLE_LOGGER_GENERAL = 'veliva/general/';
    public const IS_LOGGING_ENABLED = self::CONFIGURABLE_LOGGER_GENERAL . 'enabled';
    public const LOGGER_FILENAME = self::CONFIGURABLE_LOGGER_GENERAL . 'filename';

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @param $store
     * @return bool
     */
    public function isLoggingEnabled($store = null): bool
    {
        try {
            return $this->scopeConfig->isSetFlag(
                self::IS_LOGGING_ENABLED,
                ScopeInterface::SCOPE_STORE,
                $store ?? $this->storeManager->getStore()->getId()
            );
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param $store
     * @return string|null
     */
    public function getLoggerFilename($store = null): ?string
    {
        try {
            return $this->scopeConfig->getValue(
                self::LOGGER_FILENAME,
                ScopeInterface::SCOPE_STORE,
                $store ?? $this->storeManager->getStore()->getId()
            );
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }
}
