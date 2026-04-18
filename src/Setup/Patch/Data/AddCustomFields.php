<?php

/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Setup\Patch\Data;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;

class AddCustomFields implements DataPatchInterface
{
    private const TABLES = ['quote', 'sales_order'];

    public function __construct(private readonly ModuleDataSetupInterface $moduleDataSetup) {}

    public function apply(): self
    {
        $this->moduleDataSetup->startSetup();
        $connection = $this->moduleDataSetup->getConnection();

        foreach (self::TABLES as $tableName) {
            $table = $this->moduleDataSetup->getTable($tableName);
            foreach (CustomFieldsInterface::ATTRIBUTES as $attribute) {
                if (!$connection->tableColumnExists($table, $attribute)) {
                    $connection->addColumn($table, $attribute, [
                        'type'     => Table::TYPE_TEXT,
                        'length'   => 255,
                        'nullable' => true,
                        'comment'  => $attribute,
                    ]);
                }
            }
        }

        $this->moduleDataSetup->endSetup();

        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
