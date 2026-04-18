<?php
/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Model;

use Bodak\CheckoutCustomForm\Api\CustomFieldsRepositoryInterface;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;
use Bodak\CheckoutCustomForm\Model\CustomFields\Validator;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class CustomFieldsRepository implements CustomFieldsRepositoryInterface
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly CustomFieldsInterface $customFields,
        private readonly Validator $validator,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function saveCustomFields(int $cartId, CustomFieldsInterface $customFields): CustomFieldsInterface
    {
        $cart = $this->cartRepository->getActive($cartId);
        if (!$cart->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 is empty', $cartId));
        }

        if (!$this->validator->isValid($customFields)) {
            throw new LocalizedException(__('Custom fields contain invalid values.'));
        }

        try {
            foreach ($this->extractFieldValues($customFields) as $attribute => $value) {
                $cart->setData($attribute, $value);
            }
            $this->cartRepository->save($cart);
        } catch (\Exception $e) {
            $this->logger->error('Could not save custom order data.', ['exception' => $e]);
            throw new CouldNotSaveException(__('Custom order data could not be saved!'), $e);
        }

        return $customFields;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getCustomFields(Order $order): CustomFieldsInterface
    {
        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order %1 does not exist', $order));
        }

        $this->customFields->setCheckoutBuyerName($order->getData(CustomFieldsInterface::CHECKOUT_BUYER_NAME));
        $this->customFields->setCheckoutBuyerEmail($order->getData(CustomFieldsInterface::CHECKOUT_BUYER_EMAIL));
        $this->customFields->setCheckoutPurchaseOrderNo($order->getData(CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO));
        $this->customFields->setCheckoutGoodsMark($order->getData(CustomFieldsInterface::CHECKOUT_GOODS_MARK));
        $this->customFields->setCheckoutComment($order->getData(CustomFieldsInterface::CHECKOUT_COMMENT));

        return $this->customFields;
    }

    /**
     * @return array<string, string|null>
     */
    private function extractFieldValues(CustomFieldsInterface $customFields): array
    {
        return [
            CustomFieldsInterface::CHECKOUT_BUYER_NAME        => $customFields->getCheckoutBuyerName(),
            CustomFieldsInterface::CHECKOUT_BUYER_EMAIL       => $customFields->getCheckoutBuyerEmail(),
            CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO => $customFields->getCheckoutPurchaseOrderNo(),
            CustomFieldsInterface::CHECKOUT_GOODS_MARK        => $customFields->getCheckoutGoodsMark(),
            CustomFieldsInterface::CHECKOUT_COMMENT           => $customFields->getCheckoutComment(),
        ];
    }
}

