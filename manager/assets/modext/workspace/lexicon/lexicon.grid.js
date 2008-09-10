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
        ,width: '98%'
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
            text: _('namespace')+':'
        },{
			xtype: 'combo-namespace'
			,name: 'namespace'
			,id: 'filter_namespace'
			,value: 'core'
			,listeners: {
				'select': {fn: this.changeNamespace,scope:this}
			}
		},{
		    text: _('focus')+':'
		},{
			xtype: 'combo-lexicon-focus'
			,name: 'focus'
			,id: 'filter_focus'
			,value: 'default'
            ,listeners: {
                'select': {fn:this.filter.createDelegate(this,['focus'],true),scope:this}
            }
		},{
		    text: _('language')+':'
		},{
			xtype: 'combo-language'
			,name: 'language'
			,id: 'filter_language'
			,value: 'en'
            ,listeners: {
                'select': {fn:this.filter.createDelegate(this,['language'],true),scope:this}
            }
		},{
            text: _('create_new')
            ,xtype: 'button'
            ,menu: [{
                text: _('entry')
                ,handler: this.loadWindow2.createDelegate(this,[{ xtype: 'window-lexicon-entry-create'}],true)
                ,scope: this
            },{
                text: _('focus')
                ,handler: this.loadWindow2.createDelegate(this,[{ xtype: 'window-lexicon-focus-create'}],true)
                ,scope: this
            },{
                text: _('namespace')
                ,handler: this.loadWindow2.createDelegate(this,[{
                	   xtype: 'window-namespace-create'
                	   ,listeners: {
                            'success':{fn: function() {
                    	       Ext.getCmp('filter_namespace').store.reload();
                            },scope: this}
                	   }
                }],true)
                ,scope: this
            }]
        }
		,'->'
		,{
		    xtype: 'textfield'
		    ,name: 'name'
		    ,id: 'filter_name'
		    ,emptyText: _('search')+'...'
            ,listeners: {
                'change': {fn:this.filter.createDelegate(this,['name'],true),scope:this}
                ,'render': {fn:function(tf) {
                    tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
                        tf.fireEvent('change'); 
                    },this);
                }}
            }
		},{
            xtype: 'button'
            ,id: 'filter_clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
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
            ,handler: function(btn,e) { this.loadWindow2(btn,e,{ xtype: 'window-lexicon-import'}); }
            ,scope: this
        },{
            xtype: 'button'
            ,text: _('lexicon_export')
            ,handler: function(btn,e) { this.loadWindow2(btn,e,{ xtype: 'window-lexicon-export'}); }
            ,scope: this
        }]
    });
    MODx.grid.Lexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Lexicon,MODx.grid.Grid,{
    filter: function(cb,r,i,name) {
    	if (!name) return false;
    	this.store.baseParams[name] = cb.getValue();
    	this.getBottomToolbar().changePage(1);
    	this.refresh();
    }
    ,clearFilter: function() {
    	this.store.baseParams = {
    		action: 'getList'
            ,namespace: 'core'
            ,focus: 'default'
            ,language: 'en'
    	};
    	this.getBottomToolbar().changePage(1);
    	Ext.getCmp('filter_namespace').setValue('core');
    	Ext.getCmp('filter_focus').setValue('default');
    	Ext.getCmp('filter_language').setValue('en');
    	this.refresh();
    }
    ,changeNamespace: function(cb,nv,ov) {
    	var s = Ext.getCmp('filter_focus').store;
    	s.baseParams.namespace = cb.getValue();
    	s.reload();
    	
    	this.filter(cb,null,1,'namespace');
    }
    ,loadWindow2: function(btn,e,o) {
    	this.menu.record = {
            namespace: Ext.getCmp('filter_namespace').getValue()
            ,focus: Ext.getCmp('filter_focus').getValue()
            ,language: Ext.getCmp('filter_language').getValue()
        };
        var clef = Ext.getCmp('cle-focus');
        if (clef) { clef.store.baseParams.namespace = this.menu.record.namespace; }
    	this.loadWindow(btn, e, o);
    }
    ,reloadFromBase: function() {
    	Ext.Ajax.timeout = 0;
    	var topic = '/workspace/lexicon/reload/';
        if (this.console == null) {
            this.console = MODx.load({
               xtype: 'modx-console'
               ,register: 'mgr'
               ,topic: topic
            });
        } else {
            this.console.setRegister('mgr',topic);
        }
        this.console.show(Ext.getBody());
    	
    	MODx.Ajax.request({
    	   url: this.config.url
    	   ,params: { action: 'reloadFromBase' ,register: 'mgr' ,topic: topic }
    	   ,listeners: {
    	       'success': {fn:function(r) {
        	       this.console.complete();
                   this.refresh();
        	   },scope:this}
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
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,value: r.namespace
            ,listeners: {
            	'select': {fn: function(cb,r,i) {
                    cle = Ext.getCmp('cle-focus');
                    cle.store.baseParams.namespace = cb.getValue();
                    cle.store.reload();
                    cle.setValue('default');
            	},scope:this}
            }
        },{
            xtype: 'combo-lexicon-focus'
            ,fieldLabel: _('focus')
            ,name: 'focus'
            ,id: 'cle-focus'
            ,value: r.focus
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
            ,id: 'ule-focus'
            ,value: r.focus
        },{
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,value: r.namespace
            ,listeners: {
                'select': {fn: function(cb,r,i) {
                    cle = Ext.getCmp('ule-focus');
                    cle.store.baseParams.namespace = cb.getValue();
                    cle.store.reload();
                    cle.setValue('default');
                },scope:this}
            }
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



/**
 * Generates the export lexicon window.
 *  
 * @class MODx.window.ExportLexicon
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-lexicon-export
 */
MODx.window.ExportLexicon = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('lexicon_export')
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,action: 'export'
        ,fileUpload: true
        ,fields: [{
            html: _('lexicon_export_desc')
            ,border: false
            ,bodyStyle: 'margin: 1em;'
        },{
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,listeners: {
                'select': {fn: function(cb,r,i) {
                    cle = Ext.getCmp('ex-cmb-focus');
                    cle.store.baseParams.namespace = cb.getValue();
                    cle.store.reload();
                    cle.setValue('default');
                },scope:this}
            }
        },{
            xtype: 'combo-lexicon-focus'
            ,fieldLabel: _('focus')
            ,name: 'focus'
            ,id: 'ex-cmb-focus'
        },{
            xtype: 'combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
        }]
    });
    MODx.window.ExportLexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ExportLexicon,MODx.Window);
Ext.reg('window-lexicon-export',MODx.window.ExportLexicon);
