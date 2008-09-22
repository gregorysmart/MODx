/**
 * Loads the Template panel
 * 
 * @class MODx.panel.Template
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-template
 */
MODx.panel.Template = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/template.php'
        ,baseParams: {}
        ,id: 'panel-template'
        ,class_key: 'modTemplate'
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
                title: _('template_title')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    html: '<h2>'+_('template')+': </h2>'
                    ,id: 'template-name'
                },{
                    html: '<p>'+_('template_msg')+'</p>'
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,value: config.template
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('template_name')
                    ,name: 'templatename'
                    ,width: 300
                    ,maxLength: 100
                    ,enableKeyEvents: true
                    ,allowBlank: false
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('template-name').getEl().update('<h2>'+_('template')+': '+f.getValue()+'</h2>');
                        }}
                    }
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('template_desc')
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
                    ,fieldLabel: _('template_lock')
                    ,description: _('template_lock_msg')
                    ,name: 'locked'
                },{
                	html: onTempFormRender
                	,border: false
                },{
                    html: '<br />'+_('template_code')
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
                ,listeners: {
                    'rowdblclick': {fn:this.fieldChangeEvent,scope:this}
                }
            }]
        }
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.Template.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Template,MODx.FormPanel,{
    setup: function() {
        if (this.config.template === '' || this.config.template === 0) {            
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.template
            }
            ,listeners: {
                'success': {fn:function(r) {
                    if (r.object.category == '0') { r.object.category = null; }
                    this.getForm().setValues(r.object);
                    Ext.getCmp('template-name').getEl().update('<h2>'+_('template')+': '+r.object.templatename+'</h2>');
                    this.fireEvent('ready',r.object);
                },scope:this}
            }
        });
    }
});
Ext.reg('panel-template',MODx.panel.Template);