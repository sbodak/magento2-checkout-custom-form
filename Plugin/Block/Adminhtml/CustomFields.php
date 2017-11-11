<?php
/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Plugin\Block\Adminhtml;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Block\Adminhtml\Order\View\Info;
use Bodak\CheckoutCustomForm\Api\CustomFieldsRepositoryInterface;

/**
 * Class CustomFieldsRepository
 *
 * @category Adminhtml/Plugin
 * @package  Bodak\CheckoutCustomForm\Plugin
 */
class CustomFields
{
    /**
     * CustomFieldsRepositoryInterface
     *
     * @var CustomFieldsRepositoryInterface
     */
    protected $customFieldsRepository;

    /**
     * CustomFields constructor.
     *
     * @param CustomFieldsRepositoryInterface $customFieldsRepository Repository Interface
     */
    public function __construct(CustomFieldsRepositoryInterface $customFieldsRepository)
    {
        $this->customFieldsRepository = $customFieldsRepository;
    }

    /**
     * Modify after to html.
     *
     * @param Info   $subject Info
     * @param string $result  Result
     *
     * @return string
     * @throws LocalizedException
     */
    public function afterToHtml(Info $subject, $result) {
        $block = $subject->getLayout()->getBlock('order_custom_fields');
        if ($block !== false) {
            $block->setOrderCustomFields(
                $this->customFieldsRepository->getCustomFields($subject->getOrder())
            );
            $result = $result . $block->toHtml();
        }

        return $result;
    }
}
