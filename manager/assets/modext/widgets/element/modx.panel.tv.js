/**
 * Loads the TV panel
 * 
 * @class MODx.panel.TV
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-tv
 */
MODx.panel.TV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/tv.php'
        ,baseParams: {}
        ,id: 'modx-panel-tv'
        ,class_key: 'modTemplateVar'
        ,tv: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('tv_new')+'</h2>'
            ,id: 'modx-tv-header'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            xtype: 'portal'
            ,items: [{
                columnWidth: 1
                ,items: [{
                    title: _('general_information')
                    ,defaults: { border: false ,msgTarget: 'side' }
                    ,bodyStyle: 'padding: 1.5em;'
                    ,layout: 'form'
                    ,labelWidth: 150
                    ,items: [{
                        html: '<p>'+_('tv_msg')+'</p>'
                    },{
                        xtype: 'hidden'
                        ,name: 'id'
                        ,id: 'modx-tv-id'
                        ,value: config.tv
                    },{
                        xtype: 'hidden'
                        ,name: 'props'
                        ,id: 'modx-tv-props'
                        ,value: null
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('tv_name')
                        ,name: 'name'
                        ,id: 'modx-tv-name'
                        ,width: 300
                        ,maxLength: 100
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                Ext.getCmp('modx-tv-header').getEl().update('<h2>'+_('tv')+': '+f.getValue()+'</h2>');
                            }}
                        }
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('tv_caption')
                        ,name: 'caption'
                        ,id: 'modx-tv-caption'
                        ,width: 300
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('description')
                        ,name: 'description'
                        ,id: 'modx-tv-description'
                        ,width: 300
                        ,maxLength: 255
                    },{
                        xtype: 'modx-combo-category'
                        ,fieldLabel: _('category')
                        ,name: 'category'
                        ,id: 'modx-tv-category'
                        ,width: 250
                        ,value: config.category || null
                    },{
                        xtype: 'checkbox'
                        ,fieldLabel: _('tv_lock')
                        ,description: _('tv_lock_msg')
                        ,name: 'locked'
                        ,id: 'modx-tv-locked'
                    },{
                        xtype: 'numberfield'
                        ,fieldLabel: _('tv_rank')
                        ,name: 'rank'
                        ,id: 'modx-tv-rank'
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
                    ,layout: 'form'                    
                    ,labelWidth: 150
                    ,items: [{
                        xtype: 'modx-combo-tv-input-type'
                        ,fieldLabel: _('tv_type')
                        ,name: 'type'
                        ,id: 'modx-tv-type'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('tv_elements')
                        ,name: 'els'
                        ,id: 'modx-tv-elements'
                        ,width: 250
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('tv_default')
                        ,name: 'default_text'
                        ,id: 'modx-tv-default-text'
                        ,width: 300
                        ,grow: true
                    },{
                        xtype: 'modx-combo-tv-widget'
                        ,fieldLabel: _('tv_output_type')
                        ,name: 'display'
                        ,hiddenName: 'display'
                        ,id: 'modx-tv-display'
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
                        ,id: 'modx-widget-props'
                    }]
                },{
                    xtype: 'modx-panel-element-properties'
                    ,elementPanel: 'modx-panel-tv'
                    ,elementId: config.tv
                    ,elementType: 'modTemplateVar'
                },{ 
                    xtype: 'modx-grid-tv-template'
                    ,tv: config.tv
                    ,preventRender: true
                    ,listeners: {
                        'rowdblclick': {fn:this.fieldChangeEvent,scope:this}
                    }
                },{
                    xtype: 'modx-grid-tv-security'
                    ,tv: config.tv
                    ,preventRender: true
                    ,listeners: {
                        'rowdblclick': {fn:this.fieldChangeEvent,scope:this}
                    }
                }]
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.TV.superclass.constructor.call(this,config);
    Ext.getCmp('modx-element-tree-panel').expand();
};
Ext.extend(MODx.panel.TV,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.config.tv === '' || this.config.tv === 0 || this.initialized) {
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
                    Ext.getCmp('modx-tv-header').getEl().update('<h2>'+_('tv')+': '+r.object.name+'</h2>');
                    
                    this.showParameters(Ext.getCmp('modx-tv-display'));
                    this.fireEvent('ready',r.object);

                    var d = Ext.decode(r.object.data);
                    var g = Ext.getCmp('modx-grid-element-properties');
                    g.defaultProperties = d;
                    g.getStore().loadData(d);
                    this.initialized = true;
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-tv-template');
        var rg = Ext.getCmp('modx-grid-tv-security');
        Ext.apply(o.form.baseParams,{
            templates: g.encodeModified()
            ,resource_groups: rg.encodeModified()
        });
    }
    ,success: function(o) {
        Ext.getCmp('modx-grid-tv-template').getStore().commitChanges();
        Ext.getCmp('modx-grid-tv-security').getStore().commitChanges();
        Ext.getCmp('modx-grid-element-properties').save();
        
        var t = parent.Ext.getCmp('modx_element_tree');
        var c = Ext.getCmp('modx-tv-category').getValue();
        var u = c != '' && c != null ? 'n_tv_category_'+c : 'n_type_tv'; 
        t.refreshNode(u,true);
    }
    
    ,showParameters: function(cb,rc,i) {
        Ext.get('modx-widget-props').load({
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
Ext.reg('modx-panel-tv',MODx.panel.TV);