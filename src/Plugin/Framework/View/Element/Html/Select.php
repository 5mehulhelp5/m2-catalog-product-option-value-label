<?php /** @noinspection PhpUnusedParameterInspection */

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionValueLabel\Plugin\Framework\View\Element\Html;

use FeWeDev\Base\Variables;
use Infrangible\Core\Helper\Registry;
use Magento\Catalog\Model\Product\Option;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Select
{
    /** @var Registry */
    protected $registryHelper;

    /** @var Variables */
    protected $variables;

    public function __construct(Registry $registryHelper, Variables $variables)
    {
        $this->registryHelper = $registryHelper;
        $this->variables = $variables;
    }

    public function afterGetOptions(\Magento\Framework\View\Element\Html\Select $subject, array $options): array
    {
        $option = $this->registryHelper->registry('current_option');

        if ($option instanceof Option) {
            $optionValues = $option->getValues();

            if ($optionValues !== null && ! $subject->getData('value_labels_processed')) {
                foreach ($options as &$option) {
                    $optionValueId = $option[ 'value' ];

                    /** @var \Magento\Catalog\Model\Product\Option\Value $optionValue */
                    foreach ($optionValues as $optionValue) {
                        if ($optionValueId == $optionValue->getOptionTypeId()) {
                            $prefix = $optionValue->getData('prefix');

                            if (! $this->variables->isEmpty($prefix)) {
                                $option[ 'label' ] = trim(
                                    sprintf(
                                        '%s %s',
                                        $prefix,
                                        $option[ 'label' ]
                                    )
                                );
                            }

                            $suffix = $optionValue->getData('suffix');

                            if (! $this->variables->isEmpty($suffix)) {
                                $option[ 'label' ] = trim(
                                    sprintf(
                                        '%s %s',
                                        $option[ 'label' ],
                                        $suffix
                                    )
                                );
                            }
                        }
                    }
                }

                $subject->setData(
                    'value_labels_processed',
                    true
                );
            }
        }

        return $options;
    }
}
