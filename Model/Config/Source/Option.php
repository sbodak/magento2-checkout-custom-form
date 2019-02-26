<?php

namespace Bodak\CheckoutCustomForm\Model\Config\Source;

use \Magento\Framework\Option\ArrayInterface;
use \Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

class Option implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => CustomFieldsInterface::CHECKOUT_BUYER_NAME, 'label' => 'Buyer Name'],
            ['value' => CustomFieldsInterface::CHECKOUT_BUYER_EMAIL, 'label' => 'Buyer email'],
            ['value' => CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO, 'label' => 'Purchase order no.'],
            ['value' => CustomFieldsInterface::CHECKOUT_GOODS_MARK, 'label' => 'Goods mark'],
            ['value' => CustomFieldsInterface::CHECKOUT_COMMENT, 'label' => 'Comment'],
        ];
    }
}