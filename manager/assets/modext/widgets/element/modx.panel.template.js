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
        ,items: [{
            html: '<h2>'+_('template_new')+'</h2>'
            ,id: 'template-header'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            xtype: 'portal'
            ,items: [{
                columnWidth: 1
                ,style:'padding:10px;'
                ,defaults: {
                    collapsible: true
                    ,autoHeight: true
                    ,titleCollapse: true
                    ,draggable: true
                    ,style: 'padding: 5px 0;'
                }
                ,items: [{
                    title: _('template_title')
                    ,bodyStyle: 'padding: 1.5em;'
                    ,defaults: { border: false ,msgTarget: 'side' }
                    ,layout: 'form'
                    ,items: [{
                        html: '<p>'+_('template_msg')+'</p>'
                    },{
                        xtype: 'hidden'
                        ,name: 'id'
                        ,id: 'template-id'
                        ,value: config.template
                    },{
                        xtype: 'hidden'
                        ,name: 'props'
                        ,value: null
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('template_name')
                        ,name: 'templatename'
                        ,id: 'template-templatename'
                        ,width: 300
                        ,maxLength: 100
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                Ext.getCmp('template-header').getEl().update('<h2>'+_('template')+': '+f.getValue()+'</h2>');
                            }}
                        }
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('template_desc')
                        ,name: 'description'
                        ,id: 'template-description'
                        ,width: 300
                        ,maxLength: 255
                    },{
                        xtype: 'combo-category'
                        ,fieldLabel: _('category')
                        ,name: 'category'
                        ,id: 'template-category'
                        ,width: 250
                        ,value: config.category || null
                    },{
                        xtype: 'checkbox'
                        ,fieldLabel: _('template_lock')
                        ,description: _('template_lock_msg')
                        ,name: 'locked'
                        ,id: 'template-locked'
                    },{
                    	html: onTempFormRender
                    	,border: false
                    },{
                        html: '<br />'+_('template_code')
                    },{
                        xtype: 'textarea'
                        ,hideLabel: true
                        ,name: 'content'
                        ,id: 'template-content'
                        ,width: '95%'
                        ,height: 400
                    }]
                },{
                    xtype: 'panel-element-properties'
                    ,collapsible: true
                    ,elementPanel: 'panel-template'
                    ,elementId: config.template
                    ,elementType: 'modTemplate'
                },{
                   xtype: 'grid-template-tv'
                   ,id: 'grid-template-tv'
                   ,preventRender: true
                   ,width: '100%'
                   ,template: config.template
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
    MODx.panel.Template.superclass.constructor.call(this,config);
    Ext.getCmp('modx-element-tree-panel').expand();
};
Ext.extend(MODx.panel.Template,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.config.template === '' || this.config.template === 0 || this.initialized) {            
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
                    Ext.getCmp('template-header').getEl().update('<h2>'+_('template')+': '+r.object.templatename+'</h2>');
                    this.fireEvent('ready',r.object);

                    var d = Ext.decode(r.object.data);
                    var g = Ext.getCmp('grid-element-properties');
                    g.defaultProperties = d;
                    g.getStore().loadData(d);
                    this.initialized = true;
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('grid-template-tv');
        Ext.apply(o.form.baseParams,{
            tvs: g.encodeModified()
        });
    }
    ,success: function(o) {
        Ext.getCmp('grid-element-properties').save();
        Ext.getCmp('grid-template-tv').getStore().commitChanges();
        
        var t = parent.Ext.getCmp('modx_element_tree');
        var c = Ext.getCmp('template-category').getValue();
        var u = c != '' && c != null ? 'n_template_category_'+c : 'n_type_template'; 
        t.refreshNode(u,true);
    }
});
Ext.reg('panel-template',MODx.panel.Template);