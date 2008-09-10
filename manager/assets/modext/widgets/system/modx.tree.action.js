/**
 * Generates a modAction Tree in Ext
 * 
 * @class MODx.tree.Action
 * @extends MODx.tree.Tree
 * @constructor
 * @param {Object} config An object of options.
 * @xtype tree-action
 */
MODx.tree.Action = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        root_id: 'n_root_0'
        ,root_name: _('actions')
		,title: _('actions')
        ,rootVisible: true
        ,expandFirst: true
        ,enableDrag: true
        ,enableDrop: true
		,ddAppendOnly: true
        ,url: MODx.config.connectors_url + 'system/action.php'
		,action: 'getNodes'
    });
    MODx.tree.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.Action,MODx.tree.Tree,{
	windows: {}
		
	/**
	 * Loads the MODx.dialog.CreateAction with the parent action information.
	 * @see MODx.dialog.CreateAction
	 * @param {Ext.tree.TreeNode} node The selected TreeNode.
	 * @param {Ext.EventObject} e The event object.
	 */
	,create: function(node,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); 
        id = id[1] == 'context' ? 0 : id[2];
		
		Ext.Ajax.request({
			url: this.config.url
			,params: {
				action: 'get'
				,id: id
			}
			,scope: this
			,success: function(r,o) {
				r = Ext.decode(r.responseText);
                Ext.apply(r.object,{
                    parent: r.object.id
                });
				if (!this.windows.create_action) {
					this.windows.create_action = MODx.load({
						xtype: 'window-action-create'
                        ,scope: this
						,success: this.refresh
						,record: r.object
					});
				} else {
				    this.windows.create_action.setValues(r.object);
                }
				this.windows.create_action.show(e.target);
			}
		});
	}
	
	/**
	 * Loads the UpdateAction dialog.
	 * @see MODx.dialog.UpdateAction.
	 * @param {Ext.tree.TreeNode} node The selected TreeNode.
	 * @param {Ext.EventObject} e The event object.
	 */
	,update: function(node,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[2];
		
		Ext.Ajax.request({
			url: this.config.url
			,params: {
				action: 'get'
				,id: id
			}
			,scope: this
			,success: function(r,o) {
				r = Ext.decode(r.responseText);
				Ext.applyIf(r.object,{
                    parent: r.object.id
                    ,parent_controller: r.object.controller
                    ,loadheaders: r.object.haslayout
                });
				if (!this.windows.update_action) {
					this.windows.update_action = MODx.load({
						xtype: 'window-action-update'
                        ,scope: this
						,success: this.refresh
						,record: r.object
					});
				} else {
					this.windows.update_action.setValues(r.object);
				}
				this.windows.update_action.show(e.target);
			}
		});
	}
	
	/**
	 * Removes the action.
	 * @see MODx.msg
	 * @param {Ext.tree.TreeNode} node The selected TreeNode.
	 * @param {Ext.EventObject} e The event object.
	 */
	,remove: function(node,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[2];
		
		MODx.msg.confirm({
			title: _('warning')
			,text: _('action_confirm_remove')
			,url: this.config.url
			,params: {
				action: 'remove'
				,id: id
			}
            ,listeners: {
                'success':{fn:this.refresh,scope:this}
            }
		});	
	}
});
Ext.reg('tree-action',MODx.tree.Action);

/** 
 * Generates the Create Action window
 * 
 * @class MODx.window.CreateAction
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-action-create
 */
MODx.window.CreateAction = function(config) {
    config = config || {};
	Ext.applyIf(config,{
        title: _('action_create')
		,width: 430
        ,url: MODx.config.connectors_url+'system/action.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('controller')
            ,name: 'controller'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('context')
            ,name: 'context_key'
            ,xtype: 'combo-context'
            ,width: 150
            ,allowBlank: false
            ,value: 'mgr'
        },{
            fieldLabel: _('controller_parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,xtype: 'combo-action'
            ,width: 200
        },{
            fieldLabel: _('load_headers')
            ,name: 'loadheaders'
            ,xtype: 'checkbox'
            ,checked: true
        },{
            fieldLabel: _('lang_foci')
            ,description: _('lang_foci_desc')
            ,name: 'lang_foci'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('assets')
            ,name: 'assets'
            ,xtype: 'textarea'
            ,width: 200
            ,grow: false
        }]
	});
	MODx.window.CreateAction.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateAction,MODx.Window);
Ext.reg('window-action-create',MODx.window.CreateAction);


/** 
 * Generates the Update Action window
 * 
 * @class MODx.window.UpdateAction
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-action-update
 */
MODx.window.UpdateAction = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		title: _('action_update')
        ,width: 430
        ,url: MODx.config.connectors_url+'system/action.php'
        ,action: 'update'
        ,fields: [{
            name: 'id'
            ,xtype: 'hidden'
        },{
            name: 'parent'
            ,xtype: 'hidden'
        },{
            fieldLabel: _('controller')
            ,name: 'controller'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('context')
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,xtype: 'combo-context'
            ,width: 150
            ,allowBlank: false
        },{
            fieldLabel: _('controller_parent')
            ,name: 'parent_controller'
            ,hiddenName: 'parent_controller'
            ,xtype: 'combo-action'
            ,readOnly: true
            ,width: 200
        },{
            fieldLabel: _('load_headers')
            ,name: 'loadheaders'
            ,xtype: 'checkbox'
            ,checked: true
        },{
            fieldLabel: _('lang_foci')
            ,description: _('lang_foci_desc')
            ,name: 'lang_foci'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('assets')
            ,name: 'assets'
            ,xtype: 'textarea'
            ,width: 200
            ,grow: false
        }]
	});
	MODx.window.UpdateAction.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateAction,MODx.Window);
Ext.reg('window-action-update',MODx.window.UpdateAction);


/**
 * Displays a dropdown list of modActions.
 * @class MODx.combo.Action
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-action
 */
MODx.combo.Action = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'action'
        ,hiddenName: 'action'
        ,displayField: 'controller'
        ,valueField: 'id'
        ,fields: ['id','controller']
        ,url: MODx.config.connectors_url+'system/action/index.php'
    });
    MODx.combo.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Action,MODx.combo.ComboBox);
Ext.reg('combo-action',MODx.combo.Action);

