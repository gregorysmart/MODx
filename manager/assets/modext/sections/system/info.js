Ext.namespace('MODx');
Ext.onReady(function() {
    MODx.load({ xtype: 'modx-system-info' });
});

/**
 * Loads the system info page
 * 
 * @class MODx.SystemInfo
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-system-info
 */
MODx.SystemInfo = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        components: [{
            xtype: 'grid-databasetables'
            ,renderTo: 'dt_grid'
        },{
            xtype: 'grid-activedocuments'
            ,renderTo: 'documents_grid'
        }]
        ,tabs: [{
            contentEl: 'tab_server', title: _('server')
        },{
            contentEl: 'tab_documents', title: _('activity_title')
        },{
            contentEl: 'tab_database', title: _('database_tables')
        },{
            contentEl: 'tab_users', title: _('onlineusers_title')
        }]
	});
	MODx.SystemInfo.superclass.constructor.call(this,config);
};
Ext.extend(MODx.SystemInfo,MODx.Component);
Ext.reg('modx-system-info',MODx.SystemInfo);


var viewPHPInfo = function() {
	dontShowWorker = true; // prevent worker from being displayed
	window.location.href='index.php?a='+MODx.action['system/phpinfo'];
};

var truncate = function(name) {
    Ext.getCmp('grid-dbtable').truncate(name);
};
var optimize = function(name) {
    Ext.getCmp('grid-dbtable').optimize(name);
};