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
use Bodak\CheckoutCustomForm\Helper\Config;

class AddCustomFieldsToOrder implements ObserverInterface
{
    public function __construct(private readonly Config $config) {}

    public function execute(Observer $observer): void
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $enabledFields = $this->config->getEnabledFields();

        foreach (CustomFieldsInterface::ATTRIBUTES as $attribute) {
            if (in_array($attribute, $enabledFields, true)) {
                $order->setData($attribute, $quote->getData($attribute));
            }
        }
    }
}

