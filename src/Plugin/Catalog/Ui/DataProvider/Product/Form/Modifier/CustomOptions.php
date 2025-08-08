<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionValueLabel\Plugin\Catalog\Ui\DataProvider\Product\Form\Modifier;

use FeWeDev\Base\Arrays;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class CustomOptions
{
    public const FIELD_PREFIX_NAME = 'prefix';
    public const FIELD_SUFFIX_NAME = 'suffix';

    /** @var Arrays */
    protected $arrays;

    public function __construct(Arrays $arrays)
    {
        $this->arrays = $arrays;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterModifyMeta(
        \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions $subject,
        array $meta
    ): array {
        $meta = $this->arrays->addDeepValue(
            $meta,
            [
                \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions::GROUP_CUSTOM_OPTIONS_NAME,
                'children',
                \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions::GRID_OPTIONS_NAME,
                'children',
                'record',
                'children',
                \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions::CONTAINER_OPTION,
                'children',
                \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions::GRID_TYPE_SELECT_NAME,
                'children',
                'record',
                'children',
                static::FIELD_PREFIX_NAME
            ],
            $this->getPrefixFieldConfig(150)
        );

        return $this->arrays->addDeepValue(
            $meta,
            [
                \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions::GROUP_CUSTOM_OPTIONS_NAME,
                'children',
                \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions::GRID_OPTIONS_NAME,
                'children',
                'record',
                'children',
                \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions::CONTAINER_OPTION,
                'children',
                \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions::GRID_TYPE_SELECT_NAME,
                'children',
                'record',
                'children',
                static::FIELD_SUFFIX_NAME
            ],
            $this->getSuffixFieldConfig(151)
        );
    }

    protected function getPrefixFieldConfig(int $sortOrder): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label'         => __('Prefix'),
                        'componentType' => Field::NAME,
                        'formElement'   => Input::NAME,
                        'dataScope'     => static::FIELD_PREFIX_NAME,
                        'dataType'      => Text::NAME,
                        'sortOrder'     => $sortOrder
                    ]
                ]
            ]
        ];
    }

    protected function getSuffixFieldConfig(int $sortOrder): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label'         => __('Suffix'),
                        'componentType' => Field::NAME,
                        'formElement'   => Input::NAME,
                        'dataScope'     => static::FIELD_SUFFIX_NAME,
                        'dataType'      => Text::NAME,
                        'sortOrder'     => $sortOrder
                    ]
                ]
            ]
        ];
    }
}
