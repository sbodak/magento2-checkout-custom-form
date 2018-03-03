<?php
/**
 * Checkout custom fields guest repository interface
 *
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Api;

use Magento\Sales\Model\Order;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

/**
 * Interface CustomFieldsGuestRepositoryInterface
 *
 * @category Api/Interface
 * @package  Bodak\CheckoutCustomForm\Api
 */
interface CustomFieldsGuestRepositoryInterface
{
    /**
     * Save checkout custom fields
     *
     * @param string                                                   $cartId       Guest Cart id
     * @param \Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface $customFields Custom fields
     *
     * @return \Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface
     */
    public function saveCustomFields(
        string $cartId,
        CustomFieldsInterface $customFields
    ): CustomFieldsInterface;
}
