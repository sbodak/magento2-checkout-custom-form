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

class Plugin
{
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
                    'description' => 'We will send an order confirmation to this email address'
                ]
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
                'elementTmpl' => 'ui/form/element/textarea',
            ],
            'showTitle' => false,
        ],
    ];

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * LayoutProcessor constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     *
     * @see \Magento\Checkout\Block\Checkout\LayoutProcessor::process
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        $config = explode(',',
            $this->_scopeConfig->getValue('bodak/checkout/enabled_fields',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE));

        foreach ($this->fields as $sortOrder => $field) {
            if ( ! in_array($field['dataScopeName'], $config)) {
                continue;
            }
            if(!isset($field['showTitle'])) $field['showTitle'] = true;

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
                ['children']['shippingAddress']['children']['before-form']['children']['custom-checkout-form-container']
                ['children']['custom-checkout-form-fieldset']['children'][$field['dataScopeName']] = $formField;
        }

        return $jsLayout;
    }
}