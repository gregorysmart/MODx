Ext.namespace('MODx','MODx.combo');

/**
 * An abstraction of Ext.form.ComboBox with connector ability.
 * 
 * @class MODx.combo.ComboBox
 * @extends Ext.form.ComboBox
 * @constructor
 * @param {Object} config An object of config properties
 * @param {Boolean} getStore If true, will return the store.
 */
MODx.combo.ComboBox = function(config,getStore) {
	config = config || {};
    Ext.applyIf(config,{
        name: 'name'
        ,hiddenName: 'name'
        ,displayField: 'name'
        ,valueField: 'id'
        ,triggerAction: 'all'
        ,fields: ['id','name']
        ,baseParams: {
            action: 'getList'
        }
        ,width: 150
        ,listWidth: 300
        ,editable: true
        ,resizable: true
        ,typeAhead: true
        ,lazyInit: true
        ,minChars: 3
    });
    Ext.applyIf(config,{
        store: new Ext.data.JsonStore({
            url: config.connector || config.url
            ,root: 'results'
            ,totalProperty: 'total'
            ,fields: config.fields
            ,errorReader: modJSONReader
            ,baseParams: config.baseParams || {}
            ,remoteSort: config.remoteSort || false
        })
    });
	if (getStore === true) {
	   config.store.load();
	   return config.store;
	}
	MODx.combo.ComboBox.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ComboBox,Ext.form.ComboBox,{
	/**
     * @var {Boolean} isLoaded If the combo's store has been loaded.
     */
    isLoaded: false
    /**
	 * Set a value to the combobox correctly by loading the store.
	 * @param {Object} v The value to set.
     * @param {boolean} b True to bypass check
	 */
	,setValue: function(v,b) {
        if (this.isLoaded || b == true) {
            MODx.combo.ComboBox.superclass.setValue.call(this,v);
        } else {
            this.store.load({
                params: this.baseParams
                ,callback: function(r,o,s) {
                    this.isLoaded = true;
                    if (s) MODx.combo.ComboBox.superclass.setValue.call(this,v);
                    this.collapse();
                }
                ,scope: this
            });
        }
    }
});

/**
 * Helps with rendering of comboboxes in grids.
 * @class MODx.combo.Renderer
 * @constructor
 * @param {Ext.form.ComboBox} combo The combo to display
 */
MODx.combo.Renderer = function(combo) {
    var loaded = false;
    return (function(v) {
        if (!combo.store || !combo.store.proxy) return v;
        if (!loaded) { 
           combo.store.load();
           loaded = true;
           var idx = combo.store.find(combo.valueField,v);
           var rec = combo.store.getAt(idx);
           return (rec == null ? v : rec.get(combo.displayField));
        } else {
            var idx = combo.store.find(combo.valueField,v);
            var rec = combo.store.getAt(idx);
            return (rec == null ? v : rec.get(combo.displayField));
        }
    });
};

/**
 * Displays a yes/no combobox
 * 
 * @class MODx.combo.Boolean
 * @extends Ext.form.ComboBox
 * @constructor
 * @xtype combo-boolean
 */
MODx.combo.Boolean = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [[_('yes'),true],[_('no'),false]]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
    });
    MODx.combo.Boolean.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Boolean,Ext.form.ComboBox);
Ext.reg('combo-boolean',MODx.combo.Boolean);

/**
 * Displays a dropdown list of modUsers
 * 
 * @class MODx.combo.User
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-user
 */
MODx.combo.User = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'user'
		,hiddenName: 'user'
		,displayField: 'username'
		,valueField: 'id'
		,fields: ['username','id']
		,url: MODx.config.connectors_url+'security/user.php'
	});
	MODx.combo.User.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.User,MODx.combo.ComboBox);
Ext.reg('combo-user',MODx.combo.User);

/**
 * Displays a dropdown list of modUsers
 * 
 * @class MODx.combo.User
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-usergroup
 */
MODx.combo.UserGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'group'
		,hiddenName: 'group'
		,displayField: 'name'
		,valueField: 'id'
		,fields: ['name','id']
		,listWidth: 300
		,url: MODx.config.connectors_url+'security/group.php'
	});
	MODx.combo.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.UserGroup,MODx.combo.ComboBox);
Ext.reg('combo-usergroup',MODx.combo.UserGroup);

/**
 * Displays a dropdown list of modUserGroupRoles.
 * 
 * @class MODx.combo.UserGroupRole
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-usergrouprole
 */
MODx.combo.UserGroupRole = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'role'
		,hiddenName: 'role'
		,displayField: 'name'
		,valueField: 'id'
		,fields: ['name','id']
		,url: MODx.config.connectors_url+'security/role.php'
	});
	MODx.combo.UserGroupRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.UserGroupRole,MODx.combo.ComboBox);
Ext.reg('combo-usergrouprole',MODx.combo.UserGroupRole);

/**
 * Displays a dropdown list of modResourceGroups.
 * 
 * @class MODx.combo.ResourceGroup
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-resourcegroup
 */
MODx.combo.ResourceGroup = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        name: 'resourcegroup'
        ,hiddenName: 'resourcegroup'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,url: MODx.config.connectors_url+'security/resourcegroup.php'
    });
    MODx.combo.ResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ResourceGroup,MODx.combo.ComboBox);
Ext.reg('combo-resourcegroup',MODx.combo.ResourceGroup);

/**
 * Displays a dropdown list of modContexts.
 * 
 * @class MODx.combo.Context
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-context
 */
MODx.combo.Context = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        name: 'context'
        ,hiddenName: 'context'
        ,displayField: 'key'
        ,valueField: 'key'
        ,fields: ['key']
        ,url: MODx.config.connectors_url+'context/index.php'
    });
    MODx.combo.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Context,MODx.combo.ComboBox);
Ext.reg('combo-context',MODx.combo.Context);

/**
 * Displays a dropdown list of modPolicies.
 * 
 * @class MODx.combo.Policy
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-policy
 */
MODx.combo.Policy = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        name: 'policy'
        ,hiddenName: 'policy'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,allowBlank: false
        ,editable: false
        ,url: MODx.config.connectors_url+'security/access/policy.php'
    });
    MODx.combo.Policy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Policy,MODx.combo.ComboBox);
Ext.reg('combo-policy',MODx.combo.Policy);

/**
 * Displays a dropdown list of modTemplates.
 * 
 * @class MODx.combo.Template
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-template
 */
MODx.combo.Template = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'template'
		,hiddenName: 'template'
		,displayField: 'templatename'
		,valueField: 'id'
		,fields: ['id','templatename','description','category']
		,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{templatename}</span>'
							   ,' - <span style="font-style:italic">{category}</span>'
							   ,'<br />{description}</div></tpl>')
		,url: MODx.config.connectors_url+'element/template.php'
        ,listWidth: 350
        ,allowBlank: true
	});
	MODx.combo.Template.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Template,MODx.combo.ComboBox);
Ext.reg('combo-template',MODx.combo.Template);

/**
 * Displays a dropdown list of modCategories.
 * 
 * @class MODx.combo.Category
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-category
 */
MODx.combo.Category = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'category'
		,hiddenName: 'category'
		,displayField: 'category'
		,mode: 'local'
		,fields: ['id','category']
		,forceSelection: false
		,typeAhead: false
		,allowBlank: true
        ,enableKeyEvents: true
		,url: MODx.config.connectors_url+'element/category.php'
        ,listeners: {
            'blur': {fn:this._onblur,scope:this}
        }
	});
	MODx.combo.Category.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Category,MODx.combo.ComboBox,{
    _onblur: function(t,e) { 
        var v = this.getRawValue();
        this.setRawValue(v);
        this.setValue(v,true);
    }
});
Ext.reg('combo-category',MODx.combo.Category);

/**
 * Displays a dropdown list of languages.
 * 
 * @class MODx.combo.Language
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-language
 */
MODx.combo.Language = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'language'
		,hiddenName: 'language'
		,displayField: 'text'
		,valueField: 'value'
		,fields: ['value','text']
		,forceSelection: true
		,typeAhead: false
		,editable: false
		,allowBlank: false
		,url: MODx.config.connectors_url+'system/language.php'
	});
	MODx.combo.Language.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Language,MODx.combo.ComboBox);
Ext.reg('combo-language',MODx.combo.Language);

/**
 * Displays a dropdown list of available charsets.
 * 
 * @class MODx.combo.Charset
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-charset
 */
MODx.combo.Charset = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'charset'
		,hiddenName: 'charset'
		,displayField: 'text'
		,valueField: 'value'
		,fields: ['value','text']
		,forceSelection: true
		,typeAhead: false
		,editable: false
		,allowBlank: false
		,listWidth: 300
		,url: MODx.config.connectors_url+'system/charset.php'
	});
	MODx.combo.Charset.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Charset,MODx.combo.ComboBox);
Ext.reg('combo-charset',MODx.combo.Charset);

/**
 * Displays a dropdown list of available RTEs.
 * 
 * @class MODx.combo.RTE
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-rte
 */
MODx.combo.RTE = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'rte'
		,hiddenName: 'rte'
		,displayField: 'value'
		,valueField: 'value'
		,fields: ['value']
		,forceSelection: true
		,typeAhead: false
		,editable: false
		,allowBlank: false
		,listWidth: 300
		,url: MODx.config.connectors_url+'system/rte.php'
	});
	MODx.combo.RTE.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.RTE,MODx.combo.ComboBox);
Ext.reg('combo-rte',MODx.combo.RTE);

/**
 * Displays a dropdown list of available Roles.
 * 
 * @class MODx.combo.Role
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-role
 */
MODx.combo.Role = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'role'
        ,hiddenName: 'role'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'security/role.php'
        ,baseParams: { action: 'getList', addNone: true }
    });
    MODx.combo.Role.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Role,MODx.combo.ComboBox);
Ext.reg('combo-role',MODx.combo.Role);

/**
 * Displays a dropdown list of available Content Types.
 * 
 * @class MODx.combo.ContentType
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-contenttype
 */
MODx.combo.ContentType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'content_type'
        ,hiddenName: 'content_type'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'system/contenttype.php'
        ,baseParams: { action: 'getList' }
    });
    MODx.combo.ContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ContentType,MODx.combo.ComboBox);
Ext.reg('combo-contenttype',MODx.combo.ContentType);

/**
 * Displays a content disposition combo
 * 
 * @class MODx.combo.ContentDisposition
 * @extends Ext.form.ComboBox
 * @constructor
 * @xtype combo-boolean
 */
MODx.combo.ContentDisposition = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [[_('inline'),0],[_('attachment'),1]]
        })
        ,name: 'content_dispo'
        ,width: 200
        ,displayField: 'd'
        ,valueField: 'v'
        ,value: 0
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
    });
    MODx.combo.ContentDisposition.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ContentDisposition,Ext.form.ComboBox);
Ext.reg('combo-content-disposition',MODx.combo.ContentDisposition);
