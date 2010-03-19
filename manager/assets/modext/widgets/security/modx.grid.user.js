MODx.panel.Users = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-users'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('users')+'</h2>'
            ,border: false
            ,id: 'modx-users-header'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 1.5em'
            ,items: [{
                html: '<p>'+_('user_management_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-user'
                ,preventRender: true
            }]
        }]
    });
    MODx.panel.Users.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Users,MODx.FormPanel);
Ext.reg('modx-panel-users',MODx.panel.Users);

MODx.grid.User = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		url: MODx.config.connectors_url+'security/user.php'
		,fields: ['id','username','fullname','email'
            ,'gender','blocked','role','active','menu']
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
            ,dataIndex: 'username'
            ,width: 150
            ,sortable: true
        },{
            header: _('user_full_name')
            ,dataIndex: 'fullname'
            ,width: 180
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('email')
            ,dataIndex: 'email'
            ,width: 180
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('active')
            ,dataIndex: 'active'
            ,width: 80
            ,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        },{
            header: _('user_block')
            ,dataIndex: 'blocked'
            ,width: 80
            ,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        }]
		,tbar: [{
            text: _('user_new')
            ,handler: this.createUser
            ,scope: this
        },'-',{
            xtype: 'textfield'
            ,name: 'query'
            ,itemId: 'fld-search'
            ,emptyText: _('search')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn:function(tf) {
                    tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
                        this.search(tf);
                    },this);
                },scope:this}
            }
        }]
	});
	MODx.grid.User.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.User,MODx.grid.Grid,{
	createUser: function() {
        location.href = 'index.php?a='+MODx.action['security/user/create'];
    }
    
    ,remove: function() {
        MODx.msg.confirm({
            title: _('user_remove')
            ,text: _('user_confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'delete'
                ,id: this.menu.record.id
            }
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        });
    }
    
    ,update: function() {
        location.href = 'index.php?a='+MODx.action['security/user/update']+'&id='+this.menu.record.id;
    }
    				
	,rendGender: function(d,c) {
		switch(d.toString()) {
			case '0':
				return '-';
			case '1':
				return _('male');
			case '2':
				return _('female');
		}
	}
    
    ,search: function(tf,nv,ov) {
        this.getStore().baseParams = {
            action: 'getList'
            ,query: tf.getValue()
        };
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('modx-grid-user',MODx.grid.User);