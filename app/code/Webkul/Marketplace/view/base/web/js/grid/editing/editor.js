define(
    [
        'Magento_Ui/js/grid/editing/editor'
    ], function(Component){
        'use strict';
        return Component.extend({
            defaults:{
                templates: {
                    record: {  
                        component: 'OrionAlliance_NewModule/js/grid/editing/record', 
                    }
                }
            }
        })
    }
)