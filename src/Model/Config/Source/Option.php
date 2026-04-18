<?php

/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

class Option implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => CustomFieldsInterface::CHECKOUT_BUYER_NAME, 'label' => __('Buyer Name')],
            ['value' => CustomFieldsInterface::CHECKOUT_BUYER_EMAIL, 'label' => __('Buyer Email')],
            ['value' => CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO, 'label' => __('Purchase order no.')],
            ['value' => CustomFieldsInterface::CHECKOUT_GOODS_MARK, 'label' => __('Goods mark')],
            ['value' => CustomFieldsInterface::CHECKOUT_COMMENT, 'label' => __('Comment')],
        ];
    }
}
