<?php
namespace Bodak\CheckoutCustomForm\Observer\Sales;

use Magento\Framework\Event\ObserverInterface;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

/**
 * Class OrderLoadAfter
 * @package Bodak\CheckoutCustomForm\Observer\Sales
 */
class OrderLoadAfter implements ObserverInterface

{
    /**
     * @var \Magento\Sales\Api\Data\OrderExtension
     */
    protected $orderExtension;

    public function __construct(
        \Magento\Sales\Api\Data\OrderExtension $orderExtension
    ) {
        $this->orderExtension = $orderExtension;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $order = $observer->getOrder();

        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtension;
        }

        $extensionAttributes->setCheckoutBuyerName($order->getData(CustomFieldsInterface::CHECKOUT_BUYER_NAME));
        $extensionAttributes->setCheckoutBuyerEmail($order->getData(CustomFieldsInterface::CHECKOUT_BUYER_EMAIL));
        $extensionAttributes->setCheckoutPurchaseOrderNo($order->getData(CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO));
        $extensionAttributes->setCheckoutGoodsMark($order->getData(CustomFieldsInterface::CHECKOUT_GOODS_MARK));
        $extensionAttributes->setCheckoutComment($order->getData(CustomFieldsInterface::CHECKOUT_COMMENT));

        $order->setExtensionAttributes($extensionAttributes);
    }
}
