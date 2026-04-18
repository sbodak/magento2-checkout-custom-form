<?php

/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Observer\Sales;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

class OrderLoadAfter implements ObserverInterface
{
    public function __construct(private readonly OrderExtensionFactory $orderExtensionFactory) {}

    public function execute(Observer $observer): void
    {
        $order = $observer->getOrder();

        $extensionAttributes = $order->getExtensionAttributes()
            ?? $this->orderExtensionFactory->create();

        $extensionAttributes->setCheckoutBuyerName(
            $order->getData(CustomFieldsInterface::CHECKOUT_BUYER_NAME)
        );
        $extensionAttributes->setCheckoutBuyerEmail(
            $order->getData(CustomFieldsInterface::CHECKOUT_BUYER_EMAIL)
        );
        $extensionAttributes->setCheckoutPurchaseOrderNo(
            $order->getData(CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO)
        );
        $extensionAttributes->setCheckoutGoodsMark(
            $order->getData(CustomFieldsInterface::CHECKOUT_GOODS_MARK)
        );
        $extensionAttributes->setCheckoutComment(
            $order->getData(CustomFieldsInterface::CHECKOUT_COMMENT)
        );

        $order->setExtensionAttributes($extensionAttributes);
    }
}
