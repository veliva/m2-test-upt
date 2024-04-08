<?php

namespace Veliva\HideImages\Plugin\Model\View\Asset;

use Magento\Catalog\Model\View\Asset\Image;
use Magento\Catalog\Model\View\Asset\PlaceholderFactory;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class ImagePlugin
{
    /**
     * @var PlaceholderFactory
     */
    protected PlaceholderFactory $placeholderFactory;

    /**
     * @var Context
     */
    protected Context $httpContext;

    /**
     * @var State
     */
    protected State $state;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param PlaceholderFactory $placeholderFactory
     * @param Context $httpContext
     * @param State $state
     * @param LoggerInterface $logger
     */
    public function __construct(
        PlaceholderFactory $placeholderFactory,
        Context $httpContext,
        State $state,
        LoggerInterface $logger
    ) {
        $this->placeholderFactory = $placeholderFactory;
        $this->httpContext = $httpContext;
        $this->state = $state;
        $this->logger = $logger;
    }

    /**
     * @param Image $subject
     * @param string $result
     * @return string
     */
    public function afterGetUrl(Image $subject, string $result): string
    {
        try {
            if ($this->state->getAreaCode() === \Magento\Framework\App\Area::AREA_FRONTEND
                && !$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH)) {
                $placeHolderAsset = $this->placeholderFactory->create(
                    [
                        'type' => $subject->getSourceContentType()
                    ]
                );

                return $placeHolderAsset->getUrl();
            }
        } catch (LocalizedException $e) {
            $this->logger->error('Exception during catalog product image replacement with placeholder: ' . $e->getMessage());
        }

        return $result;
    }
}
