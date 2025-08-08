<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionValueLabel\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @throws \Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        $connection = $setup->getConnection();

        $this->addOptionValueLabelTable($connection);

        $setup->endSetup();
    }

    /**
     * @throws \Exception
     */
    private function addOptionValueLabelTable(AdapterInterface $connection): void
    {
        $optionValueLabelTableName = $connection->getTableName('catalog_product_option_type_label');

        if (! $connection->isTableExists($optionValueLabelTableName)) {
            $optionValueTableName = $connection->getTableName('catalog_product_option_type_value');
            $storeTableName = $connection->getTableName('store');

            $optionValueLabelTable = $connection->newTable($optionValueLabelTableName);

            $optionValueLabelTable->addColumn(
                'id',
                Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true]
            );
            $optionValueLabelTable->addColumn(
                'option_type_id',
                Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false]
            );
            $optionValueLabelTable->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false]
            );
            $optionValueLabelTable->addColumn(
                'prefix',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true]
            );
            $optionValueLabelTable->addColumn(
                'suffix',
                Table::TYPE_TEXT,
                10000,
                ['nullable' => true]
            );

            $optionValueLabelTable->addForeignKey(
                $connection->getForeignKeyName(
                    $optionValueLabelTableName,
                    'option_type_id',
                    $optionValueTableName,
                    'option_type_id'
                ),
                'option_type_id',
                $optionValueTableName,
                'option_type_id',
                Table::ACTION_CASCADE
            );

            $optionValueLabelTable->addForeignKey(
                $connection->getForeignKeyName(
                    $optionValueLabelTableName,
                    'store_id',
                    $storeTableName,
                    'store_id'
                ),
                'store_id',
                $storeTableName,
                'store_id',
                Table::ACTION_CASCADE
            );

            $connection->createTable($optionValueLabelTable);
        }
    }
}
