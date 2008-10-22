/**
 * 
 * @class MODx.panel.Plugin
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-plugin
 */
MODx.panel.Plugin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/plugin.php'
        ,baseParams: {}
        ,id: 'panel-plugin'
        ,class_key: 'modPlugin'
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
                title: _('plugin_title')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    html: '<h2>'+_('plugin')+': '+config.name+'</h2>'
                    ,id: 'plugin-name'
                },{
                    html: '<p>'+_('plugin_msg')+'</p>'
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,value: config.plugin
                },{
                    xtype: 'hidden'
                    ,name: 'props'
                    ,value: null
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('plugin_name')
                    ,name: 'name'
                    ,width: 300
                    ,maxLength: 255
                    ,enableKeyEvents: true
                    ,allowBlank: false
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('plugin-name').getEl().update('<h2>'+_('plugin')+': '+f.getValue()+'</h2>');
                        }}
                    }
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('plugin_desc')
                    ,name: 'description'
                    ,width: 300
                    ,maxLength: 255
                },{
                    xtype: 'combo-category'
                    ,fieldLabel: _('category')
                    ,name: 'category'
                    ,id: 'category'
                    ,width: 250
                    ,value: config.category || null
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('plugin_disabled')
                    ,name: 'disabled'
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('plugin_lock')
                    ,description: _('plugin_lock_msg')
                    ,name: 'locked'
                },{
                    html: onPluginFormRender
                    ,border: false
                },{
                    html: '<br />'+_('plugin_code')
                },{
                    xtype: 'textarea'
                    ,hideLabel: true
                    ,name: 'plugincode'
                    ,width: '95%'
                    ,height: 400
                    ,value: "<?php\n\n?>"
                    
                }]
            },{
                title: _('system_events')
                ,items: [{
                    html: '<h2>'+_('system_events')+'</h2>'
                    ,border: false
                },{
                    html: '<p>'+_('plugin_event_msg')+'</p>'
                    ,border: false
                },{
                    xtype: 'grid-plugin-event'
                    ,id: 'grid-plugin-event'
                    ,preventRender: true
                    ,plugin: config.plugin
                    ,listeners: {
                        'rowdblclick': {fn:this.fieldChangeEvent,scope:this}
                    }
                }]             
            },{
                title: _('plugin_properties')
                ,xtype: 'panel'
                ,layout: 'form'
                ,border: false
                ,items: [{
                    xtype: 'grid-element-properties'
                    ,panel: 'panel-plugin'
                }]
            }]
        }
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.Plugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Plugin,MODx.FormPanel,{
    setup: function() {
        if (this.config.plugin === '' || this.config.plugin === 0) {            
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.plugin
            }
            ,listeners: {
            	'success': {fn:function(r) {
            		if (r.object.category == '0') { r.object.category = null; }
                    r.object.plugincode = "<?php\n"+r.object.plugincode+"\n?>";
                    this.getForm().setValues(r.object);
                    Ext.getCmp('plugin-name').getEl().update('<h2>'+_('plugin')+': '+r.object.name+'</h2>');
                    this.fireEvent('ready',r.object);
                    
                    var d = Ext.decode(r.object.data);
                    Ext.getCmp('grid-element-properties').getStore().loadData(d);
            	},scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('grid-plugin-event');
        var h = Ext.getCmp('grid-element-properties');
        Ext.apply(o.form.baseParams,{
            events: g.encodeModified()
            ,propdata: h.encode()
        });
    }
    ,success: function(o) {
        Ext.getCmp('grid-element-properties').getStore().commitChanges();
        Ext.getCmp('grid-plugin-event').getStore().commitChanges();
        
        var t = parent.Ext.getCmp('modx_element_tree');
        var c = Ext.getCmp('category').getValue();
        var u = c != '' && c != null ? 'n_plugin_category_'+c : 'n_type_plugin'; 
        t.refreshNode(u,true);
    }
});
Ext.reg('panel-plugin',MODx.panel.Plugin);