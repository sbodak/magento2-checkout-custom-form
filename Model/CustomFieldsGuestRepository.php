<?php
/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Model;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Model\Order;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Bodak\CheckoutCustomForm\Api\CustomFieldsGuestRepositoryInterface;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

/**
 * Class CustomFieldsGuestRepository
 *
 * @category Model/Repository
 * @package  Bodak\CheckoutCustomForm\Model
 */
class CustomFieldsGuestRepository implements CustomFieldsGuestRepositoryInterface
{
    /**
     * @var QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

    /**
     * @var CustomFieldsRepositoryInterface
     */
    protected $customFieldsRepository;

    /**
     * @param QuoteIdMaskFactory              $quoteIdMaskFactory
     * @param CustomFieldsRepositoryInterface $customFieldsRepository
     */
    public function __construct(
        QuoteIdMaskFactory $quoteIdMaskFactory,
        CustomFieldsRepositoryInterface $customFieldsRepository
    ) {
        $this->quoteIdMaskFactory     = $quoteIdMaskFactory;
        $this->customFieldsRepository = $customFieldsRepository;
    }

    /**
     * @param string                $cartId
     * @param CustomFieldsInterface $customFields
     * @return CustomFieldsInterface
     */
    public function saveCustomFields(
        string $cartId,
        CustomFieldsInterface $customFields
    ): CustomFieldsInterface {
        $quoteIdMaskFactory = $this->quoteIdMaskFactory->create();
        $quoteIdMask = $quoteIdMaskFactory->getCollection()->addFieldToFilter( 'masked_id', $cartId); // or just $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->customFieldsRepository->saveCustomFields($quoteIdMask->getQuoteId(), $customFields);
    }
}
