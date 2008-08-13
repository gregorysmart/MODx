Ext.namespace('MODx.grid','MODx.window');
/**
 * Loads a grid for managing lexicon foci.
 * 
 * @class MODx.grid.LexiconFoci
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype grid-lexicon-foci
 */
MODx.grid.LexiconFoci = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('lexicon_foci')
        ,url: MODx.config.connectors_url+'workspace/lexicon/focus.php'
        ,fields: ['id','name','namespace','menu']
        ,baseParams: {
            action: 'getList'
            ,namespace: 'core'
        }
        ,width: '97%'
        ,paging: true
        ,autosave: true
        ,primaryKey: 'name'
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },{
            header: _('namespace')
            ,dataIndex: 'namespace'
            ,width: 500
            ,sortable: false
            ,editor: { 
                xtype: 'combo-namespace'
                ,renderer: true
            }
        }]
        ,tbar: [{
            xtype: 'combo-namespace'
            ,name: 'namespace'
            ,id: 'lf_filter_namespace'
            ,value: 'core'
            ,listeners: {
                'change': {fn:this.filter.createDelegate(this,['namespace'],true),scope:this}
            }
        },'->',{
            text: _('search_by_key')
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'lf_filter_name'
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
                text: _('focus')
                ,handler: this.loadWindow2.createDelegate(this,['window-lexicon-focus-create'],true)
                ,scope: this
            },{
                text: _('namespace')
                ,handler: this.loadWindow2.createDelegate(this,['window-namespace-create'],true)
                ,scope: this
            }]
        }]
    });
    MODx.grid.LexiconFoci.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.LexiconFoci,MODx.grid.Grid,{
    filter: function(cb,nv,ov,name) {
        if (!name) return false;
        this.store.baseParams[name] = nv;
        this.refresh();
    }
    ,loadWindow2: function(btn,e,xtype) {
        this.menu.record = {
            namespace: Ext.getCmp('lf_filter_namespace').getValue()
        };
        this.loadWindow(btn, e, {
            xtype: xtype
        });
    }
});
Ext.reg('grid-lexicon-foci',MODx.grid.LexiconFoci);

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
