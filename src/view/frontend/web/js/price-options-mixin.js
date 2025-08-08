/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

define([
    'jquery',
    'priceUtils'
], function ($, utils) {
    'use strict';

    return function (widget) {
        $.widget('mage.priceBox', widget, {
            _applyOptionNodeFix: function applyOptionNodeFix(options) {
                this._super(options);

                var config = this.options;

                options.filter('select').each(function (index, element) {
                    var $element = $(element),
                        optionId = utils.findOptionId($element),
                        optionConfig = config.optionConfig && config.optionConfig[optionId];

                    $element.find('option').each(function (idx, option) {
                        var $option,
                            optionValue,
                            prefix,
                            suffix;

                        $option = $(option);
                        optionValue = $option.val();

                        if (! optionValue && optionValue !== 0) {
                            return;
                        }

                        prefix = optionConfig[optionValue] ? optionConfig[optionValue].prefix : null;
                        if (prefix) {
                            $option.text(prefix + ' ' + $option.text());
                        }

                        suffix = optionConfig[optionValue] ? optionConfig[optionValue].suffix : null;
                        if (suffix) {
                            $option.text($option.text() + ' ' + suffix);
                        }
                    });
                });
            }
        });

        return $.mage.priceBox;
    };
});
