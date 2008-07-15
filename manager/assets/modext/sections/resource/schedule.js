Ext.namespace('MODx','MODx.Resource');
Ext.onReady(function() {
	MODx.load({ xtype: 'resource-schedule' });
});

/**
 * Loads the Site Schedule page
 * 
 * @class MODx.Resource.Schedule
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype resource-schedule
 */
MODx.Resource.Schedule = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        components: [{
            xtype: 'grid-resource-event'
            ,renderTo: 'grid-resource-event'
        }]
	});
	MODx.Resource.Schedule.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Resource.Schedule,MODx.Component);
Ext.reg('resource-schedule',MODx.Resource.Schedule);