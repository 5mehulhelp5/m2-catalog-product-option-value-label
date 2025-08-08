<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionValueLabel\Observer;

use FeWeDev\Base\Arrays;
use FeWeDev\Base\Variables;
use Infrangible\Core\Helper\Database;
use Magento\Catalog\Model\Product\Option\Value;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\Store;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class ModelSaveAfter implements ObserverInterface
{
    /** @var Database */
    protected $databaseHelper;

    /** @var Arrays */
    protected $arrays;

    /** @var Variables */
    protected $variables;

    public function __construct(
        Database $databaseHelper,
        Arrays $arrays,
        Variables $variables
    ) {
        $this->databaseHelper = $databaseHelper;
        $this->arrays = $arrays;
        $this->variables = $variables;
    }

    /**
     * @throws \Exception
     */
    public function execute(Observer $observer): void
    {
        $object = $observer->getData('object');

        if ($object instanceof Value) {
            $optionTypeId = $object->getData('option_type_id');
            $storeId = $object->getData('store_id');
            $prefix = $object->getData('prefix');
            $suffix = $object->getData('suffix');

            if ($this->variables->isEmpty($prefix)) {
                $prefix = null;
            }

            if ($this->variables->isEmpty($suffix)) {
                $suffix = null;
            }

            $isDeleteRecord = $storeId > Store::DEFAULT_STORE_ID && $prefix === null && $suffix === null;

            $dbAdapter = $object->getResource()->getConnection();

            $tableName = $dbAdapter->getTableName('catalog_product_option_type_label');

            $query = $this->databaseHelper->select(
                $tableName,
                ['id', 'prefix', 'suffix']
            );

            $query->where(
                'option_type_id = ?',
                $optionTypeId
            );

            $query->where(
                'store_id  = ?',
                $storeId
            );

            $queryResult = $this->databaseHelper->fetchRow(
                $query,
                $dbAdapter
            );

            if ($queryResult === null) {
                if (! $isDeleteRecord) {
                    $this->databaseHelper->createTableData(
                        $dbAdapter,
                        $tableName,
                        [
                            'option_type_id' => $optionTypeId,
                            'store_id'       => $storeId,
                            'prefix'         => $prefix,
                            'suffix'         => $suffix
                        ]
                    );
                }
            } else {
                $currentPrefix = $this->arrays->getValue(
                    $queryResult,
                    'prefix'
                );

                $currentSuffix = $this->arrays->getValue(
                    $queryResult,
                    'suffix'
                );

                $id = $this->arrays->getValue(
                    $queryResult,
                    'id'
                );

                if ($isDeleteRecord) {
                    $this->databaseHelper->deleteTableData(
                        $dbAdapter,
                        $tableName,
                        sprintf(
                            'id = %d',
                            $id
                        )
                    );
                } elseif ($currentPrefix != $prefix || $currentSuffix != $suffix) {
                    $this->databaseHelper->updateTableData(
                        $dbAdapter,
                        $tableName,
                        [
                            'prefix' => $prefix,
                            'suffix' => $suffix
                        ],
                        sprintf(
                            'id = %d',
                            $id
                        )
                    );
                }
            }
        }
    }
}
