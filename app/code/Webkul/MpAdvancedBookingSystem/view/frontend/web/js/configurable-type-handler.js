define([
    'jquery',
    'Webkul_Marketplace/catalog/type-events',
    'Webkul_Marketplace/js/advanced-pricing-handler',
    'Webkul_Marketplace/js/options/price-type-handler',
    'collapsible',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'domReady!'
], function ($, productType, advancedPricingHandler, priceTypeHandler) {
    'use strict';

    return {
        $block: null,
        hasVariations: null,
        configurationSectionMessageHandler: (function () {
            var title = $('[data-role="product-create-configuration-info"]'),
                buttons = $('[data-action="product-create-configuration-buttons"]'),
                newText = 'Configurations cannot be created for a standard product with downloadable files.' +
                ' To create configurations, first remove all downloadable files.',
                oldText = title.text();

            return function (change) {
                if (change) {
                    title.text(newText);
                    buttons.hide();
                } else {
                    title.text(oldText);
                    buttons.show();
                }
            };
        }()),

        /**
         * Set element disabled
         * @param {Object} $element - jquery instance element
         * @param {Bool} state
         * @param {Bool} triggerEvent
         * @private
         */
        _setElementDisabled: function ($element, state, triggerEvent) {
            if (!$element.is('[data-locked]')) {
                $element.prop('disabled', state);

                if (triggerEvent) {
                    $element.trigger('change');
                }
            }
        },

        /**
         * Show
         */
        show: function () {
            this.configurationSectionMessageHandler(false);
        },

        /**
         * Hide
         */
        hide: function () {
            this.configurationSectionMessageHandler(true);
        },

        /**
         * Bind all
         */
        bindAll: function () {
            $(document).on('changeConfigurableTypeProduct', function (event, isConfigurable) {
                var productType = "hotelbooking";
                var currentProductType = $(document).find("#product_type_id").val();
                var defaultMarketplaceType = ["simple","virtual","configurable"];
                if ($.inArray(currentProductType, defaultMarketplaceType) !== -1) {
                    productType = "configurable";
                }
                $(document).trigger('setTypeProduct', isConfigurable ? productType : null);
            });
            $(document).on('changeTypeProduct', this._initType.bind(this));
        },

        /**
         * Init type
         * @private
         */
        _initType: function () {
            var suggestContainer = $('#product-template-suggest-container .action-dropdown > .action-toggle');

            if (productType.type.current === 'hotelbooking') {
                suggestContainer.addClass('disabled').prop('disabled', true);
                $('#inventory_qty').prop('disabled', true);
                $('#inventory_stock_availability').removeProp('disabled');
                this._setElementDisabled($('#qty'), true, true);
                this._setElementDisabled($('#quantity_and_stock_status'), false, false);
            } else {
                suggestContainer.removeClass('disabled').removeProp('disabled');
                $('#inventory_qty').removeProp('disabled');
                $('#inventory_stock_availability').prop('disabled', true);
                this._setElementDisabled($('#quantity_and_stock_status'), true, false);
                this._setElementDisabled($('#qty'), false, true);
            }

            if (['simple', 'virtual', 'configurable', 'hotelbooking'].indexOf(productType.type.current) < 0) {
                this.hide();
            } else {
                this.show();
            }
        },

        /**
         * Constructor component
         * @param {Object} data - this backend data
         */
        'Webkul_MpAdvancedBookingSystem/js/configurable-type-handler': function (data) {
            this.$block = $(data.blockId + ' input[name="attributes[]"]');
            this.hasVariations = data.hasVariations;

            advancedPricingHandler.init();
            priceTypeHandler.init();

            this.bindAll();
            this._initType();
        }
    };
});