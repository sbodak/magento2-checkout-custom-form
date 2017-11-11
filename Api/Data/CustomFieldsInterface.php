<?php
/**
 * Checkout custom fields interface
 *
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Api\Data;

/**
 * Interface CustomFieldsInterface
 *
 * @category Api/Data/Interface
 * @package  Bodak\CheckoutCustomForm\Api\Data
 */
interface CustomFieldsInterface
{
    const CHECKOUT_BUYER_NAME = 'checkout_buyer_name';
    const CHECKOUT_BUYER_EMAIL = 'checkout_buyer_email';
    const CHECKOUT_PURCHASE_ORDER_NO = 'checkout_purchase_order_no';
    const CHECKOUT_GOODS_MARK = 'checkout_goods_mark';
    const CHECKOUT_COMMENT = 'checkout_comment';

    /**
     * Get checkout buyer name
     *
     * @return string|null
     */
    public function getCheckoutBuyerName();

    /**
     * Get checkout buyer email
     *
     * @return string|null
     */
    public function getCheckoutBuyerEmail();

    /**
     * Get checkout purchase order number
     *
     * @return string|null
     */
    public function getCheckoutPurchaseOrderNo();

    /**
     * Get checkout goods mark
     *
     * @return string|null
     */
    public function getCheckoutGoodsMark();

    /**
     * Get checkout comment
     *
     * @return string|null
     */
    public function getCheckoutComment();

    /**
     * Set checkout buyer name
     *
     * @param string|null $checkoutBuyerName Buyer name
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutBuyerName(string $checkoutBuyerName = null);

    /**
     * Set checkout buyer email
     *
     * @param string|null $checkoutBuyerEmail Buyer email
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutBuyerEmail(string $checkoutBuyerEmail = null);

    /**
     * Set checkout purchase order number
     *
     * @param string|null $checkoutPurchaseOrderNo Purchase order number
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutPurchaseOrderNo(string $checkoutPurchaseOrderNo = null);

    /**
     * Set checkout goods mark
     *
     * @param string|null $checkoutGoodsMark Goods mark
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutGoodsMark(string $checkoutGoodsMark = null);

    /**
     * Set checkout comment
     *
     * @param string|null $comment Comment
     *
     * @return CustomFieldsInterface
     */
    public function setCheckoutComment(string $comment = null);
}
