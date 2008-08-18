Ext.namespace('MODx.grid','MODx.window');
/**
 * Loads a grid for managing lexicons.
 * 
 * @class MODx.grid.Lexicon
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype grid-lexicon
 */
MODx.grid.Lexicon = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('lexicon')
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,fields: ['id','name','value','namespace','focus','language','editedon','menu']
		,baseParams: {
			action: 'getList'
			,namespace: 'core'
			,focus: 'default'
		}
        ,width: '97%'
        ,paging: true
        ,autosave: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,width: 500
            ,sortable: false
            ,editor: { xtype: 'textarea' }
        },{
            header: _('last_modified')
            ,dataIndex: 'editedon'
            ,width: 100
            
        }]
        ,tbar: [{
			xtype: 'combo-namespace'
			,name: 'namespace'
			,id: 'filter_namespace'
			,value: 'core'
			,listeners: {
				'change': {fn: this.changeNamespace,scope:this}
			}
		},{
			xtype: 'combo-lexicon-focus'
			,name: 'focus'
			,id: 'filter_focus'
			,value: 'default'
            ,listeners: {
                'change': {fn:this.filter.createDelegate(this,['focus'],true),scope:this}
            }
		},{
			xtype: 'combo-language'
			,name: 'language'
			,id: 'filter_language'
			,value: 'en'
            ,listeners: {
                'change': {fn:this.filter.createDelegate(this,['language'],true),scope:this}
            }
		}
		,'->'
		,{
		    text: _('search_by_key')
		},{
		    xtype: 'textfield'
		    ,name: 'name'
		    ,id: 'filter_name'
            ,listeners: {
                'change': {fn:this.filter.createDelegate(this,['name'],true),scope:this}
                ,'render': {fn:function(tf) {
                    tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
                        tf.fireEvent('change'); 
                    },this);
                }}
            }
		},{
            text: _('create_new')
			,xtype: 'button'
			,menu: [{
			    text: _('entry')
				,handler: this.loadWindow2.createDelegate(this,['window-lexicon-entry-create'],true)
				,scope: this
			},{
				text: _('focus')
                ,handler: this.loadWindow2.createDelegate(this,['window-lexicon-focus-create'],true)
                ,scope: this
			},{
				text: _('namespace')
                ,handler: this.loadWindow2.createDelegate(this,['window-namespace-create'],true)
                ,scope: this
			}]
        }]
        ,pagingItems: [{
            text: _('reload_from_base')
            ,handler: this.reloadFromBase
            ,scope: this
        }
        ,'-'
        ,{
            xtype: 'button'
            ,text: _('lexicon_import')
            ,handler: function(btn,e) { this.loadWindow2(btn,e,'window-lexicon-import'); }
            ,scope: this
        }]
    });
    MODx.grid.Lexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Lexicon,MODx.grid.Grid,{
    filter: function(cb,nv,ov,name) {
    	if (!name) return false;
    	this.store.baseParams[name] = nv;
    	this.refresh();
    }
    ,changeNamespace: function(cb,nv,ov) {
    	var s = Ext.getCmp('filter_focus').store;
    	s.baseParams.namespace = nv;
    	s.reload();
    	
    	this.filter(cb,nv,ov,'namespace');
    }
    ,loadWindow2: function(btn,e,xtype) {
    	this.menu.record = {
            namespace: Ext.getCmp('filter_namespace').getValue()
            ,focus: Ext.getCmp('filter_focus').getValue()
            ,language: Ext.getCmp('filter_language').getValue()
        };
    	this.loadWindow(btn, e, {
            xtype: xtype
        });
    }
    ,reloadFromBase: function() {
    	Ext.Ajax.timeout = 0;
    	Ext.Ajax.request({
    	   url: this.config.url
    	   ,params: { action: 'reloadFromBase' }
    	   ,scope: this
    	   ,success: function(r) {
    	       r = Ext.decode(r.responseText);
    	       if (r.success) {
    	          this.refresh();
    	       } else FormHandler.errorJSON(r);
    	   }
    	});
    }
});
Ext.reg('grid-lexicon',MODx.grid.Lexicon);

/**
 * Generates the create lexicon entry window.
 *  
 * @class MODx.window.CreateLexiconEntry
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-lexicon-entry-create
 */
MODx.window.CreateLexiconEntry = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('entry_create')
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('key')
            ,name: 'name'
            ,width: 250
            ,maxLength: 100
        },{
            xtype: 'combo-lexicon-focus'
            ,fieldLabel: _('focus')
            ,name: 'focus'
            ,value: r.focus
        },{
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,value: r.namespace
        },{
            xtype: 'combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
            ,value: r.language
        },{
            xtype: 'textarea'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,width: 300
            ,grow: true
        }]
    });
    MODx.window.CreateLexiconEntry.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateLexiconEntry,MODx.Window);
Ext.reg('window-lexicon-entry-create',MODx.window.CreateLexiconEntry);


/**
 * Generates the update lexicon entry window.
 *  
 * @class MODx.window.UpdateLexiconEntry
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-lexicon-entry-update
 */
MODx.window.UpdateLexiconEntry = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('entry_update')
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,value: r.id
        },{
            xtype: 'textfield'
            ,fieldLabel: _('key')
            ,name: 'name'
            ,width: 250
            ,maxLength: 100
            ,value: r.name
        },{
            xtype: 'combo-lexicon-focus'
            ,fieldLabel: _('focus')
            ,name: 'focus'
            ,value: r.focus
        },{
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,value: r.namespace
        },{
            xtype: 'combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
            ,value: r.language
        },{
            xtype: 'textarea'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,width: 300
            ,grow: true
            ,value: r.value
        }]
    });
    MODx.window.UpdateLexiconEntry.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateLexiconEntry,MODx.Window);
Ext.reg('window-lexicon-entry-update',MODx.window.UpdateLexiconEntry);

/**
 * Generates the create lexicon focus window.
 *  
 * @class MODx.window.CreateLexiconFocus
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-lexicon-focus-create
 */
MODx.window.CreateLexiconFocus = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('focus_create')
        ,url: MODx.config.connectors_url+'workspace/lexicon/focus.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,width: 250
            ,maxLength: 100
        },{
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,value: r.namespace
        }]
    });
    MODx.window.CreateLexiconFocus.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateLexiconFocus,MODx.Window);
Ext.reg('window-lexicon-focus-create',MODx.window.CreateLexiconFocus);


/**
 * Generates the import lexicon window.
 *  
 * @class MODx.window.ImportLexicon
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-lexicon-import
 */
MODx.window.ImportLexicon = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('lexicon_import')
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,action: 'import'
        ,fileUpload: true
        ,fields: [{
            html: _('lexicon_import_desc')
            ,border: false
            ,bodyStyle: 'margin: 1em;'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('lexicon')
            ,name: 'lexicon'
            ,width: 250
            ,inputType: 'file'
        },{
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('focus')
            ,name: 'focus'
        },{
            xtype: 'combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
        }]
    });
    MODx.window.ImportLexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ImportLexicon,MODx.Window);
Ext.reg('window-lexicon-import',MODx.window.ImportLexicon);
