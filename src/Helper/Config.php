<?php

/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Config
{
    public const LIMIT_NOT_SET = -1;
    private const XML_PATH_ENABLED_FIELDS = 'bodak/checkout/enabled_fields';
    private const XML_PATH_FIELD_LIMIT = 'checkout/general/%s_limit';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly StoreManagerInterface $storeManager,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * Returns the list of enabled field names.
     *
     * @return string[]
     */
    public function getEnabledFields(): array
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_ENABLED_FIELDS,
            ScopeInterface::SCOPE_STORE
        );

        return $value ? array_filter(explode(',', (string)$value)) : [];
    }

    /**
     * Returns the configured character limit for an attribute, or LIMIT_NOT_SET.
     */
    public function getAllowedLength(string $attribute): int
    {
        $configPath = sprintf(self::XML_PATH_FIELD_LIMIT, $attribute);

        try {
            $allowedLength = $this->scopeConfig->getValue(
                $configPath,
                ScopeInterface::SCOPE_STORES,
                $this->storeManager->getStore()->getId()
            );
        } catch (NoSuchEntityException $e) {
            $this->logger->error(
                'Cannot get allowed length for custom field. Falling back to default scope value.',
                ['exception' => $e]
            );
            $allowedLength = $this->scopeConfig->getValue($configPath);
        }

        return (int)$allowedLength ?: self::LIMIT_NOT_SET;
    }
}
