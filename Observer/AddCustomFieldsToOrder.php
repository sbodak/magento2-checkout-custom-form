<?php
/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

/**
 * Class AddCustomFieldsToOrder
 *
 * @category Observer
 * @package  Bodak\CheckoutCustomForm\Observer
 */
class AddCustomFieldsToOrder implements ObserverInterface
{
    /**
     * Execute observer method.
     *
     * @param Observer $observer Observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $order->setData(
            CustomFieldsInterface::CHECKOUT_BUYER_NAME,
            $quote->getData(CustomFieldsInterface::CHECKOUT_BUYER_NAME)
        );
        $order->setData(
            CustomFieldsInterface::CHECKOUT_BUYER_EMAIL,
            $quote->getData(CustomFieldsInterface::CHECKOUT_BUYER_EMAIL)
        );
        $order->setData(
            CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO,
            $quote->getData(CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO)
        );
        $order->setData(
            CustomFieldsInterface::CHECKOUT_GOODS_MARK,
            $quote->getData(CustomFieldsInterface::CHECKOUT_GOODS_MARK)
        );
        $order->setData(
            CustomFieldsInterface::CHECKOUT_COMMENT,
            $quote->getData(CustomFieldsInterface::CHECKOUT_COMMENT)
        );
    }
}
