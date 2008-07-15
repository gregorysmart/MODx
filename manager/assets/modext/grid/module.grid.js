Ext.namespace('MODx','MODx.grid');
/**
 * Loads a grid of MODx Modules.
 * 
 * @class MODx.grid.Module
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-module
 */
MODx.grid.Module = function(config) {
	config = config || {};
    Ext.applyIf(config,{
		title: _('modules')
        ,url: MODx.config.connectors_url+'element/module.php'
		,fields: ['id','name','description','locked','disabled','menu']
		,width: 800
		,autosave: true
        ,paging: true
        ,remoteSort: true
		,tbar: [{
			text: _('create_new')
			,handler: this.create
		}]
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 350
            ,sortable: false
            ,editor: { xtype: 'textfield' }
        },{
            header: _('locked')
            ,dataIndex: 'locked'
            ,width: 80
            ,sortable: false
            ,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        },{
            header: _('disabled')
            ,dataIndex: 'disabled'
            ,width: 80
            ,sortable: false
            ,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        }]
	});
	MODx.grid.Module.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Module,MODx.grid.Grid,{
	create: function() {
        location.href = 'index.php?a='+MODx.action['element/module/create'];
    }
    
    ,run: function() {
        location.href = 'index.php?a='+MODx.action['element/module/run&id=']+this.menu.record.id;
    }
    
    ,update: function() {
        location.href = 'index.php?a='+MODx.action['element/module/update']+'&id='+this.menu.record.id;
    }
});
Ext.reg('grid-module',MODx.grid.Module);