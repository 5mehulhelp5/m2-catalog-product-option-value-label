<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionValueLabel\Helper;

use Magento\Store\Model\Store;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Data
{
    public function addValueLabelToResult(
        \Magento\Catalog\Model\ResourceModel\Product\Option\Value\Collection $collection,
        $storeId
    ): void {
        if ($collection->isLoaded() || $collection->hasFlag('label')) {
            return;
        }

        $dbAdapter = $collection->getConnection();

        $tableName = $dbAdapter->getTableName('catalog_product_option_type_label');

        $select = $collection->getSelect();

        $select->joinLeft(
            ['default_option_value_label' => $tableName],
            sprintf(
                'default_option_value_label.option_type_id = main_table.option_type_id AND %s',
                $dbAdapter->quoteInto(
                    'default_option_value_label.store_id = ?',
                    Store::DEFAULT_STORE_ID
                )
            ),
            ['default_prefix' => 'prefix', 'default_suffix' => 'suffix']
        );

        $prefixExpr = $dbAdapter->getCheckSql(
            'store_option_value_label.prefix IS NULL',
            'default_option_value_label.prefix',
            'store_option_value_label.prefix'
        );

        $suffixExpr = $dbAdapter->getCheckSql(
            'store_option_value_label.suffix IS NULL',
            'default_option_value_label.suffix',
            'store_option_value_label.suffix'
        );

        $select->joinLeft(
            ['store_option_value_label' => $tableName],
            sprintf(
                'store_option_value_label.option_type_id = main_table.option_type_id AND %s',
                $dbAdapter->quoteInto(
                    'store_option_value_label.store_id = ?',
                    $storeId
                )
            ),
            [
                'store_prefix' => 'prefix',
                'store_suffix' => 'suffix',
                'prefix'       => $prefixExpr,
                'suffix'       => $suffixExpr
            ]
        );

        $collection->setFlag(
            'label',
            true
        );
    }
}
