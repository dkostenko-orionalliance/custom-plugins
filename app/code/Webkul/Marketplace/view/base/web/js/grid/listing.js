define([
    'Magento_Ui/js/grid/listing'
],function(Component){
    'use strict';
    
    return Component.extend({
        defaults: {
            editorConfig: {
                component: 'OrionAlliance_NewModule/js/grid/editing/editor'
            }
        }
    })
}
)