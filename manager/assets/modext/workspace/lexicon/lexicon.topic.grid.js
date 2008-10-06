/**
 * Loads a grid for managing lexicon topics.
 * 
 * @class MODx.grid.LexiconTopic
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype grid-lexicon-topic
 */
MODx.grid.LexiconTopic = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('lexicon_topics')
        ,url: MODx.config.connectors_url+'workspace/lexicon/topic.php'
        ,fields: ['id','name','namespace','menu']
        ,baseParams: {
            action: 'getList'
            ,namespace: 'core'
        }
        ,saveParams: {
        	namespace: 'core'
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
                text: _('topic')
                ,handler: this.loadWindow2.createDelegate(this,['window-lexicon-topic-create'],true)
                ,scope: this
            },{
                text: _('namespace')
                ,handler: this.loadWindow2.createDelegate(this,['window-namespace-create'],true)
                ,scope: this
            }]
        }]
    });
    MODx.grid.LexiconTopic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.LexiconTopic,MODx.grid.Grid,{
    filter: function(cb,nv,ov,name) {
        if (!name) { return false; }
        this.store.baseParams[name] = nv;
        this.config.saveParams[name] = nv;
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
Ext.reg('grid-lexicon-topic',MODx.grid.LexiconTopic);

/**
 * Generates the create lexicon topic window.
 *  
 * @class MODx.window.CreateLexiconTopic
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype window-lexicon-topic-create
 */
MODx.window.CreateLexiconTopic = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('topic_create')
        ,url: MODx.config.connectors_url+'workspace/lexicon/topic.php'
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
    MODx.window.CreateLexiconTopic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateLexiconTopic,MODx.Window);
Ext.reg('window-lexicon-topic-create',MODx.window.CreateLexiconTopic);
