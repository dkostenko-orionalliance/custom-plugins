define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'uiRegistry',
    'mage/template',
    'underscore',
    'knockout',
    'text!Webkul_MpAdvancedBookingSystem/template/form/element/skill-seniority.html'
], function ($, Abstract, registry, mageTemplate, _, ko, template) {
    'use strict';

    return Abstract.extend({
        defaults: {
            template: 'Webkul_MpAdvancedBookingSystem/form/element/skill-seniority',
            skills: window.skillsOptions || [],
            seniorities: window.skillSenioritiesOptions || [],
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
            console.log('Initializing skill-seniority component');
            console.log('Skills options:', this.skills);
            console.log('Seniorities options:', this.seniorities);

            var initialPairs = this.getInitialPairs();
            if (initialPairs.length === 0) {
                initialPairs.push({ skill: ko.observable(''), seniority: ko.observable('') });
            }
            console.log('Initial pairs:', initialPairs);
            this.pairs(initialPairs);

            return this;
        },

        onValueChanged: function (value) {
            console.log('Value changed:', value);
            var parsedValue = this.parseValue(value);
            if (parsedValue.length === 0) {
                parsedValue.push({ skill: ko.observable(''), seniority: ko.observable('') });
            }
            console.log('Parsed value:', parsedValue);
            this.pairs(parsedValue);
        },

        getInitialPairs: function () {
            var value = window.skillSeniorityPairs || this.value();
            console.log('Getting initial pairs from value:', value);
            var pairs = this.parseValue(value);
            if (pairs.length === 0) {
                pairs.push({ skill: ko.observable(''), seniority: ko.observable('') }); // Ensure at least one empty pair
            }
            return pairs;
        },

        parseValue: function (value) {
            console.log('Parsing value:', value);
            try {
                if (!value || value.length === 0) {
                    return [];
                }
                var parsed = typeof value === 'string' ? JSON.parse(value) : value;
                var result = parsed.map(function (pair) {
                    return {
                        skill: ko.observable(pair.skill || ''),
                        seniority: ko.observable(pair.seniority || '')
                    };
                });
                console.log('Parsed result:', result);
                return result;
            } catch (e) {
                console.log('Error parsing value:', e);
                return [];
            }
        },

        addPair: function () {
            console.log('Adding pair');
            this.pairs.push({ skill: ko.observable(''), seniority: ko.observable('') });
            this.updateValue();
        },

        removePair: function (pair) {
            console.log('Removing pair', pair);
            this.pairs.remove(pair);
            this.updateValue();
        },

        updateValue: function () {
            var pairs = this.pairs().map(function (pair) {
                return {
                    skill: pair.skill(),
                    seniority: pair.seniority()
                };
            });
            console.log('Updating value to:', pairs);
            this.value(JSON.stringify(pairs));
        }
    });
});
