/**
 * Loads the TV panel
 * 
 * @class MODx.panel.TV
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-tv
 */
MODx.panel.TV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/tv.php'
        ,baseParams: {}
        ,id: 'panel-tv'
        ,class_key: 'modTemplateVar'
        ,tv: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: {
            xtype: 'modx-tabs'
            ,resizeTabs: false
            ,deferredRender: false
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,labelWidth: 150
            }
            ,items: [{
                title: _('general_information')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,bodyStyle: 'padding: 1.5em;'
                ,items: [{
                    html: '<h2>'+_('tv')+': </h2>'
                    ,id: 'tv-header'
                },{
                    html: '<p>'+_('tv_msg')+'</p>'
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,id: 'tv-id'
                    ,value: config.tv
                },{
                    xtype: 'hidden'
                    ,name: 'props'
                    ,value: null
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_name')
                    ,name: 'name'
                    ,id: 'tv-name'
                    ,width: 300
                    ,maxLength: 100
                    ,enableKeyEvents: true
                    ,allowBlank: false
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('tv-header').getEl().update('<h2>'+_('tv')+': '+f.getValue()+'</h2>');
                        }}
                    }
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_caption')
                    ,name: 'caption'
                    ,id: 'tv-caption'
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,id: 'tv-description'
                    ,width: 300
                    ,maxLength: 255
                },{
                    xtype: 'combo-category'
                    ,fieldLabel: _('category')
                    ,name: 'category'
                    ,id: 'tv-category'
                    ,width: 250
                    ,value: config.category || null
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('tv_lock')
                    ,description: _('tv_lock_msg')
                    ,name: 'locked'
                    ,id: 'tv-locked'
                },{
                    xtype: 'numberfield'
                    ,fieldLabel: _('tv_rank')
                    ,name: 'rank'
                    ,id: 'tv-rank'
                    ,width: 50
                    ,maxLength: 4
                    ,allowNegative: false
                    ,allowBlank: false
                    ,value: 0
                },{
                    html: onTVFormRender
                    ,border: false
                }]
            },{
                title: _('rendering_options')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,bodyStyle: 'padding: 1.5em;'
                ,items: [{
                    xtype: 'combo-tv-input-type'
                    ,fieldLabel: _('tv_type')
                    ,name: 'type'
                    ,id: 'tv-type'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_elements')
                    ,name: 'els'
                    ,id: 'tv-els'
                    ,width: 250
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('tv_default')
                    ,name: 'default_text'
                    ,id: 'tv-default-text'
                    ,width: 300
                    ,grow: true
                },{
                    xtype: 'combo-tv-widget'
                    ,fieldLabel: _('tv_output_type')
                    ,name: 'display'
                    ,hiddenName: 'display'
                    ,id: 'tv-display'
                    ,listeners: {
                        'select': {fn:this.showParameters,scope:this}
                    }
                },{
                    autoLoad: {
                        url: MODx.config.connectors_url+'element/tv/renders.php'
                        ,method: 'GET'
                        ,params: {
                           'action': 'getProperties'
                           ,'context': 'mgr'
                           ,'tv': config.tv
                           ,'type': config.type || 'default' 
                        }
                        ,scripts: true
                    }
                    ,id: 'widget-props'
                }]
            },{ 
                xtype: 'grid-tv-template'
                ,id: 'grid-tv-templates'
                ,tv: config.tv
                ,preventRender: true
                ,listeners: {
                    'rowdblclick': {fn:this.fieldChangeEvent,scope:this}
                }
            },{
                xtype: 'grid-tv-security'
                ,id: 'grid-tv-security'
                ,tv: config.tv
                ,preventRender: true
                ,listeners: {
                    'rowdblclick': {fn:this.fieldChangeEvent,scope:this}
                }
            },{
                xtype: 'panel-element-properties'
                ,elementPanel: 'panel-tv'
                ,elementId: config.tv
                ,elementType: 'modTemplateVar'
            }]
        }
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.TV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.TV,MODx.FormPanel,{
    setup: function() {
        if (this.config.tv === '' || this.config.tv === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.tv
            }
            ,listeners: {
                'success': {fn:function(r) {
                    if (r.object.category == '0') { r.object.category = null; }
                    this.getForm().setValues(r.object);
                    Ext.getCmp('tv-header').getEl().update('<h2>'+_('tv')+': '+r.object.name+'</h2>');
                    
                    this.showParameters(Ext.getCmp('tv-display'));
                    this.fireEvent('ready',r.object);

                    var d = Ext.decode(r.object.data);
                    var g = Ext.getCmp('grid-element-properties');
                    g.defaultProperties = d;
                    g.getStore().loadData(d);
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('grid-tv-templates');
        var rg = Ext.getCmp('grid-tv-security');
        Ext.apply(o.form.baseParams,{
            templates: g.encodeModified()
            ,resource_groups: rg.encodeModified()
        });
    }
    ,success: function(o) {
        Ext.getCmp('grid-tv-templates').getStore().commitChanges();
        Ext.getCmp('grid-tv-security').getStore().commitChanges();
        Ext.getCmp('grid-element-properties').save();
        
        var t = parent.Ext.getCmp('modx_element_tree');
        var c = Ext.getCmp('tv-category').getValue();
        var u = c != '' && c != null ? 'n_tv_category_'+c : 'n_type_tv'; 
        t.refreshNode(u,true);
    }
    
    ,showParameters: function(cb,rc,i) {
        Ext.get('widget-props').load({
            url: MODx.config.connectors_url+'element/tv/renders.php'
            ,method: 'GET'
            ,params: {
               'action': 'getProperties'
               ,'context': 'mgr'
               ,'tv': this.config.tv
               ,'type': cb.getValue() || 'default'
            }
            ,scripts: true
        });
    }
});
Ext.reg('panel-tv',MODx.panel.TV);