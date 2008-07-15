Ext.namespace('MODx');
Ext.onReady(function() {
    MODx.load({ xtype: 'modx-system-event' });
});
/**
 * Loads the system event page
 * 
 * @class MODx.SystemEvent
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-system-event
 */
MODx.SystemEvent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'grid-system-event'
            ,el: 'grid-system-event'
        }]
    });
    MODx.SystemEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.SystemEvent,MODx.Component);
Ext.reg('modx-system-event',MODx.SystemEvent);