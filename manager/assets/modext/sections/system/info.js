Ext.onReady(function() {
    MODx.load({ xtype: 'page-system-info' });
});

/**
 * Loads the system info page
 * 
 * @class MODx.page.SystemInfo
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-system-info
 */
MODx.page.SystemInfo = function(config) {
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
	MODx.page.SystemInfo.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.SystemInfo,MODx.Component);
Ext.reg('page-system-info',MODx.page.SystemInfo);


var viewPHPInfo = function() {
	dontShowWorker = true; // prevent worker from being displayed
	window.location.href= MODx.config.connectors_url+'system/phpinfo.php';
};