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
        ,template: ''
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
                title: _('tv_title')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    html: '<h2>'+_('tv')+': </h2>'
                    ,id: 'tv-name'
                },{
                    html: '<p>'+_('tv_msg')+'</p>'
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,value: config.template
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
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
                    html: '<br />'+_('tv_code')
                },{
                    xtype: 'textarea'
                    ,hideLabel: true
                    ,name: 'content'
                    ,width: '95%'
                    ,height: 400
                }]
            },{
               xtype: 'grid-template-tv'
               ,id: 'grid-template-tv'
               ,preventRender: true
               ,template: config.template
               ,bodyStyle: ''
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
                } else FormHandler.errorJSON(r);
            }
        })
    }
});
Ext.reg('panel-tv',MODx.panel.TV);