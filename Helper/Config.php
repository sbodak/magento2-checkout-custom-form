<?php

namespace Bodak\CheckoutCustomForm\Helper;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Config
{
    const LIMIT_NOT_SET = -1;

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $config
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $config,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getEnabledFields()
    {
        return explode(',',
            $this->config->getValue('bodak/checkout/enabled_fields',
                ScopeInterface::SCOPE_STORE));
    }

    /**
     * @param $attribute
     *
     * @return int
     */
    public function getAllowedLength($attribute)
    {
        $configPath = 'checkout/general/' . $attribute . '_limit';
        try {
            $allowedLength = $this->config->getValue($configPath, ScopeInterface::SCOPE_STORES,
                $this->storeManager->getStore()->getId());
        } catch (NoSuchEntityException $e) {
            $this->logger->error('Cannot get allowed length for custom field. Falling back to default scope value.');
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $allowedLength = $this->config->getValue($configPath);
        }

        return (int)$allowedLength ?: self::LIMIT_NOT_SET;
    }
}