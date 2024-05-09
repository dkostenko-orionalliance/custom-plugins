define(
    [
        'Magento_Ui/js/grid/editing/record'
    ], function(Component){
        'use strict';
        return Component.extend({
            defaults:{
                templates: {
                    fields: {
                        price: {
                            component: 'OrionAlliance_NewModule/js/form/element/price',
                            template: 'ui/form/element/input'
                        }
                    }
                }
            }
        })
    }
)