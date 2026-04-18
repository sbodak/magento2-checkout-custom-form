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

class Uninstall implements UninstallInterface
{
    private const TABLES = ['quote', 'sales_order'];

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();
        $connection = $setup->getConnection();

        foreach (self::TABLES as $tableName) {
            $table = $setup->getTable($tableName);
            foreach (CustomFieldsInterface::ATTRIBUTES as $attribute) {
                if ($connection->tableColumnExists($table, $attribute)) {
                    $connection->dropColumn($table, $attribute);
                }
            }
        }

        $setup->endSetup();
    }
}
