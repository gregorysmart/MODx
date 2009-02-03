/**
 * Generates a Tree for managing the Top Menu
 *
 * @class MODx.tree.Menu
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-menu
 */
MODx.tree.Menu = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        root_id: 'n_0'
        ,root_name: _('menu_top')
		,title: _('menu_top')
        ,rootVisible: true
        ,expandFirst: true
        ,enableDrag: true
        ,enableDrop: true
        ,url: MODx.config.connectors_url + 'system/menu.php'
		,action: 'getNodes'
    });
    MODx.tree.Menu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.Menu, MODx.tree.Tree, {
	windows: {}
	
	,create: function(n,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		
		MODx.Ajax.request({
			url: this.config.url
			,params: {
				action: 'get'
				,id: id
			}
			,listeners: {
				'success': {fn:function(r) {
					Ext.applyIf(r.object,{
                        parent: r.object.id
                        ,parent_text: r.object.text
                    });
                    if (!this.windows.create_menu) {
                        this.windows.create_menu = MODx.load({
                            xtype: 'modx-window-menu-create'
                            ,scope: this
                            ,success: this.refresh
                            ,record: r.object
                        });
                    } else {
                        this.windows.create_menu.setValues(r.object);
                    }
                    this.windows.create_menu.show(e.target);
				},scope:this}
			}
		});
	}
	
	,update: function(n,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		
		MODx.Ajax.request({
			url: this.config.url
			,params: {
				action: 'get'
				,id: id
			}
			,listeners: {
				'success': {fn:function(r) {
					Ext.applyIf(r.object,{
                        action_id: r.object.action
                    });
                    if (!this.windows.update_menu) {
                        this.windows.update_menu = MODx.load({
                            xtype: 'modx-window-menu-update'
                            ,scope: this
                            ,success: this.refresh
                            ,record: r.object
                        });
                    } else {
                        this.windows.update_menu.setValues(r.object);
                    }
                    this.windows.update_menu.show(e.target);
				},scope:this}
			}
		});
	}
	
	,remove: function(n,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		
		MODx.msg.confirm({
			title: _('warning')
			,text: _('menu_confirm_remove')
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
Ext.reg('modx-tree-menu',MODx.tree.Menu);

/** 
 * Generates the Create Menu window
 * 
 * @class MODx.window.CreateMenu
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-menu-create
 */
MODx.window.CreateMenu = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('menu_create')
        ,width: 480
		,height: 400
        ,url: MODx.config.connectors_url+'system/menu.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('text')
            ,name: 'text'
            ,id: 'modx-cmen-text'
            ,xtype: 'textfield'
            ,allowBlank: false
            ,width: 200
        },{
            fieldLabel: _('action')
            ,name: 'action_id'
            ,hiddenName: 'action_id'
            ,id: 'modx-cmen-action-id'
            ,xtype: 'combo-action'
        },{
            fieldLabel: _('menu_parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'modx-cmen-parent'
            ,xtype: 'combo-menu'
            ,hideTrigger: true
            ,width: 200
        },{
            fieldLabel: _('icon')
            ,name: 'icon'
            ,id: 'modx-cmen-icon'
            ,xtype: 'textfield'
            ,allowBlank: true
            ,width: 200
        },{
            fieldLabel: _('parameters')
            ,name: 'params'
            ,id: 'modx-cmen-params'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('handler')
            ,name: 'handler'
            ,id: 'modx-cmen-handler'
            ,xtype: 'textarea'
            ,width: 320
            ,grow: false
        }]
	});
	MODx.window.CreateMenu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateMenu,MODx.Window);
Ext.reg('window-menu-create',MODx.window.CreateMenu);

/** 
 * Generates the Update Menu window
 * 
 * @class MODx.window.UpdateMenu
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-menu-update
 */
MODx.window.UpdateMenu = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		title: _('menu_update')
        ,width: 480
		,height: 400
        ,url: MODx.config.connectors_url+'system/menu.php'
        ,action: 'update'
        ,fields: [{
            name: 'id'
            ,id: 'modx-umen-id'
            ,xtype: 'hidden'
        },{
            fieldLabel: _('text')
            ,name: 'text'
            ,id: 'modx-umen-text'
            ,xtype: 'textfield'
            ,allowBlank: false
            ,width: 200
        },{
            fieldLabel: _('action')
            ,name: 'action_id'
            ,hiddenName: 'action_id'
            ,id: 'modx-umen-action-id'
            ,xtype: 'modx-combo-action'
        },{
            fieldLabel: _('menu_parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'modx-umen-parent'
            ,xtype: 'modx-combo-menu'
            ,readOnly: true
            ,hideTrigger: true
            ,width: 200
        },{
            fieldLabel: _('icon')
            ,name: 'icon'
            ,id: 'modx-umen-icon'
            ,xtype: 'textfield'
            ,allowBlank: true
            ,width: 200
        },{
            fieldLabel: _('parameters')
            ,name: 'params'
            ,id: 'modx-umen-params'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('handler')
            ,name: 'handler'
            ,id: 'modx-umen-handler'
            ,xtype: 'textarea'
            ,width: 320
            ,grow: false
        }]
	});
	MODx.window.UpdateMenu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateMenu,MODx.Window);
Ext.reg('modx-window-menu-update',MODx.window.UpdateMenu);

/** 
 * Displays a dropdown of modActions
 * 
 * @class MODx.combo.Action
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-combo-action
 */
MODx.combo.Action = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		name: 'action'
		,hiddenName: 'action'
        ,url: MODx.config.connectors_url+'system/action.php'
		,fields: ['id','controller']
        ,displayField: 'controller'
        ,valueField: 'id'
		,listWidth: 300
	});
	MODx.combo.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Action,MODx.combo.ComboBox);
Ext.reg('modx-combo-action',MODx.combo.Action);


/** 
 * Displays a dropdown of modMenus
 * 
 * @class MODx.combo.Menu
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-combo-menu
 */
MODx.combo.Menu = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'menu'
        ,hiddenName: 'menu'
        ,url: MODx.config.connectors_url+'system/menu.php'
        ,fields: ['id','text']
        ,displayField: 'text'
        ,valueField: 'id'
        ,listWidth: 300
    });
    MODx.combo.Menu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Menu,MODx.combo.ComboBox);
Ext.reg('modx-combo-menu',MODx.combo.Menu);