/**
 * @class MODx.panel.Resource
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-resource
 */
MODx.panel.Resource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,baseParams: {}
        ,id: 'panel-resource'
        ,class_key: 'modResource'
        ,resource: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            xtype: 'tabpanel'
            ,activeTab: 0
            ,deferredRender: false
            ,border: false
            ,collapsible: true
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,bodyStyle: 'padding: 1.5em;'
                ,labelWidth: 150
            }
            ,items: [{
                title: _('resource_settings')
                ,defaults: { 
                    border: false 
                    ,msgTarget: 'side'
                }
                ,items: [{
                    html: '<h2>'+_('general_settings')+'</h2>'
                },{
                    xtype: (config.resource ? 'statictextfield' : 'hidden')
                    ,fieldLabel: _('id')
                    ,name: 'id'
                    ,value: config.resource
                    ,submitValue: true
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_pagetitle')
                    ,description: _('resource_pagetitle_help')
                    ,name: 'pagetitle'
                    ,width: 300
                    ,maxLength: 255
                    ,allowBlank: false
                    
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_longtitle')
                    ,description: _('resource_longtitle_help')
                    ,name: 'longtitle'
                    ,width: 300
                    ,maxLength: 255
                    
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_description')
                    ,description: _('resource_description_help')
                    ,name: 'description'
                    ,width: 300
                    ,maxLength: 255
                    
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_alias')
                    ,description: _('resource_alias_help')
                    ,name: 'alias'
                    ,width: 300
                    ,maxLength: 100
                    
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_link_attributes')
                    ,description: _('resource_link_attributes_help')
                    ,name: 'link_attributes'
                    ,width: 300
                    ,maxLength: 255
                    
                },(config.class_key == 'modWebLink' ? {
                    xtype: 'textfield'
                    ,fieldLabel: _('weblink')
                    ,description: _('weblink_help')
                    ,name: 'ta'
                    ,width: 300
                    ,maxLength: 255
                    ,value: 'http://'
                    
                } : {
                    xtype: 'textarea'
                    ,fieldLabel: _('resource_summary')
                    ,description: _('resource_summary_help')
                    ,name: 'introtext'
                    ,width: 300
                    ,grow: true
                    
                }),{
                    xtype: 'combo-template'
                    ,fieldLabel: _('resource_template')
                    ,description: _('resource_template_help')
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
                    ,fieldLabel: _('resource_parent')
                    ,description: _('resource_parent_help')
                    ,name: 'parent'
                    ,id: 'resource-parent'
                    ,value: config.parent || 0
                    ,width: 60
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_menutitle')
                    ,description: _('resource_menutitle_help')
                    ,name: 'menutitle'
                    ,width: 300
                    ,maxLength: 255
                    
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('resource_hide_from_menus')
                    ,description: _('resource_hide_from_menus_help')
                    ,name: 'hidemenu'
                    ,inputValue: 1
                    ,checked: false
                    
                }]
            },{
                title: _('page_settings')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    html: '<h2>'+_('resource_settings')+'</h2>'
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('resource_folder')
                    ,description: _('resource_folder_help')
                    ,name: 'isfolder'
                    ,inputValue: 1
                    
                },(config.class_key == 'modDocument' ? {
                    xtype: 'checkbox'
                    ,fieldLabel: _('resource_richtext')
                    ,description: _('resource_richtext_help')
                    ,name: 'richtext'
                    ,inputValue: 1
                    
                } : {}),{
                    xtype: 'checkbox'
                    ,fieldLabel: _('resource_published')
                    ,description: _('resource_published_help')
                    ,name: 'published'
                    ,inputValue: 1
                    ,checked: MODx.config.publish_default == '1' ? true : false
                    
                },(config.publish_document ? {
                    xtype: 'datefield'
                    ,fieldLabel: _('resource_publishdate')
                    ,description: _('resource_publishdate_help')
                    ,name: 'pub_date'
                    ,format: 'Y-m-d'
                    ,allowBlank: true
                    ,width: 200
                    ,anchor: '30%'
                    
                }:{}),(config.publish_document ? {
                    xtype: 'datefield'
                    ,fieldLabel: _('resource_unpublishdate')
                    ,description: _('resource_unpublishdate_help')
                    ,name: 'unpub_date'
                    ,format: 'Y-m-d'
                    ,allowBlank: true
                    ,width: 200
                    ,anchor: '30%'
                    
                }:{}),{
                    xtype: 'checkbox'
                    ,fieldLabel: _('resource_searchable')
                    ,description: _('resource_searchable_help')
                    ,name: 'searchable'
                    ,inputValue: 1
                    ,checked: MODx.config.search_default == '1' ? true : false
                    
                },(config.class_key != 'modWebLink' ? {
                    xtype: 'checkbox'
                    ,fieldLabel: _('resource_cacheable')
                    ,description: _('resource_cacheable_help')
                    ,name: 'cacheable'
                    ,inputValue: 1
                    ,checked: MODx.config.cache_default == '1' ? true : false
                    
                }:{}),{
                    xtype: 'checkbox'
                    ,fieldLabel: _('resource_syncsite')
                    ,description: _('resource_syncsite_help')
                    ,name: 'syncsite'
                    ,inputValue: 1
                    ,checked: true
                    
                },(config.class_key != 'modWebLink' ? {
                    xtype: 'combo-contenttype'
                    ,fieldLabel: _('resource_content_type')
                    ,description: _('resource_content_type_help')
                    ,name: 'content_type'
                    ,width: 200
                    ,value: 1
                    ,anchor: '30%'
                    
                }:{}),(config.class_key != 'modWebLink' ? {
                    xtype: 'combo-content-disposition'
                    ,fieldLabel: _('resource_contentdispo')
                    ,description: _('resource_contentdispo_help')
                    ,name: 'content_dispo'
                    ,anchor: '30%'
                    
                }:{}),{
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
                    ,id: 'context_key'
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
                
            },/*
            :TODO: Recreate admin interface for metatags
            (config.edit_doc_metatags ? {
                contentEl: 'tab_mtkw'
                ,title: _('meta_keywords')
                
            } : {}),*/(config.access_permissions ? {
                contentEl: 'tab_access'
                ,title: _('access_permissions')
                
            } : {})]
        },{
            html: '<hr />'
            ,border: false
        },{
            autoHeight: true
            ,layout: 'form'
            ,bodyStyle: 'padding: 1.5em;'
            ,border: false
            ,items: [{
                html: '<h2>'+_('resource_content')+'</h2>'
                ,border: false
            },{
                xtype: 'textarea'
                ,name: 'ta'
                ,id: 'ta'
                ,hideLabel: true
                ,width: '97%'
                ,height: 400
                ,grow: false
            },{
                xtype: 'combo-rte'
                ,fieldLabel: _('which_editor_title')
                ,id: 'which_editor'
                ,name: 'which_editor'
                ,value: config.which_editor
                ,editable: false
                ,listWidth: 300
                ,triggerAction: 'all'
                ,allowBlank: true
                ,listeners: {
                    'select': {fn:function() {
                        var w = Ext.getCmp('which_editor').getValue();
                        this.form.submit();
                        var u = '?a='+MODx.request.a+'&id='+MODx.request.id+'&which_editor='+w;
                        location.href = u;
                    },scope:this}
                }
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Resource.superclass.constructor.call(this,config);
    Ext.get('ta').on('keydown',this.fieldChangeEvent,this);
};
Ext.extend(MODx.panel.Resource,MODx.FormPanel,{
    rteLoaded: false
    ,setup: function() {
        if (this.config.resource === '' || this.config.resource === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'get'
                ,id: this.config.resource
                ,class_key: this.config.class_key
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    if (r.object.pub_date == '0') { r.object.pub_date = ''; }
                    if (r.object.unpub_date == '0') { r.object.unpub_date = ''; }
                    r.object.ta = r.object.content;
                    this.getForm().setValues(r.object);
                    
                    if (r.object.richtext && MODx.config.use_editor && loadRTE !== null && !this.rteLoaded) {
                    	loadRTE('ta');
                        this.rteLoaded = true;
                    }
                    
                    this.fireEvent('ready');
            	},scope:this}
            }
        });
    }
    
    ,beforeSubmit: function(o) {
        var v = Ext.get('ta').dom.value;
        Ext.getCmp('hiddenContent').setValue(v);
        
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
var triggerRTEOnChange = function(i) {
	triggerDirtyField(Ext.getCmp('ta'));
}
var loadRTE = null;