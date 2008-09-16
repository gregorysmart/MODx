/**
 * @class MODx.panel.Resource
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-resource
 */
MODx.panel.Resource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/document.php'
        ,baseParams: {}
        ,id: 'panel-resource'
        ,class_key: 'modDocument'
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
                    
                },(config.class_key == 'modWebLink' ? {
                    xtype: 'textfield'
                    ,fieldLabel: _('weblink')
                    ,description: _('document_weblink_help')
                    ,name: 'ta'
                    ,width: 300
                    ,maxLength: 255
                    ,value: 'http://'
                    
                } : {
                    xtype: 'textarea'
                    ,fieldLabel: _('document_summary')
                    ,description: _('document_summary_help')
                    ,name: 'introtext'
                    ,width: 300
                    ,grow: true
                    
                }),{
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
                    ,checked: false
                    
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
                    
                },(config.class_key == 'modDocument' ? {
                    xtype: 'checkbox'
                    ,fieldLabel: _('document_opt_richtext')
                    ,description: _('document_opt_richtext_help')
                    ,name: 'richtext'
                    ,inputValue: 1
                    
                } : {}),(config.publish_document ? {
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
                    ,format: 'Y-m-d'
                    ,allowBlank: true
                    ,width: 200
                    
                }:{}),(config.publish_document ? {
                    xtype: 'datefield'
                    ,fieldLabel: _('page_data_unpublishdate')
                    ,description: _('page_data_unpublishdate_help')
                    ,name: 'unpub_date'
                    ,format: 'Y-m-d'
                    ,allowBlank: true
                    ,width: 200
                    
                }:{}),{
                    xtype: 'checkbox'
                    ,fieldLabel: _('page_data_searchable')
                    ,description: _('page_data_searchable_help')
                    ,name: 'searchable'
                    ,inputValue: 1
                    ,checked: MODx.config.search_default == '1' ? true : false
                    
                },(config.class_key != 'modWebLink' ? {
                    xtype: 'checkbox'
                    ,fieldLabel: _('page_data_cacheable')
                    ,description: _('page_data_cacheable_help')
                    ,name: 'cacheable'
                    ,inputValue: 1
                    ,checked: MODx.config.cache_default == '1' ? true : false
                    
                }:{}),{
                    xtype: 'checkbox'
                    ,fieldLabel: _('document_opt_emptycache')
                    ,description: _('document_opt_emptycache_help')
                    ,name: 'syncsite'
                    ,inputValue: 1
                    ,checked: true
                    
                },(config.class_key != 'modWebLink' ? {
                    xtype: 'combo-contenttype'
                    ,fieldLabel: _('page_data_contentType')
                    ,description: _('page_data_contentType_help')
                    ,name: 'content_type'
                    ,width: 200
                    ,value: 1
                    
                }:{}),(config.class_key != 'modWebLink' ? {
                    xtype: 'combo-content-disposition'
                    ,fieldLabel: _('document_opt_contentdispo')
                    ,description: _('document_opt_contentdispo_help')
                    ,name: 'content_dispo'
                    
                }:{}),{
                    xtype: 'hidden'
                    ,name: 'parent'
                    ,value: config.parent || 0
                },{
                    xtype: 'hidden'
                    ,name: 'class_key'
                    ,value: config.class_key || 'modDocument'
                    
                },{
                    xtype: 'hidden'
                    ,name: 'type'
                    ,value: 'document'
                    
                },{
                    xtype: 'hidden'
                    ,name: 'context_key'
                    ,value: config.context_key || 'web'
                },{
                    xtype: 'hidden'
                    ,name: 'content'
                    ,id: 'hiddenContent'
                }]
            },{
                xtype: 'panel-resource-tv'
                ,resource: config.resource
                ,class_key: config.class_key
                ,template: config.template
                
            },(config.edit_doc_metatags ? {
                contentEl: 'tab_mtkw'
                ,title: _('meta_keywords')
                
            } : {}),(config.access_permissions ? {
                contentEl: 'tab_access'
                ,title: _('access_permissions')
                
            } : {})]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.Resource.superclass.constructor.call(this,config);
    this.getForm().on('beforeaction',function(f) {
        var v = Ext.get('ta').dom.value;
        Ext.getCmp('hiddenContent').setValue(v);
    },this);
    Ext.get('ta').on('keydown',this.fieldChangeEvent,this);
};
Ext.extend(MODx.panel.Resource,MODx.FormPanel,{
    setup: function() {
        if (this.config.resource == '' || this.config.resource == 0) {
            this.fireEvent('ready');
            return;
        }
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'resource/document.php'
            ,params: {
                action: 'get'
                ,id: this.config.resource
                ,class_key: this.config.class_key
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    if (r.object.pub_date == '0') r.object.pub_date = '';
                    if (r.object.unpub_date == '0') r.object.unpub_date = '';
                    this.getForm().setValues(r.object);
                    this.fireEvent('ready');
            	},scope:this}
            }
        });
    }
    
    ,templateWarning: function() {
        var t = Ext.getCmp('tpl');
        if (!t) return;
        // if selection isn't the current value (originalValue), then show dialog
        if(t.getValue() != t.originalValue) {
            Ext.Msg.confirm(_('warning'), _('resource_change_template_confirm'), function(e) {
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
Ext.reg('panel-resource',MODx.panel.Resource);

// global accessor for TV dynamic fields
var triggerDirtyField = function(fld) {
    Ext.getCmp('panel-resource').fieldChangeEvent(fld);
};
