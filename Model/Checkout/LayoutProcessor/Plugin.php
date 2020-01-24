<?php

/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Konrad Langenberg <k.langenberg@imi.de>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Model\Checkout\LayoutProcessor;

use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;
use Bodak\CheckoutCustomForm\Helper\Config;

class Plugin
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $fields = [
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

    /**
     * LayoutProcessor constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     *
     * @return array
     * @see \Magento\Checkout\Block\Checkout\LayoutProcessor::process
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        $config = $this->config->getEnabledFields();

        $this->applyLengthLimitToFields();

        foreach ($this->fields as $sortOrder => $field) {
            if (!in_array($field['dataScopeName'], $config)) {
                continue;
            }
            if (!isset($field['showTitle'])) {
                $field['showTitle'] = true;
            }

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

    private function applyLengthLimitToFields()
    {
        foreach ($this->fields as $key => $field) {
            $fieldName = $field['dataScopeName'];
            $allowedLength = $this->config->getAllowedLength($fieldName);
            if ($allowedLength === Config::LIMIT_NOT_SET) {
                continue;
            }
            $this->fields[$key]['config']['maxlength'] = $allowedLength;
            $this->fields[$key]['validation']['max_text_length'] = $allowedLength;
        }
    }
}