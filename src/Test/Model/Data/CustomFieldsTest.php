<?php

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Test\Unit\Model\Data;

use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;
use Bodak\CheckoutCustomForm\Helper\Config;
use Bodak\CheckoutCustomForm\Model\Data\CustomFields;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CustomFieldsTest extends TestCase
{
    private Config&MockObject $config;
    private CustomFields $customFields;

    protected function setUp(): void
    {
        $this->config = $this->createMock(Config::class);
        $extensionFactory = $this->createMock(ExtensionAttributesFactory::class);
        $attributeFactory = $this->createMock(AttributeValueFactory::class);

        $this->customFields = new CustomFields(
            $this->config,
            $extensionFactory,
            $attributeFactory
        );
    }

    public function testSetAndGetCheckoutBuyerName(): void
    {
        $this->customFields->setCheckoutBuyerName('John Doe');
        $this->assertSame('John Doe', $this->customFields->getCheckoutBuyerName());
    }

    public function testSetAndGetCheckoutBuyerEmail(): void
    {
        $this->customFields->setCheckoutBuyerEmail('john@example.com');
        $this->assertSame('john@example.com', $this->customFields->getCheckoutBuyerEmail());
    }

    public function testSetAndGetCheckoutPurchaseOrderNo(): void
    {
        $this->customFields->setCheckoutPurchaseOrderNo('PO-123');
        $this->assertSame('PO-123', $this->customFields->getCheckoutPurchaseOrderNo());
    }

    public function testSetAndGetCheckoutGoodsMark(): void
    {
        $this->customFields->setCheckoutGoodsMark('fragile');
        $this->assertSame('fragile', $this->customFields->getCheckoutGoodsMark());
    }

    public function testSetAndGetCheckoutComment(): void
    {
        $this->customFields->setCheckoutComment('Please deliver in the morning.');
        $this->assertSame('Please deliver in the morning.', $this->customFields->getCheckoutComment());
    }

    public function testSetNullableFields(): void
    {
        $this->customFields->setCheckoutBuyerName(null);
        $this->assertNull($this->customFields->getCheckoutBuyerName());
    }

    public function testIsFieldEnabledReturnsTrueForEnabledField(): void
    {
        $this->config->expects($this->once())
            ->method('getEnabledFields')
            ->willReturn([CustomFieldsInterface::CHECKOUT_BUYER_NAME]);

        $this->assertTrue($this->customFields->isFieldEnabled(CustomFieldsInterface::CHECKOUT_BUYER_NAME));
    }

    public function testIsFieldEnabledReturnsFalseForDisabledField(): void
    {
        $this->config->expects($this->once())
            ->method('getEnabledFields')
            ->willReturn([CustomFieldsInterface::CHECKOUT_BUYER_NAME]);

        $this->assertFalse($this->customFields->isFieldEnabled(CustomFieldsInterface::CHECKOUT_BUYER_EMAIL));
    }

    public function testEnabledFieldsCachedAfterFirstCall(): void
    {
        $this->config->expects($this->once())
            ->method('getEnabledFields')
            ->willReturn([CustomFieldsInterface::CHECKOUT_BUYER_NAME]);

        // Call twice – config should only be queried once
        $this->customFields->isFieldEnabled(CustomFieldsInterface::CHECKOUT_BUYER_NAME);
        $this->customFields->isFieldEnabled(CustomFieldsInterface::CHECKOUT_BUYER_EMAIL);
    }

    public function testIsCheckoutBuyerNameEnabled(): void
    {
        $this->config->method('getEnabledFields')
            ->willReturn([CustomFieldsInterface::CHECKOUT_BUYER_NAME]);

        $this->assertTrue($this->customFields->isCheckoutBuyerNameEnabled());
        $this->assertFalse($this->customFields->isCheckoutBuyerEmailEnabled());
    }

    public function testIsCheckoutCommentEnabled(): void
    {
        $this->config->method('getEnabledFields')
            ->willReturn([CustomFieldsInterface::CHECKOUT_COMMENT]);

        $this->assertTrue($this->customFields->isCheckoutCommentEnabled());
    }
}
