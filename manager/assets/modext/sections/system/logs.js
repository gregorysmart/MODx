Ext.onReady(function() {
    MODx.load({ xtype: 'page-manager-log' });
});
/**
 * Loads the manager log page
 * 
 * @class MODx.page.ManagerLog
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-manager-log
 */
MODx.page.ManagerLog = function(config) {
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
	MODx.page.ManagerLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ManagerLog,MODx.Component);
Ext.reg('page-manager-log',MODx.page.ManagerLog);