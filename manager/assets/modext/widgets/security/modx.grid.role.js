/**
 * Loads a grid of roles.
 * 
 * @class MODx.grid.Role
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-role
 */
MODx.grid.Role = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		title: _('roles')
        ,url: MODx.config.connectors_url+'security/role.php'
		,fields: ['id','rolename_link','description','authority']
        ,width: 800
        ,paging: true
        ,autosave: true
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 50 ,sortable: true }
            ,{ header: _('name') ,dataIndex: 'rolename_link' ,width: 150 ,sortable: true }
            ,{ header: _('description') ,dataIndex: 'description' ,width: 350 ,editor: { xtype: 'textfield' } }
            ,{ header: _('authority') ,dataIndex: 'authority' ,width: 60 ,editor: { xtype: 'textfield' } ,sortable: true }
        ]
		,tbar: [{
			text: _('create_new')
			,handler: function() {
                location.href = 'index.php?a='+MODx.action['security/role/create'];
            }
		}]
	});
	MODx.grid.Role.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Role,MODx.grid.Grid);
Ext.reg('grid-role',MODx.grid.Role);