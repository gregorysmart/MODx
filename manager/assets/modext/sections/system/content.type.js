Ext.namespace('MODx');
Ext.onReady(function() {
    MODx.load({ xtype: 'modx-content-type'});
});
/**
 * Loads the content type management page
 * 
 * @class MODx.ContentType
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-content-type
 */
MODx.ContentType = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
            xtype: 'grid-contenttype'
            ,renderTo: 'content_type_grid'
        }]
	});	
	MODx.ContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.ContentType,MODx.Component);
Ext.reg('modx-content-type',MODx.ContentType);