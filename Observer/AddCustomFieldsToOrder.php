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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * AddCustomFieldsToOrder constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Execute observer method.
     *
     * @param Observer $observer Observer
     *
     * @return void
     */
    public function execute(
        Observer $observer
    ) {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
        $config = explode(',', $this->_scopeConfig->getValue('bodak/checkout/enabled_fields', Magento\Store\Model\ScopeInterface::SCOPE_STORE));

        if (in_array(CustomFieldsInterface::CHECKOUT_BUYER_NAME, $config)) {
            $order->setData(
                CustomFieldsInterface::CHECKOUT_BUYER_NAME,
                $quote->getData(CustomFieldsInterface::CHECKOUT_BUYER_NAME)
            );
        }
        if (in_array(CustomFieldsInterface::CHECKOUT_BUYER_EMAIL, $config)) {
            $order->setData(
                CustomFieldsInterface::CHECKOUT_BUYER_EMAIL,
                $quote->getData(CustomFieldsInterface::CHECKOUT_BUYER_EMAIL)
            );
        }
        if (in_array(CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO, $config)) {
            $order->setData(
                CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO,
                $quote->getData(CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO)
            );
        }
        if (in_array(CustomFieldsInterface::CHECKOUT_GOODS_MARK, $config)) {
            $order->setData(
                CustomFieldsInterface::CHECKOUT_GOODS_MARK,
                $quote->getData(CustomFieldsInterface::CHECKOUT_GOODS_MARK)
            );
        }
        if (in_array(CustomFieldsInterface::CHECKOUT_COMMENT, $config)) {
            $order->setData(
                CustomFieldsInterface::CHECKOUT_COMMENT,
                $quote->getData(CustomFieldsInterface::CHECKOUT_COMMENT)
            );
        }
    }
}
