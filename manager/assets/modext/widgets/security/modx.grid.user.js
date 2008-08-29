/**
 * Loads a grid of MODx users.
 * 
 * @class MODx.grid.AccessResourceGroup
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of config properties
 * @xtype grid-user
 */
MODx.grid.User = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		title: _('users')
        ,url: MODx.config.connectors_url+'security/user.php'
		,fields: ['id','username','fullname','email'
            ,'gender','blocked','role','menu']
        ,columns: this.getColumns()
        ,paging: true
		,autosave: true
		,tbar: [{
            xtype: 'textfield'
            ,name: 'username_filter'
            ,id: 'username_filter'
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
            ,editor: { xtype: 'combo-role' ,renderer: true }
        },{
			header: _('user_block')
            ,dataIndex: 'blocked'
			,width: 80
			,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        }];
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
            ,scope: this
            ,success: this.refresh
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
Ext.reg('grid-user',MODx.grid.User);