Ext.onReady(function() {
    MODx.load({ xtype: 'page-system-event' });
});
/**
 * Loads the system event page
 * 
 * @class MODx.page.SystemEvent
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-system-event
 */
MODx.page.SystemEvent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'grid-system-event'
            ,renderTo: 'grid-system-event'
        }]
    });
    MODx.page.SystemEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.SystemEvent,MODx.Component);
Ext.reg('page-system-event',MODx.page.SystemEvent);