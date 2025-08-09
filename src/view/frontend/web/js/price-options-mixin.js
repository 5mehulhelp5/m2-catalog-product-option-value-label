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
        $.widget('mage.priceOptions', widget, {
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

                options.filter(':radio,:checkbox').each(function(index, element) {
                    var $element = $(element),
                        optionId = utils.findOptionId($element),
                        optionValue = $element.val(),
                        optionConfig = config.optionConfig && config.optionConfig[optionId],
                        $optionTitle,
                        $optionPriceNotice,
                        prefix,
                        $optionPrefix,
                        suffix,
                        $optionSuffix;

                    if (! optionValue && optionValue !== 0) {
                        return;
                    }

                    $optionTitle = $element.parent().find('label.label > span:first');
                    $optionPriceNotice = $element.parent().find('label.label > span.price-notice');

                    prefix = optionConfig[optionValue] ? optionConfig[optionValue].prefix : null;
                    if (prefix) {
                        $optionPrefix = $('<span>', {class: 'prefix'});
                        $optionPrefix.text(prefix);

                        $optionTitle.before($optionPrefix);
                    }

                    suffix = optionConfig[optionValue] ? optionConfig[optionValue].suffix : null;
                    if (suffix) {
                        $optionSuffix = $('<span>', {class: 'suffix'});
                        $optionSuffix.text(suffix);

                        if ($optionPriceNotice.length > 0) {
                            $optionPriceNotice.after($optionSuffix);
                        } else {
                            $optionTitle.after($optionSuffix);
                        }
                    }
                });
            }
        });

        return $.mage.priceOptions;
    };
});
