/**
 * Loads a grid of recently-edited modResources.
 * 
 * @class MODx.grid.ActiveDocuments
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-activedocuments
 */
MODx.grid.ActiveDocuments = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		title: _('active_documents')
        ,url: MODx.config.connectors_url+'system/activedocuments.php'
		,fields: ['id','pagetitle','user','editedon']
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 50 }
            ,{ header: _('document_title') ,dataIndex: 'pagetitle' ,width: 240 }
            ,{ header: _('sysinfo_userid') ,dataIndex: 'user' ,width: 180 }
            ,{ header: _('datechanged') ,dataIndex: 'editedon' ,width: 140 }]
		,paging: true
	});
	MODx.grid.ActiveDocuments.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ActiveDocuments,MODx.grid.Grid);
Ext.reg('grid-activedocuments',MODx.grid.ActiveDocuments);