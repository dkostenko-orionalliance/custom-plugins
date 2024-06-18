define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'uiRegistry',
    'mage/template',
    'underscore',
    'knockout',
    'text!Webkul_MpAdvancedBookingSystem/template/form/element/language-seniority.html'
], function ($, Abstract, registry, mageTemplate, _, ko, template) {
    'use strict';

    return Abstract.extend({
        defaults: {
            template: 'Webkul_MpAdvancedBookingSystem/form/element/language-seniority',
            languages: window.languagesOptions || [],
            seniorities: window.senioritiesOptions || [],
            pairs: ko.observableArray([]),
            listens: {
                value: 'onValueChanged'
            },
            modules: {
                source: '${ $.provider }'
            }
        },

        initialize: function () {
            this._super();
            console.log('Initializing language-seniority component');
            console.log('Languages options:', this.languages);
            console.log('Seniorities options:', this.seniorities);

            var initialPairs = this.getInitialPairs();
            console.log('Initial pairs:', initialPairs);
            this.pairs(initialPairs);

            return this;
        },

        onValueChanged: function (value) {
            console.log('Value changed:', value);
            var parsedValue = this.parseValue(value);
            console.log('Parsed value:', parsedValue);
            this.pairs(parsedValue);
        },

        getInitialPairs: function () {
            var value = this.value();
            console.log('Getting initial pairs from value:', value);
            return this.parseValue(value);
        },

        parseValue: function (value) {
            console.log('Parsing value:', value);
            try {
                var parsed = JSON.parse(value) || [];
                parsed.forEach(function(pair) {
                    pair.language = ko.observable(pair.language);
                    pair.seniority = ko.observable(pair.seniority);
                });
                return parsed;
            } catch (e) {
                console.log('Error parsing value:', e);
                return [];
            }
        },

        addPair: function () {
            console.log('Adding pair');
            this.pairs.push({ language: ko.observable(''), seniority: ko.observable('') });
            this.updateValue();
        },

        removePair: function (pair) {
            console.log('Removing pair', pair);
            this.pairs.remove(pair);
            this.updateValue();
        },

        updateValue: function () {
            var pairs = this.pairs().map(function(pair) {
                return {
                    language: pair.language(),
                    seniority: pair.seniority()
                };
            });
            console.log('Updating value to:', pairs);
            this.value(JSON.stringify(pairs));
        }
    });
});
