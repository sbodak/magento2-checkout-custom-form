<?php

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Test\Unit\Helper;

use Bodak\CheckoutCustomForm\Helper\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ConfigTest extends TestCase
{
    private ScopeConfigInterface&MockObject $scopeConfig;
    private StoreManagerInterface&MockObject $storeManager;
    private LoggerInterface&MockObject $logger;
    private Config $config;

    protected function setUp(): void
    {
        $this->scopeConfig  = $this->createMock(ScopeConfigInterface::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->logger       = $this->createMock(LoggerInterface::class);

        $this->config = new Config($this->scopeConfig, $this->storeManager, $this->logger);
    }

    public function testGetEnabledFieldsReturnsArray(): void
    {
        $this->scopeConfig->method('getValue')
            ->with('bodak/checkout/enabled_fields', ScopeInterface::SCOPE_STORE)
            ->willReturn('checkout_buyer_name,checkout_buyer_email');

        $result = $this->config->getEnabledFields();

        $this->assertSame(['checkout_buyer_name', 'checkout_buyer_email'], $result);
    }

    public function testGetEnabledFieldsReturnsEmptyArrayWhenNull(): void
    {
        $this->scopeConfig->method('getValue')->willReturn(null);

        $this->assertSame([], $this->config->getEnabledFields());
    }

    public function testGetAllowedLengthReturnsConfiguredValue(): void
    {
        $store = $this->createMock(Store::class);
        $store->method('getId')->willReturn(1);
        $this->storeManager->method('getStore')->willReturn($store);

        $this->scopeConfig->method('getValue')
            ->with('checkout/general/checkout_buyer_name_limit', ScopeInterface::SCOPE_STORES, 1)
            ->willReturn('50');

        $this->assertSame(50, $this->config->getAllowedLength('checkout_buyer_name'));
    }

    public function testGetAllowedLengthReturnsLimitNotSetWhenEmpty(): void
    {
        $store = $this->createMock(Store::class);
        $store->method('getId')->willReturn(1);
        $this->storeManager->method('getStore')->willReturn($store);

        $this->scopeConfig->method('getValue')->willReturn('');

        $this->assertSame(Config::LIMIT_NOT_SET, $this->config->getAllowedLength('checkout_buyer_name'));
    }

    public function testGetAllowedLengthFallsBackOnException(): void
    {
        $this->storeManager->method('getStore')
            ->willThrowException(new NoSuchEntityException(__('Store not found')));

        $this->logger->expects($this->atLeastOnce())->method('error');

        $this->scopeConfig->method('getValue')
            ->willReturnOnConsecutiveCalls(null, '100');

        $result = $this->config->getAllowedLength('checkout_buyer_name');

        $this->assertSame(100, $result);
    }
}
