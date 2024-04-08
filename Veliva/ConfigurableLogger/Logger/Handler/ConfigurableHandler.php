<?php

namespace Veliva\ConfigurableLogger\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;
use Veliva\ConfigurableLogger\Model\Config;

class ConfigurableHandler extends Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * @var Config
     */
    protected Config $configurableLoggerConfig;

    /**
     * @param Config $configurableLoggerConfig
     * @param DriverInterface $filesystem
     * @param string|null $filePath
     * @param string|null $fileName
     */
    public function __construct(
        Config $configurableLoggerConfig,
        DriverInterface $filesystem,
        ?string $filePath = null,
        ?string $fileName = null
    ) {
        $this->configurableLoggerConfig = $configurableLoggerConfig;
        $this->initLoggerFilename();

        parent::__construct($filesystem, $filePath, $fileName);
    }

    /**
     * @return void
     */
    protected function initLoggerFilename(): void
    {
        if ($filename = $this->configurableLoggerConfig->getLoggerFilename()) {
            $this->fileName = '/var/log/' . $filename . '.log';
        }
    }
}
