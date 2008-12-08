/**
 * @class MODx.panel.Context
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-context
 */
MODx.panel.Context = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'context/index.php'
        ,baseParams: {}
        ,id: 'panel-context'
        ,class_key: 'modContext'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: {
            xtype: 'tabpanel'
            ,activeTab: 0
            ,deferredRender: false
            ,border: false
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,bodyStyle: 'padding: 1.5em;'
                ,labelWidth: 150
            }
            ,items: [{
                title: _('general_information')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    html: '<h2>'+_('context')+': '+config.context+'</h2>'
                    ,id: 'context-name'
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('key')
                    ,name: 'newkey'
                    ,width: 300
                    ,maxLength: 255
                    ,enableKeyEvents: true
                    ,allowBlank: false
                    ,value: config.context
                    ,submitValue: true
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,width: 300
                    ,grow: true
                }]
            },{
                title: _('context_settings')
                ,items: [{
                    html: '<h2>'+_('context_settings')+'</h2>'
                    ,border: false
                },{
                    xtype: 'grid-context-settings'
                    ,id: 'grid-context-setting'
                    ,preventRender: true
                    ,context_key: config.context
                }]
            }]
        }
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Context,MODx.FormPanel,{
    setup: function() {
        if (this.config.context === '' || this.config.context === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,key: this.config.context
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    var el = Ext.getCmp('context-name');
                    if (el) { el.getEl().update('<h2>'+_('context')+': '+r.object.key+'</h2>'); }
                    this.fireEvent('ready');
            	},scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('grid-context-setting');
        Ext.apply(o.form.baseParams,{
            settings: g.encodeModified()
        });
    }
    ,success: function(o) {
        Ext.getCmp('grid-context-setting').getStore().commitChanges();
        
        var t = parent.Ext.getCmp('modx_element_tree');        
        t.refreshNode(this.config.context+'_0',true);
    }
});
Ext.reg('panel-context',MODx.panel.Context);