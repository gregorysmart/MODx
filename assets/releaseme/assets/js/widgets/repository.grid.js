RM.grid.Repository = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	    title: _('repositories')
        ,url: RM.config.connector_url
        ,baseParams: { action: 'repository/getList' }
        ,fields: ['id','name','description','packages','menu']
        ,save_action: 'repository/updateFromGrid'
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,editor: { xtype: 'textfield' }
            ,width: 200
        },{
            header: _('num_items')
            ,dataIndex: 'items'
            ,width: 100
        }]
        ,tbar: [{
            text: _('repository_create_new')
            ,handler: { xtype: 'window-repository-create' ,blankValues: true }
        }]
	});
	RM.grid.Repository.superclass.constructor.call(this,config)
};
Ext.extend(RM.grid.Repository,MODx.grid.Grid,{
    viewPackages: function() {
        location.href = '?a='+RM.request.a+'&action=repository/update&repository='+this.menu.record.id;
    }
});
Ext.reg('rm-grid-repository',RM.grid.Repository);