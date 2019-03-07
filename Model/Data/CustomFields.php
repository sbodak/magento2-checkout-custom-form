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

/**
 * Class CustomFields
 *
 * @category Model/Data
 * @package  Bodak\CheckoutCustomForm\Model\Data
 */
class CustomFields extends AbstractExtensibleObject implements CustomFieldsInterface
{

    /**
     * @var null|array
     */
    private $enabledFields = null;

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
     * Get checkout buyer name
     *
     * @return string|null
     */
    public function getCheckoutBuyerName()
    {
        return $this->_get(self::CHECKOUT_BUYER_NAME);
    }

    /**
     * Get checkout buyer email
     *
     * @return string|null
     */
    public function getCheckoutBuyerEmail()
    {
        return $this->_get(self::CHECKOUT_BUYER_EMAIL);
    }

    /**
     * Get checkout purchase order number
     *
     * @return string|null
     */
    public function getCheckoutPurchaseOrderNo()
    {
        return $this->_get(self::CHECKOUT_PURCHASE_ORDER_NO);
    }

    /**
     * Get checkout goods mark
     *
     * @return string|null
     */
    public function getCheckoutGoodsMark()
    {
        return $this->_get(self::CHECKOUT_GOODS_MARK);
    }

    /**
     * Get checkout comment
     *
     * @return string|null
     */
    public function getCheckoutComment()
    {
        return $this->_get(self::CHECKOUT_COMMENT);
    }

    /**
     * Set checkout buyer name
     *
     * @param string|null $checkoutBuyerName Buyer name
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutBuyerName(string $checkoutBuyerName = null)
    {
        return $this->setData(self::CHECKOUT_BUYER_NAME, $checkoutBuyerName);
    }

    /**
     * Set checkout buyer email
     *
     * @param string|null $checkoutBuyerEmail Buyer email
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutBuyerEmail(string $checkoutBuyerEmail = null)
    {
        return $this->setData(self::CHECKOUT_BUYER_EMAIL, $checkoutBuyerEmail);
    }

    /**
     * Set checkout purchase order number
     *
     * @param string|null $checkoutPurchaseOrderNo Purchase order number
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutPurchaseOrderNo(string $checkoutPurchaseOrderNo = null)
    {
        return $this->setData(self::CHECKOUT_PURCHASE_ORDER_NO, $checkoutPurchaseOrderNo);
    }

    /**
     * Set checkout goods mark
     *
     * @param string|null $checkoutGoodsMark Goods mark
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutGoodsMark(string $checkoutGoodsMark = null)
    {
        return $this->setData(self::CHECKOUT_GOODS_MARK, $checkoutGoodsMark);
    }

    /**
     * Set checkout comment
     *
     * @param string|null $comment Comment
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutComment(string $comment = null)
    {
        return $this->setData(self::CHECKOUT_COMMENT, $comment);
    }

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    public function isFieldEnabled(string $fieldName): bool
    {
        if($this->enabledFields === null) {
            $this->enabledFields = explode(',', $this->_scopeConfig->getValue('bodak/checkout/enabled_fields', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        }

        return in_array($fieldName, $this->enabledFields);
    }

    /**
     * @return bool
     */
    public function isCheckoutBuyerNameEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_BUYER_NAME);
    }

    /**
     * @return bool
     */
    public function isCheckoutBuyerEmailEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_BUYER_EMAIL);
    }

    /**
     * @return bool
     */
    public function isCheckoutPurchaseOrderNoEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_PURCHASE_ORDER_NO);
    }

    /**
     * @return bool
     */
    public function isCheckoutGoodsMarkEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_GOODS_MARK);
    }

    /**
     * @return bool
     */
    public function isCheckoutCommentEnabled(): bool
    {
        return $this->isFieldEnabled(self::CHECKOUT_COMMENT);
    }


}
