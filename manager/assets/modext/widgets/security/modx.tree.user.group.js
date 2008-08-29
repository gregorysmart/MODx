/**
 * Generates the User Group Tree in Ext
 *
 * @class MODx.tree.UserGroup
 * @extends MODx.tree.Tree
 * @constructor
 * @param {Object} config An object of options.
 * @xtype tree-usergroup
 */
MODx.tree.UserGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('user_groups') 
        ,url: MODx.config.connectors_url+'security/group.php'
		,root_id: 'n_ug_0'
		,root_name: _('user_groups')
		,enableDrag: true
		,enableDrop: true
        ,rootVisible: false
		,ddAppendOnly: true
        ,useDefaultToolbar: true
        ,tbar: [{
            text: _('user_group_new')
            ,scope: this
            ,handler: this.create.createDelegate(this,[true],true)
        }]
	});
	MODx.tree.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.UserGroup,MODx.tree.Tree,{	
	windows: {}
	
	,addUser: function(item,e) {
		var n = this.cm.activeNode;
		var ug = n.id.substr(2).split('_'); ug = ug[1];
		if (ug == undefined) ug = 0;
		var r = {user_group: ug};
        
        if (!this.windows.adduser) {
            this.windows.adduser = new MODx.window.AddUserToUserGroup({
    			record: r
    			,success: this.refresh
    			,scope: this
    		});
        } else {
            this.windows.adduser.setValues(r);
        }
		this.windows.adduser.show(e.target);
	}
	
	,create: function(item,e,tbar) {
		tbar = tbar || false;
        if (tbar == false) {
            var n = this.cm.activeNode;
		    var p = n.id.substr(2).split('_'); p = p[1];
		    if (p == undefined) p = 0;
        } else var p = 0;
        var r = {parent: p};
        
		if (!this.windows.create) {
    		this.windows.create = new MODx.window.CreateUserGroup({
    			record: r
    			,success: this.refresh
    			,scope: this
    		});
        } else {
            this.windows.create.setValues(r);
        }
		this.windows.create.show(e.target);
	}
    
    ,update: function(item,e) {
        var n = this.cm.activeNode;
        var id = n.id.substr(2).split('_'); id = id[1];
        
        Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: id
            }
            ,scope: this
            ,success: function(r,o) {
                r = Ext.decode(r.responseText);
                if (!this.windows.update) {
                    this.windows.update = new MODx.window.UpdateUserGroup({
                        scope: this
                        ,success: this.refresh
                        ,record: r.object
                    });
                } else {
                    this.windows.update.setValues(r.object);
                }
                this.windows.update.show(e.target);
            }
        });
    }
	
	,remove: function(item,e) {
		var n = this.cm.activeNode;
		var id = n.id.substr(2).split('_'); id = id[1];
		
		MODx.msg.confirm({
			title: _('warning')
			,text: _('confirm_delete_user_group')
			,connector: this.config.url
			,params: {
				action: 'remove'
				,id: id
			}
			,scope: this
			,success: this.refresh
		});
	}
	
	,removeUser: function(item,e) {
		var n = this.cm.activeNode;
		var user_id = n.id.substr(2).split('_'); user_id = user_id[1];
		var group_id = n.parentNode.id.substr(2).split('_'); group_id = group_id[1];
		
		MODx.msg.confirm({
			title: _('warning')
			,text: _('confirm_remove_user_from_group')
			,connector: this.config.url
			,params: { 
				action: 'removeUser'
				,user_id: user_id
				,group_id: group_id
			}
			,scope: this
			,success: this.refresh
		});
	}
});
Ext.reg('tree-usergroup',MODx.tree.UserGroup);


/** 
 * Generates the Create User Group window.
 *  
 * @class MODx.window.CreateUserGroup
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-usergroup-create
 */
MODx.window.UpdateUserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('user_group_update')
        ,height: 150
        ,width: 375
        ,url: MODx.config.connectors_url+'security/group.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,width: 150
            ,allowBlank: false
        }]
    });
    MODx.window.UpdateUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUserGroup,MODx.Window);
Ext.reg('window-usergroup-update',MODx.window.UpdateUserGroup);
