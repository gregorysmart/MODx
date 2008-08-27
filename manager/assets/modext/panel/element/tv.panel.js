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
            xtype: 'tabpanel'
            ,activeTab: 0
            ,deferredRender: false
            ,border: false
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
                    ,id: 'tv-name'
                },{
                    html: '<p>'+_('tv_msg')+'</p>'
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,value: config.tv
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_name')
                    ,name: 'name'
                    ,width: 300
                    ,maxLength: 100
                    ,enableKeyEvents: true
                    ,allowBlank: false
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('tv-name').getEl().update('<h2>'+_('tv')+': '+f.getValue()+'</h2>');
                        }}
                    }
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_caption')
                    ,name: 'caption'
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,width: 300
                    ,maxLength: 255
                },{
                    xtype: 'combo-category'
                    ,fieldLabel: _('category')
                    ,name: 'category'
                    ,width: 250
                    ,value: config.category || null
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('tv_lock')
                    ,description: _('tv_lock_msg')
                    ,name: 'locked'
                },{
                    xtype: 'numberfield'
                    ,fieldLabel: _('tv_rank')
                    ,name: 'rank'
                    ,width: 50
                    ,maxLength: 4
                    ,allowNegative: false
                    ,allowBlank: false
                    ,value: 0
                }]
            },{
                title: _('rendering_options')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,bodyStyle: 'padding: 1.5em;'
                ,items: [{
                    xtype: 'combo-tv-input-type'
                    ,fieldLabel: _('tv_type')
                    ,name: 'type'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_elements')
                    ,name: 'els'
                    ,width: 250
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('tv_default')
                    ,name: 'default_text'
                    ,width: 300
                    ,grow: true
                },{
                    xtype: 'combo-tv-widget'
                    ,fieldLabel: _('tv_output_type')
                    ,name: 'display'
                    ,hiddenName: 'display'
                    ,id: 'combo-tv-widget'
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
            },{
                xtype: 'grid-tv-security'
                ,id: 'grid-tv-security'
                ,tv: config.tv
                ,preventRender: true
            }]
        }
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.TV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.TV,MODx.FormPanel,{
    setup: function() {
        if (this.config.tv == '' || this.config.tv == 0) return;
        Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.tv
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    if (r.object.category == '0') r.object.category = null;
                    this.getForm().setValues(r.object);
                    Ext.getCmp('tv-name').getEl().update('<h2>'+_('tv')+': '+r.object.name+'</h2>');
                    
                    this.showParameters(Ext.getCmp('combo-tv-widget'));
                } else FormHandler.errorJSON(r);
            }
        })
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