<?php
/**
 * Uninstall checkout custom data
 *
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

/**
 * Class Uninstall
 *
 * @category Uninstall
 * @package  Bodak\CheckoutCustomForm\Setup
 */
class Uninstall implements UninstallInterface
{
    /**
     * SchemaSetupInterface
     *
     * @var SchemaSetupInterface
     */
    protected $setup;

    /**
     * Uninstall data
     *
     * @param SchemaSetupInterface   $setup   SchemaSetupInterface
     * @param ModuleContextInterface $context ModuleContextInterface
     *
     * @return void
     */
    public function uninstall(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $this->setup = $setup->startSetup();
        $this->uninstallQuoteData();
        $this->uninstallSalesData();
        $this->setup = $setup->endSetup();
    }

    /**
     * Uninstall quote custom data
     *
     * @return void
     */
    public function uninstallQuoteData()
    {
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('quote'),
            CustomFieldsInterface::CHECKOUT_BUYER_NAME
        );
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('quote'),
            CustomFieldsInterface::CHECKOUT_BUYER_EMAIL
        );
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('quote'),
            CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO
        );
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('quote'),
            CustomFieldsInterface::CHECKOUT_GOODS_MARK
        );
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('quote'),
            CustomFieldsInterface::CHECKOUT_COMMENT
        );
    }

    /**
     * Uninstall sales custom data
     *
     * @return void
     */
    public function uninstallSalesData()
    {
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('sales_order'),
            CustomFieldsInterface::CHECKOUT_BUYER_NAME
        );
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('sales_order'),
            CustomFieldsInterface::CHECKOUT_BUYER_EMAIL
        );
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('sales_order'),
            CustomFieldsInterface::CHECKOUT_PURCHASE_ORDER_NO
        );
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('sales_order'),
            CustomFieldsInterface::CHECKOUT_GOODS_MARK
        );
        $this->setup->getConnection()->dropColumn(
            $this->setup->getTable('sales_order'),
            CustomFieldsInterface::CHECKOUT_COMMENT
        );
    }
}
