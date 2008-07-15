Ext.namespace('MODx');
Ext.onReady(function() {
    MODx.load({ xtype: 'modx-manager-log' });
});
/**
 * Loads the manager log page
 * 
 * @class MODx.ManagerLog
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-manager-log
 */
MODx.ManagerLog = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        components: [{
            xtype: 'panel-manager-log'
            ,renderTo: 'panel-manager-log'
        },{
            xtype: 'grid-manager-log'
            ,renderTo: 'grid-manager-log'
        }]
	});
	MODx.ManagerLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.ManagerLog,MODx.Component);
Ext.reg('modx-manager-log',MODx.ManagerLog);