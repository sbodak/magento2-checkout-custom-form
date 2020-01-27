<?php
/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Model;

use Bodak\CheckoutCustomForm\Model\CustomFields\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Model\Order;
use Bodak\CheckoutCustomForm\Api\CustomFieldsRepositoryInterface;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

/**
 * Class CustomFieldsRepository
 *
 * @category Model/Repository
 * @package  Bodak\CheckoutCustomForm\Model
 */
class CustomFieldsRepository implements CustomFieldsRepositoryInterface
{
    /**
     * Quote repository.
     *
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * ScopeConfigInterface
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * CustomFieldsInterface
     *
     * @var CustomFieldsInterface
     */
    protected $customFields;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * CustomFieldsRepository constructor.
     *
     * @param CartRepositoryInterface $cartRepository CartRepositoryInterface
     * @param ScopeConfigInterface $scopeConfig ScopeConfigInterface
     * @param CustomFieldsInterface $customFields CustomFieldsInterface
     * @param Validator $validator
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        ScopeConfigInterface $scopeConfig,
        CustomFieldsInterface $customFields,
        Validator $validator
    ) {
        $this->cartRepository = $cartRepository;
        $this->scopeConfig    = $scopeConfig;
        $this->customFields   = $customFields;
        $this->validator = $validator;
    }

    /**
     * Save checkout custom fields
     *
     * @param int $cartId Cart id
     * @param \Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface $customFields Custom fields
     *
     * @return \Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface|string
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws \Zend_Validate_Exception
     * @throws LocalizedException
     */
    public function saveCustomFields(
        int $cartId,
        CustomFieldsInterface $customFields
    ): CustomFieldsInterface {
        $cart = $this->cartRepository->getActive($cartId);
        if (!$cart->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 is empty', $cartId));
        }

        if (!$this->validator->isValid($customFields)) {
            throw new LocalizedException(__('Custom fields contain invalid values.'));
        }

        try {
            $cart->setData(
                CustomFieldsInterface::CHECKOUT_BUYER_NAME,
                $customFields->getCheckoutBuyerName()
            );
            $cart->setData(
                CustomFieldsInterface::CHECKOUT_BUYER_EMAIL,
                $customFields->getCheckoutBuyerEmail()
            );
            $cart->setData(
                CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO,
                $customFields->getCheckoutPurchaseOrderNo()
            );
            $cart->setData(
                CustomFieldsInterface::CHECKOUT_GOODS_MARK,
                $customFields->getCheckoutGoodsMark()
            );
            $cart->setData(
                CustomFieldsInterface::CHECKOUT_COMMENT,
                $customFields->getCheckoutComment()
            );

            $this->cartRepository->save($cart);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Custom order data could not be saved!'));
        }

        return $customFields;
    }

    /**
     * Get checkout custom fields by given order id
     *
     * @param Order $order Order
     *
     * @return CustomFieldsInterface
     * @throws NoSuchEntityException
     */
    public function getCustomFields(Order $order): CustomFieldsInterface
    {
        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order %1 does not exist', $order));
        }

        $this->customFields->setCheckoutBuyerName(
            $order->getData(CustomFieldsInterface::CHECKOUT_BUYER_NAME)
        );
        $this->customFields->setCheckoutBuyerEmail(
            $order->getData(CustomFieldsInterface::CHECKOUT_BUYER_EMAIL)
        );
        $this->customFields->setCheckoutPurchaseOrderNo(
            $order->getData(CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO)
        );
        $this->customFields->setCheckoutGoodsMark(
            $order->getData(CustomFieldsInterface::CHECKOUT_GOODS_MARK)
        );
        $this->customFields->setCheckoutComment(
            $order->getData(CustomFieldsInterface::CHECKOUT_COMMENT)
        );

        return $this->customFields;
    }
}
