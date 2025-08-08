<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionValueLabel\Plugin\Catalog\Block\Product\View\Options\Type;

use Infrangible\Core\Helper\Registry;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Select
{
    /** @var Registry */
    protected $registryHelper;

    public function __construct(Registry $registryHelper)
    {
        $this->registryHelper = $registryHelper;
    }

    public function aroundGetValuesHtml(
        \Magento\Catalog\Block\Product\View\Options\Type\Select $subject,
        callable $proceed
    ): string {
        $option = $subject->getOption();

        $this->registryHelper->register(
            'current_option',
            $option,
            true
        );

        $result = $proceed();

        $this->registryHelper->unregister('current_option');

        return $result;
    }
}
