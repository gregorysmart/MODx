/**
 * @class MODx.panel.Static
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-static
 */
MODx.panel.Static = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,baseParams: {}
        ,id: 'panel-static'
        ,class_key: 'modStaticResource'
        ,resource: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
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
                title: _('document_setting')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    html: '<h2>'+_('general_settings')+'</h2>'
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,value: config.resource
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('document_title')
                    ,description: _('document_title_help')
                    ,name: 'pagetitle'
                    ,width: 300
                    ,maxLength: 255
                    ,allowBlank: false
                    
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('long_title')
                    ,description: _('document_long_title_help')
                    ,name: 'longtitle'
                    ,width: 300
                    ,maxLength: 255
                    
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('document_description')
                    ,description: _('document_description_help')
                    ,name: 'description'
                    ,width: 300
                    ,maxLength: 255
                    
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('document_alias')
                    ,description: _('document_alias_help')
                    ,name: 'alias'
                    ,width: 300
                    ,maxLength: 100
                    
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('link_attributes')
                    ,description: _('link_attributes_help')
                    ,name: 'link_attributes'
                    ,width: 300
                    ,maxLength: 255
                    
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('content')
                    ,name: 'content'
                    ,width: 300
                    ,maxLength: 255
                    ,value: ''
                    
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('document_summary')
                    ,description: _('document_summary_help')
                    ,name: 'introtext'
                    ,width: 300
                    ,grow: true
                    
                },{
                    xtype: 'combo-template'
                    ,fieldLabel: _('page_data_template')
                    ,description: _('page_data_template_help')
                    ,name: 'template'
                    ,id: 'tpl'
                    ,width: 300
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                    ,listeners: {
                        'select': {fn: this.templateWarning,scope: this}
                    }
                    ,value: config.template
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('document_opt_menu_title')
                    ,description: _('document_opt_menu_title_help')
                    ,name: 'menutitle'
                    ,width: 300
                    ,maxLength: 255
                    
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('document_opt_show_menu')
                    ,description: _('document_opt_show_menu_help')
                    ,name: 'hidemenu'
                    ,inputValue: 1
                    ,checked: true
                    
                }]
            },{
                title: _('settings_page_settings')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    html: '<h2>'+_('resource_settings')+'</h2>'
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('document_opt_folder')
                    ,description: _('document_opt_folder_help')
                    ,name: 'isfolder'
                    ,inputValue: 1
                    
                },(config.publish_document ? {
                    xtype: 'checkbox'
                    ,fieldLabel: _('document_opt_published')
                    ,description: _('document_opt_published_help')
                    ,name: 'published'
                    ,inputValue: 1
                    ,checked: MODx.config.publish_default == '1' ? true : false
                    
                }:{}),(config.publish_document ? {
                    xtype: 'datefield'
                    ,fieldLabel: _('page_data_publishdate')
                    ,description: _('page_data_publishdate_help')
                    ,name: 'pub_date'
                    ,format: 'd-m-Y H:i:s'
                    ,allowBlank: true
                    ,width: 200
                    
                }:{}),(config.publish_document ? {
                    xtype: 'datefield'
                    ,fieldLabel: _('page_data_unpublishdate')
                    ,description: _('page_data_unpublishdate_help')
                    ,name: 'unpub_date'
                    ,format: 'd-m-Y H:i:s'
                    ,allowBlank: true
                    ,width: 200
                    
                }:{}),{
                    xtype: 'checkbox'
                    ,fieldLabel: _('page_data_searchable')
                    ,description: _('page_data_searchable_help')
                    ,name: 'searchable'
                    ,inputValue: 1
                    ,checked: MODx.config.search_default == '1' ? true : false
                    
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('document_opt_emptycache')
                    ,description: _('document_opt_emptycache_help')
                    ,name: 'syncsite'
                    ,inputValue: 1
                    ,checked: true
                    
                },{
                    xtype: 'hidden'
                    ,name: 'parent'
                    ,id: 'resource-parent'
                    ,value: config.parent || 0
                },{
                    xtype: 'hidden'
                    ,name: 'class_key'
                    ,id: 'class_key'
                    ,value: config.class_key || 'modDocument'
                    
                },{
                    xtype: 'hidden'
                    ,name: 'type'
                    ,value: 'document'
                    
                },{
                    xtype: 'hidden'
                    ,name: 'context_key'
                    ,id: 'context_key'
                    ,value: config.context_key || 'web'
                }]
            },{
                xtype: 'panel-resource-tv'
                ,resource: config.resource
                ,class_key: config.class_key
                ,template: config.template
                
            },(config.access_permissions ? {
                contentEl: 'tab_access'
                ,title: _('access_permissions')
                
            } : {})]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'success': {fn:this.success,scope:this}
        }
    });
    MODx.panel.Static.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Static,MODx.FormPanel,{
    setup: function() {
        if (this.config.resource === '' || this.config.resource === 0) {
            this.fireEvent('ready');
            return false;
        }
        Ext.Ajax.request({
            url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'get'
                ,id: this.config.resource
                ,class_key: this.config.class_key
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    if (r.object.pub_date == '0') { r.object.pub_date = ''; }
                    if (r.object.unpub_date == '0') { r.object.unpub_date = ''; }
                    this.getForm().setValues(r.object);
                    this.fireEvent('ready');
                } else { MODx.form.Handler.errorJSON(r); }
            }
        });
    }
    ,beforeSubmit: function(o) {        
        var g = Ext.getCmp('grid-resource-security');
        Ext.apply(o.form.baseParams,{
            resource_groups: g.encodeModified()
        });
    }

    ,success: function(o) {
        Ext.getCmp('grid-resource-security').getStore().commitChanges();
        var t = parent.Ext.getCmp('modx_resource_tree');
        var ctx = Ext.getCmp('context_key').getValue();
        var pa = Ext.getCmp('resource-parent').getValue();
        t.refreshNode(ctx+'_'+pa,true);
    }
    
    
    ,templateWarning: function() {
        var t = Ext.getCmp('tpl');
        if (!t) { return false; }
        // if selection isn't the current value (originalValue), then show dialog
        if(t.getValue() != t.originalValue) {
            Ext.Msg.confirm(_('tmplvar_change_template'), _('tmplvar_change_template_msg'), function(e) {
                if (e == 'yes') {
                    var tvpanel = Ext.getCmp('panel-resource-tv');
                    if(tvpanel && tvpanel.body) {
                        // update the Template Variables tab
                        this.tvum = tvpanel.body.getUpdater();
                        this.tvum.update({
                            url: 'index.php?a='+MODx.action['resource/tvs']
                            ,params: {
                                class_key: this.config.class_key
                                ,resource: this.config.resource
                                ,template: t.getValue()
                            }
                            ,discardUrl: true
                            ,scripts: true
                            ,nocache: true
                        });
                    }
                    t.originalValue = t.getValue(); // so that the next reset will work logically
                } else {
                    t.reset();
                }
            },this);
        }
    }
});
Ext.reg('panel-static',MODx.panel.Static);


// global accessor for TV dynamic fields
var triggerDirtyField = function(fld) {
    Ext.getCmp('panel-resource').fieldChangeEvent(fld);
};
var triggerRTEOnChange = function(i) {
    triggerDirtyField(Ext.getCmp('ta'));
}
var loadRTE = null;