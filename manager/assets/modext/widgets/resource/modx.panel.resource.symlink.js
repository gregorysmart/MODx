/**
 * @class MODx.panel.SymLink
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-symlink
 */
MODx.panel.SymLink = function(config) {
    config = config || {};
    var it = [];
    it.push({
        title: _('createedit_symlink')
        ,layout: 'form'
        ,labelWidth: 200
        ,bodyStyle: 'padding: 1.5em;'
        ,autoHeight: true
        ,defaults: { border: false ,msgTarget: 'side' ,width: 400 }
        ,items: [{
            xtype: 'hidden'
            ,name: 'id'
            ,value: config.resource
            ,id: 'modx-symlink-id'
        },{
            layout:'column'
            ,border: false
            ,width: '100%'
            ,items:[{
                columnWidth: .55
                ,layout: 'form'
                ,border: false
                ,items: [{
                    xtype: 'modx-combo-template'
                    ,fieldLabel: _('resource_template')
                    ,description: _('resource_template_help')
                    ,name: 'template'
                    ,id: 'modx-symlink-template'
                    ,width: 300
                    ,editable: false
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                    ,listeners: {
                        'select': {fn: this.templateWarning,scope: this}
                    }
                    ,value: config.record.template
                }]
            },{
                columnWidth: .45
                ,layout: 'form'
                ,hideLabels: true
                ,labelWidth: 0
                ,border: false
                ,items: [{
                    xtype: 'checkbox'
                    ,boxLabel: _('resource_published')
                    ,description: _('resource_published_help')
                    ,name: 'published'
                    ,id: 'modx-symlink-published'
                    ,inputValue: 1
                    ,checked: MODx.config.publish_default == '1' ? true : false
                    
                }]
            }]
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_pagetitle')
            ,description: _('resource_pagetitle_help')
            ,name: 'pagetitle'
            ,id: 'modx-symlink-pagetitle'
            ,maxLength: 255
            ,allowBlank: false
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_longtitle')
            ,description: _('resource_longtitle_help')
            ,name: 'longtitle'
            ,id: 'modx-symlink-longtitle'
            ,maxLength: 255
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_description')
            ,description: _('resource_description_help')
            ,name: 'description'
            ,id: 'modx-symlink-description'
            ,maxLength: 255
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_alias')
            ,description: _('resource_alias_help')
            ,name: 'alias'
            ,id: 'modx-symlink-alias'
            ,maxLength: 100
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_link_attributes')
            ,description: _('resource_link_attributes_help')
            ,name: 'link_attributes'
            ,maxLength: 255
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('symlink')
            ,description: _('symlink_help')
            ,name: 'content'
            ,id: 'modx-symlink-content'
            ,maxLength: 255            
        },{
            xtype: 'textarea'
            ,fieldLabel: _('resource_summary')
            ,description: _('resource_summary_help')
            ,name: 'introtext'
            ,id: 'modx-symlink-introtext'
            ,grow: true
            
        },{
            xtype: 'modx-field-parent-change'
            ,fieldLabel: _('resource_parent')
            ,description: _('resource_parent_help')
            ,name: 'parent-cmb'
            ,editable: false
            ,id: 'modx-symlink-parent'
            ,value: config.record.parent || 0
            ,formpanel: 'modx-panel-symlink'
        },{
            xtype: 'hidden'
            ,name: 'parent'
            ,value: config.record.parent || 0
            ,id: 'modx-resource-parent-hidden'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_menutitle')
            ,description: _('resource_menutitle_help')
            ,name: 'menutitle'
            ,id: 'modx-symlink-menutitle'
            ,maxLength: 255
            
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('resource_menuindex')
            ,description: _('resource_menuindex_help')
            ,name: 'menuindex'
            ,id: 'modx-symlink-menuindex'
            ,width: 60
            
        },{
            xtype: 'checkbox'
            ,fieldLabel: _('resource_hide_from_menus')
            ,description: _('resource_hide_from_menus_help')
            ,name: 'hidemenu'
            ,inputValue: 1
            ,checked: false
            
        },{
            xtype: 'hidden'
            ,name: 'type'
            ,value: 'document'                        
        },{
            xtype: 'hidden'
            ,name: 'context_key'
            ,id: 'modx-symlink-context-key'
            ,value: 'web'
        },{
            html: MODx.onDocFormRender, border: false
        }]
    });
    
    var va = [];
    va.push({
        xtype: 'checkbox'
        ,fieldLabel: _('resource_folder')
        ,description: _('resource_folder_help')
        ,name: 'isfolder'
        ,id: 'modx-symlink-isfolder'
        ,inputValue: 1        
    });
    va.push({
        xtype: 'datetimefield'
        ,fieldLabel: _('resource_publishedon')
        ,description: _('resource_publishedon_help')
        ,name: 'publishedon'
        ,id: 'modx-symlink-publishedon'
        ,allowBlank: true
        ,dateWidth: 120
        ,timeWidth: 120
    });
    if (MODx.config.publish_document) {
        va.push({
            xtype: 'datetimefield'
            ,fieldLabel: _('resource_publishdate')
            ,description: _('resource_publishdate_help')
            ,name: 'pub_date'
            ,id: 'modx-symlink-pub-date'
            ,format: 'd-m-Y H:i:s'
            ,allowBlank: true
            ,dateWidth: 120
            ,timeWidth: 120
        });
    }
    if (MODx.config.publish_document) {
        va.push({
            xtype: 'datetimefield'
            ,fieldLabel: _('resource_unpublishdate')
            ,description: _('resource_unpublishdate_help')
            ,name: 'unpub_date'
            ,id: 'modx-symlink-unpub-date'
            ,format: 'd-m-Y H:i:s'
            ,allowBlank: true
            ,dateWidth: 120
            ,timeWidth: 120   
        });
    }
    va.push({
        xtype: 'checkbox'
        ,fieldLabel: _('resource_searchable')
        ,description: _('resource_searchable_help')
        ,name: 'searchable'
        ,id: 'modx-symlink-searchable'
        ,inputValue: 1
        ,checked: MODx.config.search_default == '1' ? true : false        
    });
    va.push({
        xtype: 'checkbox'
        ,fieldLabel: _('resource_cacheable')
        ,description: _('resource_cacheable_help')
        ,name: 'cacheable'
        ,id: 'modx-symlink-cacheable'
        ,inputValue: 1
        ,checked: true        
    });
    va.push({
        xtype: 'hidden'
        ,name: 'class_key'
        ,id: 'modx-symlink-class-key'
        ,value: 'modStaticResource'
        
    });
    va.push({
        xtype: 'modx-combo-content-type'
        ,fieldLabel: _('resource_content_type')
        ,description: _('resource_content_type_help')
        ,name: 'content_type'
        ,id: 'modx-symlink-content-type'
        ,width: 300
        ,value: 1
    });
    va.push({
        xtype: 'modx-combo-content-disposition'
        ,fieldLabel: _('resource_contentdispo')
        ,description: _('resource_contentdispo_help')
        ,name: 'content_dispo'
        ,id: 'modx-symlink-content-dispo'
        ,width: 300
    });
    va.push({
        xtype: 'textfield'
        ,fieldLabel: _('class_key')
        ,name: 'class_key'
        ,id: 'modx-symlink-class-key'
        ,allowBlank: false
        ,value: 'modStaticResource'    
        ,width: 250
    });
    it.push({
        id: 'modx-symlink-page-settings'
        ,title: _('page_settings')
        ,layout: 'form'
        ,labelWidth: 200
        ,bodyStyle: 'padding: 1.5em;'
        ,autoHeight: true
        ,defaults: {
            border: false
            ,msgTarget: 'side'
        }
        ,items: va
    });
    it.push({
        xtype: 'modx-panel-resource-tv'
        ,resource: config.resource
        ,class_key: config.record.class_key
        ,template: config.record.template
        
    });
    if (config.access_permissions) {
        it.push({
            id: 'modx-resource-access-permissions'
            ,title: _('access_permissions')
            ,bodyStyle: 'padding: 1.5em;'
            ,autoHeight: true
            ,layout: 'form'
            ,items: [{
                html: '<p>'+_('resource_access_message')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-resource-security'
                ,preventRender: true
                ,resource: config.resource
                ,listeners: {
                    'afteredit': {fn:this.fieldChangeEvent,scope:this}
                }
            }]
        });
    }
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,baseParams: {}
        ,id: 'modx-panel-symlink'
        ,class_key: 'modSymLink'
        ,resource: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('symlink_new')+'</h2>'
            ,id: 'modx-symlink-header'
            ,cls: 'modx-page-header'
            ,border: false
        },MODx.getPageStructure(it,{id:'modx-resource-tabs' ,forceLayout: true ,deferredRender: false })]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'success': {fn:this.success,scope:this}
        }
    });
    MODx.panel.SymLink.superclass.constructor.call(this,config);
    setTimeout("Ext.getCmp('modx-panel-symlink').onLoad();",1000);
};
Ext.extend(MODx.panel.SymLink,MODx.FormPanel,{
    onLoad: function() {
        this.getForm().setValues(this.config.record);
    }
    ,setup: function() {
        if (this.config.resource === '' || this.config.resource === 0) {
            this.fireEvent('ready');
            return false;
        }
        Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.resource
                ,class_key: this.config.record.class_key
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    if (r.object.pub_date == '0') { r.object.pub_date = ''; }
                    if (r.object.unpub_date == '0') { r.object.unpub_date = ''; }
                    r.object['parent-cmb'] = r.object.parent;
                    
                    Ext.getCmp('modx-symlink-header').getEl().update('<h2>'+_('symlink')+': '+r.object.pagetitle+'</h2>');
                    
                    this.getForm().setValues(r.object);
                    this.fireEvent('ready');
                } else { MODx.form.Handler.errorJSON(r); }
            }
        });
    }
    ,beforeSubmit: function(o) {        
        var g = Ext.getCmp('modx-grid-resource-security');
        Ext.apply(o.form.baseParams,{
            resource_groups: g.encodeModified()
        });        
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }

    ,success: function(o) {
        Ext.getCmp('modx-grid-resource-security').getStore().commitChanges();
        var t = Ext.getCmp('modx-resource-tree');
        var ctx = Ext.getCmp('modx-symlink-context-key').getValue();
        var pa = Ext.getCmp('modx-resource-parent-hidden').getValue();
        var v = ctx+'_'+pa;
        var n = t.getNodeById(v);
        n.leaf = false;
        t.refreshNode(v,true);
        Ext.getCmp('modx-page-update-resource').config.preview_url = o.result.object.preview_url;
    }
    
    ,templateWarning: function() {
        var t = Ext.getCmp('modx-symlink-template');
        if (!t) { return false; }
        /* if selection isn't the current value (originalValue), then show dialog */
        if(t.getValue() != t.originalValue) {
            Ext.Msg.confirm(_('warning'), _('resource_change_template_confirm'), function(e) {
                if (e == 'yes') {
                    var tvpanel = Ext.getCmp('modx-panel-resource-tv');
                    if(tvpanel && tvpanel.body) {
                        /* update the Template Variables tab */
                        this.tvum = tvpanel.body.getUpdater();
                        this.tvum.update({
                            url: 'index.php?a='+MODx.action['resource/tvs']
                            ,params: {
                                class_key: this.config.record.class_key
                                ,resource: this.config.resource
                                ,template: t.getValue()
                            }
                            ,discardUrl: true
                            ,scripts: true
                            ,nocache: true
                        });
                    }
                    t.originalValue = t.getValue(); /* so that the next reset will work logically */
                } else {
                    t.reset();
                }
            },this);
        }
    }
});
Ext.reg('modx-panel-symlink',MODx.panel.SymLink);

/* global accessor for TV dynamic fields */
var triggerDirtyField = function(fld) {
    Ext.getCmp('modx-panel-symlink').fieldChangeEvent(fld);
};
MODx.triggerRTEOnChange = function(i) {
    triggerDirtyField(Ext.getCmp('ta'));
};
MODx.fireResourceFormChange = function(f,nv,ov) {
    Ext.getCmp('modx-panel-symlink').fireEvent('fieldChange');
};