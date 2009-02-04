/**
 * Loads the panel for managing users.
 * 
 * @class MODx.panel.Users
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-users
 */
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

/**
 * Loads a grid of MODx users.
 * 
 * @class MODx.grid.User
 * @extends MODx.grid.Grid
 * @param {Object} config An object of config properties
 * @xtype modx-grid-user
 */
MODx.grid.User = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		url: MODx.config.connectors_url+'security/user.php'
		,fields: ['id','username','fullname','email'
            ,'gender','blocked','role','menu']
        ,columns: this.getColumns()
        ,paging: true
		,autosave: true
		,tbar: [{
            text: _('user_new')
            ,handler: this.createUser
            ,scope: this
        },'-',{
            xtype: 'textfield'
            ,name: 'username_filter'
            ,id: 'modx-filter-username'
            ,emptyText: _('filter_by_username')
            ,listeners: {
                'change': {fn:this.filterByName,scope:this}
                ,'render': {fn:function(tf) {
                    tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
                        tf.fireEvent('change'); 
                    },this);
                }}
            }
        }]
	});
	MODx.grid.User.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.User,MODx.grid.Grid,{
	getColumns: function() {		
		var gs = new Ext.data.SimpleStore({
			fields: ['text','value']
			,data: [['-',0],[_('male'),1],[_('female'),2]]
		});
		
		return [{
			header: _('id')
            ,dataIndex: 'id'
			,width: 50
            ,sortable: false
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
            header: _('role')
            ,dataIndex: 'role'
            ,sortable: true
            ,editor: { xtype: 'modx-combo-role' ,renderer: true }
        },{
			header: _('user_block')
            ,dataIndex: 'blocked'
			,width: 80
			,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        }];
	}
    
    ,createUser: function() {
        location.href = 'index.php?a='+MODx.action['security/user/create'];
    }
    
    /**
     * Prompts a confirm dialog to remove the user
     */
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
    
    /**
     * Loads the update screen for the user
     */
    ,update: function() {
        location.href = 'index.php?a='+MODx.action['security/user/update']+'&id='+this.menu.record.id;
    }
    
	/**
     * Render the row to a specific gender title.
     * @param {Object} d The data record
     * @param {Object} c The dom properties
     */				
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
    
    /**
     * Filters the grid by the specified name
     * @param {Ext.form.Textfield} tf
     * @param {String} newValue
     * @param {String} oldValue
     */
    ,filterByName: function(tf,newValue,oldValue) {
        this.getStore().baseParams = {
            action: 'getList'
            ,username: newValue            
        };
        this.getStore().load({
            params: {
                start: 0
                ,limit: 15
            }
            ,scope: this
            ,callback: this.refresh
        });
    }
});
Ext.reg('modx-grid-user',MODx.grid.User);