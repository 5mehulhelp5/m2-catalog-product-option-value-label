<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionValueLabel\Plugin\Catalog\Block\Product\View;

use FeWeDev\Base\Json;
use FeWeDev\Base\Variables;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Options
{
    /** @var Json */
    protected $json;

    /** @var Variables */
    protected $variables;

    public function __construct(Json $json, Variables $variables)
    {
        $this->json = $json;
        $this->variables = $variables;
    }

    public function afterGetJsonConfig(\Magento\Catalog\Block\Product\View\Options $subject, string $result): string
    {
        $config = $this->json->decode($result);

        foreach ($subject->getOptions() as $option) {
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

        return $this->json->encode($config);
    }
}
