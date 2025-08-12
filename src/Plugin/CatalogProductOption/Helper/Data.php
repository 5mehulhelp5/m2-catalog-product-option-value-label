<?php /** @noinspection PhpUnusedParameterInspection */

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionValueLabel\Plugin\CatalogProductOption\Helper;

use FeWeDev\Base\Variables;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Data
{
    /** @var Variables */
    protected $variables;

    public function __construct(Variables $variables)
    {
        $this->variables = $variables;
    }

    public function afterGetOptionsJsonConfigData(
        \Infrangible\CatalogProductOption\Helper\Data $subject,
        array $config,
        array $options
    ): array {
        foreach ($options as $option) {
            /** @var \Magento\Catalog\Model\Product\Option $option */
            $optionValues = $option->getValues();

            if ($optionValues) {
                /** @var \Magento\Catalog\Model\Product\Option\Value $optionValue */
                foreach ($option->getValues() as $optionValue) {
                    $prefix = $optionValue->getData('prefix');

                    if (! $this->variables->isEmpty($prefix)) {
                        $config[ $option->getId() ][ $optionValue->getOptionTypeId() ][ 'prefix' ] = $prefix;
                    }

                    $suffix = $optionValue->getData('suffix');

                    if (! $this->variables->isEmpty($suffix)) {
                        $config[ $option->getId() ][ $optionValue->getOptionTypeId() ][ 'suffix' ] = $suffix;
                    }
                }
            }
        }

        return $config;
    }
}