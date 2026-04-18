<?php

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Test\Unit\Observer;

use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;
use Bodak\CheckoutCustomForm\Helper\Config;
use Bodak\CheckoutCustomForm\Observer\AddCustomFieldsToOrder;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddCustomFieldsToOrderTest extends TestCase
{
    private Config&MockObject $config;
    private AddCustomFieldsToOrder $observer;

    protected function setUp(): void
    {
        $this->config   = $this->createMock(Config::class);
        $this->observer = new AddCustomFieldsToOrder($this->config);
    }

    public function testOnlyEnabledFieldsAreCopiedToOrder(): void
    {
        $enabledFields = [
            CustomFieldsInterface::CHECKOUT_BUYER_NAME,
            CustomFieldsInterface::CHECKOUT_COMMENT,
        ];

        $this->config->method('getEnabledFields')->willReturn($enabledFields);

        $quote = $this->createMock(Quote::class);
        $quote->method('getData')
            ->willReturnCallback(fn(string $key) => match ($key) {
                CustomFieldsInterface::CHECKOUT_BUYER_NAME => 'John',
                CustomFieldsInterface::CHECKOUT_COMMENT    => 'Fast delivery',
                default                                    => null,
            });

        $order = $this->createMock(Order::class);
        $order->expects($this->exactly(2))
            ->method('setData')
            ->withConsecutive(
                [CustomFieldsInterface::CHECKOUT_BUYER_NAME, 'John'],
                [CustomFieldsInterface::CHECKOUT_COMMENT, 'Fast delivery']
            );

        $event = $this->createMock(Event::class);
        $event->method('getOrder')->willReturn($order);
        $event->method('getQuote')->willReturn($quote);

        $observerEvent = $this->createMock(Observer::class);
        $observerEvent->method('getEvent')->willReturn($event);

        $this->observer->execute($observerEvent);
    }

    public function testNoFieldsCopiedWhenNoneEnabled(): void
    {
        $this->config->method('getEnabledFields')->willReturn([]);

        $order = $this->createMock(Order::class);
        $order->expects($this->never())->method('setData');

        $quote = $this->createMock(Quote::class);

        $event = $this->createMock(Event::class);
        $event->method('getOrder')->willReturn($order);
        $event->method('getQuote')->willReturn($quote);

        $observerEvent = $this->createMock(Observer::class);
        $observerEvent->method('getEvent')->willReturn($event);

        $this->observer->execute($observerEvent);
    }
}
