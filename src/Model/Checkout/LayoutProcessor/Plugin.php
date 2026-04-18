<?php

/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Model\Checkout\LayoutProcessor;

use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;
use Bodak\CheckoutCustomForm\Helper\Config;
use Magento\Checkout\Block\Checkout\LayoutProcessor;

class Plugin
{
    /** @var array[] */
    private array $fields = [
        [
            'dataScopeName' => CustomFieldsInterface::CHECKOUT_BUYER_NAME,
            'label' => 'Buyer name',
        ],
        [
            'dataScopeName' => CustomFieldsInterface::CHECKOUT_BUYER_EMAIL,
            'label' => 'Buyer email',
            'validation' => [
                'validate-email' => true,
            ],
            'config' => [
                'tooltip' => [
                    'description' => 'We will send an order confirmation to this email address',
                ],
            ],
        ],
        [
            'dataScopeName' => CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO,
            'label' => 'Purchase order no.',
        ],
        [
            'dataScopeName' => CustomFieldsInterface::CHECKOUT_GOODS_MARK,
            'label' => 'Goods mark',
        ],
        [
            'dataScopeName' => CustomFieldsInterface::CHECKOUT_COMMENT,
            'label' => 'Comment',
            'config' => [
                'cols' => 15,
                'rows' => 2,
                'maxlength' => null,
                'elementTmpl' => 'Bodak_CheckoutCustomForm/form/element/textarea',
            ],
            'showTitle' => false,
        ],
    ];

    public function __construct(private readonly Config $config) {}

    /**
     * Inject custom checkout form fields into the layout dynamically.
     *
     * @param LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(LayoutProcessor $subject, array $jsLayout): array
    {
        $fields = $this->applyLengthLimitToFields($this->fields);

        $enabledFields = $this->config->getEnabledFields();

        foreach ($fields as $sortOrder => $field) {
            if (!in_array($field['dataScopeName'], $enabledFields, true)) {
                continue;
            }

            $field['showTitle'] ??= true;

            $formField = [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'config' => [
                    'customScope' => 'customCheckoutForm',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input',
                ],
                'provider' => 'checkoutProvider',
                'dataScope' => 'customCheckoutForm.' . $field['dataScopeName'],
                'label' => $field['showTitle'] ? __($field['label']) : '',
                'sortOrder' => $sortOrder + 1,
                'validation' => [],
            ];

            if (isset($field['config'])) {
                $formField['config'] = array_merge($formField['config'], $field['config']);
            }

            if (isset($field['validation'])) {
                $formField['validation'] = array_merge($formField['validation'], $field['validation']);
            }

            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                ['children']['shippingAddress']['children']['custom-checkout-form-container']
                ['children']['custom-checkout-form-fieldset']['children'][$field['dataScopeName']] = $formField;
        }

        return $jsLayout;
    }

    private function applyLengthLimitToFields(array $fields): array
    {
        foreach ($fields as $key => $field) {
            $allowedLength = $this->config->getAllowedLength($field['dataScopeName']);
            if ($allowedLength === Config::LIMIT_NOT_SET) {
                continue;
            }
            $fields[$key]['config']['maxlength'] = $allowedLength;
            $fields[$key]['validation']['max_text_length'] = $allowedLength;
        }

        return $fields;
    }
}
