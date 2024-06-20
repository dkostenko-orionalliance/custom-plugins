define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'uiRegistry',
    'mage/template',
    'underscore',
    'text!Webkul_MpAdvancedBookingSystem/template/form/element/language-seniority.html'
], function ($, Abstract, registry, mageTemplate, _, template) {
    'use strict';

    return Abstract.extend({
        defaults: {
            template: 'Webkul_MpAdvancedBookingSystem/form/element/language-seniority',
            languages: window.languagesOptions || [],
            seniorities: window.senioritiesOptions || [],
            pairs: [],
            listens: {
                value: 'onValueChanged'
            },
            modules: {
                source: '${ $.provider }'
            }
        },

        initialize: function () {
            this._super();
            this.pairs = ko.observableArray(this.getInitialPairs());
            return this;
        },

        onValueChanged: function (value) {
            this.pairs(this.parseValue(value));
        },

        getInitialPairs: function () {
            return this.parseValue(this.value());
        },

        parseValue: function (value) {
            try {
                return JSON.parse(value) || [];
            } catch (e) {
                return [];
            }
        },

        addPair: function () {
            this.pairs.push({ language: '', seniority: '' });
            this.updateValue();
        },

        removePair: function (pair) {
            this.pairs.remove(pair);
            this.updateValue();
        },

        updateValue: function () {
            this.value(JSON.stringify(this.pairs()));
        }
    });
});
