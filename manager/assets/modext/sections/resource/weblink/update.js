/**
 * Loads the update weblink resource page
 * 
 * @class MODx.page.UpdateWebLink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-weblink-update
 */
MODx.page.UpdateWebLink = function(config) {
    config = config || {};
        
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,which_editor: 'none'
        ,formpanel: 'panel-weblink'
        ,actions: {
            'new': MODx.action['resource/weblink/create']
            ,edit: MODx.action['resource/weblink/update']
            ,preview: MODx.action['resource/preview']
            ,cancel: MODx.action['welcome']
        }
        ,components: [{
            xtype: 'panel-weblink'
            ,renderTo: 'panel-weblink'
            ,resource: config.id
            ,class_key: config.class_key
            ,publish_document: config.publish_document
            ,edit_doc_metatags: config.edit_doc_metatags
            ,access_permissions: config.access_permissions
            ,template: config.template
        },{
            xtype: 'grid-resource-security'
            ,renderTo: 'grid-resource-security'
            ,id: 'grid-resource-security'
            ,resource: config.id
        }]
    	,loadStay: true
        ,buttons: [{
            process: 'update'
            ,javascript: config.which_editor != 'none' ? "cleanupRTE('"+config.which_editor+"');" : ';'
            ,text: _('save')
            ,method: 'remote'
            ,listeners: {
                'click': {fn:function(btn,e) {
                    var g = Ext.getCmp('grid-resource-security');
                    Ext.apply(this.ab.config.params,{
                        resource_groups: g.encodeModified()
                    });
                },scope:this}
                ,'success': function(o,i,r) {
                    Ext.getCmp('grid-resource-security').getStore().commitChanges();
                    var t = parent.Ext.getCmp('modx_resource_tree');
                    t.refreshNode(config.ctx+'_'+config.id);
                }
            }
        }
        ,'-'
        ,{
            process: 'duplicate'
            ,text: _('duplicate')
            ,handler: this.duplicate
            ,scope: this
        }
        ,'-'
        ,{
            process: 'preview'
            ,text: _('preview')
            ,handler: this.preview.createDelegate(this,[config.id])
            ,scope: this
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: { a: MODx.action['welcome'] }
        }]
    });
    MODx.page.UpdateWebLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateWebLink,MODx.Component,{
    preview: function(id) {
        window.open(MODx.config.base_url+'index.php?id='+id);
        return false;
    }
    
    ,duplicate: function(btn,e) {
        MODx.msg.confirm({
            text: _('resource_duplicate_confirm')
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'duplicate'
                ,id: this.config.id
            }
            ,listeners: {
                success: {fn:function(r) {
                    location.href = '?a='+MODx.action['resource/update']+'&id='+r.object.id;
                },scope:this}
            }
        });
    }
});
Ext.reg('page-weblink-update',MODx.page.UpdateWebLink);