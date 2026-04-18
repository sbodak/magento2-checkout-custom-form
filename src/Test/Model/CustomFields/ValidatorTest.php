<?php

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Test\Unit\Model\CustomFields;

use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;
use Bodak\CheckoutCustomForm\Helper\Config;
use Bodak\CheckoutCustomForm\Model\CustomFields\Validator;
use InvalidArgumentException;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    private Config&MockObject $config;
    private UnderscoreToCamelCase $filter;
    private Validator $validator;

    protected function setUp(): void
    {
        $this->config    = $this->createMock(Config::class);
        $this->filter    = new UnderscoreToCamelCase();
        $this->validator = new Validator($this->config, $this->filter);
    }

    public function testIsValidReturnsTrueWhenNoLimitsSet(): void
    {
        $this->config->method('getAllowedLength')->willReturn(Config::LIMIT_NOT_SET);

        $customFields = $this->buildCustomFields([
            'getCheckoutBuyerName'       => 'John Doe',
            'getCheckoutBuyerEmail'      => 'john@example.com',
            'getCheckoutPurchaseOrderNo' => 'PO-123',
            'getCheckoutGoodsMark'       => 'fragile',
            'getCheckoutComment'         => 'Handle with care',
        ]);

        $this->assertTrue($this->validator->isValid($customFields));
    }

    public function testIsValidReturnsFalseOnInvalidEmail(): void
    {
        $this->config->method('getAllowedLength')->willReturn(Config::LIMIT_NOT_SET);

        $customFields = $this->buildCustomFields([
            'getCheckoutBuyerEmail' => 'not-an-email',
        ]);

        $this->assertFalse($this->validator->isValid($customFields));
        $this->assertNotEmpty($this->validator->getMessages());
    }

    public function testIsValidReturnsTrueWhenEmailIsEmpty(): void
    {
        $this->config->method('getAllowedLength')->willReturn(Config::LIMIT_NOT_SET);

        $customFields = $this->buildCustomFields(['getCheckoutBuyerEmail' => '']);

        $this->assertTrue($this->validator->isValid($customFields));
    }

    public function testIsValidReturnsFalseWhenFieldExceedsLimit(): void
    {
        $this->config->method('getAllowedLength')
            ->willReturnCallback(fn(string $attr) => match ($attr) {
                CustomFieldsInterface::CHECKOUT_BUYER_NAME => 5,
                default => Config::LIMIT_NOT_SET,
            });

        $customFields = $this->buildCustomFields([
            'getCheckoutBuyerName' => 'This name is way too long',
        ]);

        $this->assertFalse($this->validator->isValid($customFields));
        $this->assertNotEmpty($this->validator->getMessages());
    }

    public function testIsValidReturnsTrueWhenFieldExactlyAtLimit(): void
    {
        $this->config->method('getAllowedLength')
            ->willReturnCallback(fn(string $attr) => match ($attr) {
                CustomFieldsInterface::CHECKOUT_BUYER_NAME => 8,
                default => Config::LIMIT_NOT_SET,
            });

        $customFields = $this->buildCustomFields([
            'getCheckoutBuyerName' => 'John Doe', // exactly 8 chars
        ]);

        $this->assertTrue($this->validator->isValid($customFields));
    }

    public function testIsValidThrowsOnInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->validator->isValid('not a CustomFieldsInterface');
    }

    private function buildCustomFields(array $getterReturns): CustomFieldsInterface&MockObject
    {
        $stub = $this->createMock(CustomFieldsInterface::class);

        $defaults = [
            'getCheckoutBuyerName'       => '',
            'getCheckoutBuyerEmail'      => '',
            'getCheckoutPurchaseOrderNo' => '',
            'getCheckoutGoodsMark'       => '',
            'getCheckoutComment'         => '',
        ];

        foreach (array_merge($defaults, $getterReturns) as $method => $value) {
            $stub->method($method)->willReturn($value);
        }

        return $stub;
    }
}
