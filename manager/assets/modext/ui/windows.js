Ext.namespace('MODx','MODx.window');

/** 
 * Generates the Duplicate Resource window.
 *  
 * @class MODx.window.DuplicateResource
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-resource-duplicate
 */
MODx.window.DuplicateResource = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('duplication_options')
		,width: 400
	});
	MODx.window.DuplicateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateResource,MODx.Window,{
	_loadForm: function() {
		if (this.checkIfLoaded(this.config.record)) {
			this.fp.getForm().baseParams = {
				action: 'duplicate'
				,id: this.config.resource
			}
			return false;
		}
		var items = [];
		
		if (this.config.is_folder) {
			items.push({
                xtype: 'checkbox'
                ,fieldLabel: _('duplicate_children')
                ,name: 'duplicate_children'
                ,checked: true
                ,listeners: {
                    'check': {fn: function(cb,checked) { 
                        if (checked) {
                            this.fp.getForm().findField('drd_name').disable();
                        } else this.fp.getForm().findField('drd_name').enable();
                    },scope:this}
                }
            });
		}
		items.push({
            xtype: 'textfield'
            ,id: 'drd_name'
            ,fieldLabel: _('resource_name_new')
            ,name: 'name'
            ,width: 150
            ,value: ''
            ,disabled: this.config.is_folder ? true : false
        });
		
		this.fp = this.createForm({
			url: this.config.connector || MODx.config.connectors_url+'resource/document.php'
			,baseParams: this.config.baseParams || {
				action: 'duplicate'
				,id: this.config.resource
			}
			,labelWidth: 125
			,defaultType: 'textfield'
			,autoHeight: true
			,items: items
		});
		
		this.renderForm();
	}
});
Ext.reg('window-resource-duplicate',MODx.window.DuplicateResource);

/** 
 * Generates the Create User Group window.
 *  
 * @class MODx.window.CreateUserGroup
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-usergroup-create
 */
MODx.window.CreateUserGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('create_user_group')
		,height: 150
		,width: 375
        ,url: MODx.config.connectors_url+'security/group.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
        },{
            name: 'parent'
            ,xtype: 'hidden'
        }]
	});
	MODx.window.CreateUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUserGroup,MODx.Window);
Ext.reg('window-usergroup-create',MODx.window.CreateUserGroup);

/** 
 * Generates the Add User to User Group window.
 *  
 * @class MODx.window.AddUserToUserGroup
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-usergroup-adduser
 */
MODx.window.AddUserToUserGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('add_user_to_group')
		,height: 150
		,width: 375
        ,connector: MODx.config.connectors_url+'security/group.php'
        ,action: 'addUser'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'member'
            ,hiddenName: 'member'
            ,xtype: 'combo-user'
        },{
            name: 'user_group'
            ,xtype: 'hidden'
        }]
	});
	MODx.window.AddUserToUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddUserToUserGroup,MODx.Window);
Ext.reg('window-usergroup-adduser',MODx.window.AddUserToUserGroup);

/** 
 * Generates the Create Document Group window.
 *  
 * @class MODx.window.CreateDocumentGroup
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-resourcegroup-create
 */
MODx.window.CreateDocumentGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('create_document_group')
		,height: 150
		,width: 350
        ,connector: MODx.config.connectors_url+'security/documentgroup.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
        }]
	});
	MODx.window.CreateDocumentGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateDocumentGroup,MODx.Window);
Ext.reg('window-resourcegroup-create',MODx.window.CreateDocumentGroup);

/** 
 * Generates the Create Category window.
 *  
 * @class MODx.window.CreateCategory
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-category-create
 */
MODx.window.CreateCategory = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('new_category')
		,height: 150
		,width: 350
        ,connector: MODx.config.connectors_url+'element/category.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'category'
            ,xtype: 'textfield'
            ,width: 150
        }]
	});
	MODx.window.CreateCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateCategory,MODx.Window);
Ext.reg('window-category-create',MODx.window.CreateCategory);


/**
 * Generates the create namespace window.
 *  
 * @class MODx.window.CreateNamespace
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-namespace-create
 */
MODx.window.CreateNamespace = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('namespace_create')
        ,width: 600
        ,url: MODx.config.connectors_url+'workspace/namespace.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,width: 250
            ,maxLength: 100
        },{
            xtype: 'textfield'
            ,fieldLabel: _('path')
            ,name: 'path'
            ,width: 400
        }]
    });
    MODx.window.CreateNamespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateNamespace,MODx.Window);
Ext.reg('window-namespace-create',MODx.window.CreateNamespace);