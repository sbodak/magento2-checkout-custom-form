<?php

namespace Bodak\CheckoutCustomForm\Plugin\Checkout\LayoutProcessor;

use Magento\Framework\Stdlib\ArrayManager;

class CustomOptions
{
    protected $arrayManager;

    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {

        $checkoutCustomSelectFieldOptionsPath = $this->arrayManager->findPath(
                'checkout_custom_select_field',
                $jsLayout
            ).'/options';

        return $this->arrayManager->set(
            $checkoutCustomSelectFieldOptionsPath,
            $jsLayout,
            $this->getCustomOptions()
        );
    }

    protected function getCustomOptions(): array
    {
        // TODO:: get it from somewhere
        $options = [
            [
                'value' => '1',
                'label' => 'Opt 1',
            ],
            [
                'value' => '2',
                'label' => 'Opt 2',
            ],
            [
                'value' => '3',
                'label' => 'Opt 3',
            ],
            [
                'value' => '4',
                'label' => 'Opt 4',
            ],
        ];

        return $options;
    }
}