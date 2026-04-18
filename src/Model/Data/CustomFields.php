<?php

/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;
use Bodak\CheckoutCustomForm\Helper\Config;

/**
 * Class CustomFields
 *
 * @category Model/Data
 * @package  Bodak\CheckoutCustomForm\Model\Data
 */
class CustomFields extends AbstractExtensibleObject implements CustomFieldsInterface
{
    /** @var string[]|null */
    private ?array $enabledFields = null;

    public function __construct(
        private readonly Config $config,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
    ) {
        parent::__construct($extensionFactory, $customAttributeFactory);
    }

    public function getCheckoutBuyerName(): ?string
    {
        return $this->_get(self::CHECKOUT_BUYER_NAME);
    }

    public function getCheckoutBuyerEmail(): ?string
    {
        return $this->_get(self::CHECKOUT_BUYER_EMAIL);
    }

    public function getCheckoutPurchaseOrderNo(): ?string
    {
        return $this->_get(self::CHECKOUT_PURCHASE_ORDER_NO);
    }

    public function getCheckoutGoodsMark(): ?string
    {
        return $this->_get(self::CHECKOUT_GOODS_MARK);
    }

    public function getCheckoutComment(): ?string
    {
        return $this->_get(self::CHECKOUT_COMMENT);
    }

    public function setCheckoutBuyerName(?string $checkoutBuyerName = null): CustomFieldsInterface
    {
        return $this->setData(self::CHECKOUT_BUYER_NAME, $checkoutBuyerName);
    }

    public function setCheckoutBuyerEmail(?string $checkoutBuyerEmail = null): CustomFieldsInterface
    {
        return $this->setData(self::CHECKOUT_BUYER_EMAIL, $checkoutBuyerEmail);
    }

    public function setCheckoutPurchaseOrderNo(?string $checkoutPurchaseOrderNo = null): CustomFieldsInterface
    {
        return $this->setData(self::CHECKOUT_PURCHASE_ORDER_NO, $checkoutPurchaseOrderNo);
    }

    public function setCheckoutGoodsMark(?string $checkoutGoodsMark = null): CustomFieldsInterface
    {
        return $this->setData(self::CHECKOUT_GOODS_MARK, $checkoutGoodsMark);
    }

    public function setCheckoutComment(?string $comment = null): CustomFieldsInterface
    {
        return $this->setData(self::CHECKOUT_COMMENT, $comment);
    }

    public function isFieldEnabled(string $fieldName): bool
    {
        if ($this->enabledFields === null) {
            $this->enabledFields = $this->config->getEnabledFields();
        }

        return in_array($fieldName, $this->enabledFields, true);
    }

    public function isCheckoutBuyerNameEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_BUYER_NAME);
    }

    public function isCheckoutBuyerEmailEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_BUYER_EMAIL);
    }

    public function isCheckoutPurchaseOrderNoEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_PURCHASE_ORDER_NO);
    }

    public function isCheckoutGoodsMarkEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_GOODS_MARK);
    }

    public function isCheckoutCommentEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_COMMENT);
    }
}
