/**
 * @class MODx.panel.ResourceData
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration parameters
 * @xtype panel-resource-data
 */
MODx.panel.ResourceData = function(config) {
    config = config || {};
    var df = { 
        border: false
        ,msgTarget: 'side'
    };
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/document.php'
        ,baseParams: {}
        ,id: 'panel-resource-data'
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
                title: _('page_data_general')
                ,defaults: df
                ,items: [{
                    html: '<h2>'+config.pagetitle+'</h2>'
                },{
                    name: 'pagetitle'
                    ,fieldLabel: _('document_title')
                    ,description: _('document_title_help')
                    ,xtype: 'statictextfield'
                },{
                    name: 'longtitle'
                    ,fieldLabel: _('long_title')
                    ,description: _('document_long_title_help')
                    ,xtype: 'statictextfield'
                    ,value: _('notset')
                    ,width: 500
                },{
                    name: 'description'
                    ,fieldLabel: _('document_description')
                    ,description: _('document_description_help')
                    ,xtype: 'statictextfield'
                    ,width: 500
                },{
                    name: 'class_key'
                    ,fieldLabel: _('class_key')
                    ,xtype: 'statictextfield'
                },{
                    name: 'alias'
                    ,fieldLabel: _('document_alias')
                    ,description: _('document_alias_help')
                    ,xtype: 'statictextfield'
                },{
                    name: 'keywords'
                    ,fieldLabel: _('keywords')
                    ,xtype: 'statictextfield'
                },{
                    name: 'context_key'
                    ,fieldLabel: _('context')
                    ,xtype: 'statictextfield'
                }]
            },{
                title: _('page_data_changes')
                ,defaults: df
                ,defaultType: 'statictextfield'
                ,items: [{
                    name: 'createdon_adjusted'
                    ,fieldLabel: _('page_data_created')
                },{
                    name: 'createdon_by'
                    ,fieldLabel: _('page_data_created_by')
                },{
                    name: 'editedon_adjusted'
                    ,fieldLabel: _('page_data_edited')
                },{
                    name: 'editedon_by'
                    ,fieldLabel: _('page_data_edited_by')
                }]
            },{
                title: _('page_data_status')
                ,defaults: df
                ,defaultType: 'statictextfield'
                ,items: [{
                    name: 'status'
                    ,fieldLabel: _('page_data_status')
                    ,description: _('document_opt_published_help')
                },{
                    name: 'deleted'
                    ,fieldLabel: _('deleted')
                    ,xtype: 'staticboolean'
                },{
                    name: 'pub_date'
                    ,fieldLabel: _('page_data_publishdate')
                    ,description: _('page_data_publishdate_help')
                },{
                    name: 'unpub_date'
                    ,fieldLabel: _('page_data_unpublishdate')
                    ,description: _('page_data_unpublishdate_help')
                },{
                    name: 'cacheable'
                    ,fieldLabel: _('page_data_cacheable')
                    ,description: _('page_data_cacheable_help')
                    ,xtype: 'staticboolean'
                },{
                    name: 'searchable'
                    ,fieldLabel: _('page_data_searchable')
                    ,description: _('page_data_searchable_help')
                    ,xtype: 'staticboolean'
                },{
                    name: 'showmenu'
                    ,fieldLabel: _('document_opt_show_menu')
                    ,description: _('document_opt_show_menu_help')
                    ,xtype: 'staticboolean'
                },{
                    name: 'menutitle'
                    ,fieldLabel: _('document_opt_menu_title')
                    ,description: _('document_opt_menu_title_help')
                }]
            },{
                title: _('page_data_markup')
                ,defaults: df
                ,defaultType: 'statictextfield'
                ,items: [{
                    name: 'template'
                    ,fieldLabel: _('page_data_template')
                },{
                    name: 'richtext'
                    ,fieldLabel: _('page_data_editor')
                    ,description: _('document_opt_richtext_help')
                    ,xtype: 'staticboolean'
                },{
                    name: 'isfolder'
                    ,fieldLabel: _('page_data_folder')
                    ,description: _('document_opt_folder_help')
                    ,xtype: 'staticboolean'
                }]
            },{
                title: _('page_data_source')
                ,items: [{
                    name: 'buffer'
                    ,xtype: 'textarea'
                    ,hideLabel: true
                    ,width: '100%'
                    ,grow: true
                }]
            },{
                title: _('preview')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    autoLoad: {
                        url: '../index.php?id='+config.resource+'&z=manprev'
                    }
                }]
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.ResourceData.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ResourceData,MODx.FormPanel,{
    setup: function() {
        if (this.config.resource == '' || this.config.resource == 0) {
            this.fireEvent('ready');
        	return;
        }
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'resource/document.php'
            ,params: {
                action: 'data'
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
});
Ext.reg('panel-resource-data',MODx.panel.ResourceData);