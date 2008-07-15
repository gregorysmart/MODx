Ext.namespace('MODx','MODx.Resource');

/**
 * Loads the resource update page
 * 
 * @class MODx.UpdateResource
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype resource-update
 */
MODx.UpdateResource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
    	url: MODx.config.connectors_url+'resource/document.php'
        ,which_editor: 'none'
        ,formpanel: 'panel-resource'
    	,actions: {
            'new': MODx.action['resource/create']
            ,edit: MODx.action['resource/update']
            ,preview: MODx.action['resource/preview']
            ,cancel: MODx.action['welcome']
        }
    	,loadStay: true
        ,components: [{
            xtype: 'panel-resource'
            ,renderTo: 'panel-resource'
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
        ,tabs: [{
            contentEl: 'tab_content' ,title: _('document_content')
        }]
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
                    var t = parent.Ext.getCmp('modx_document_tree');
                    t.refreshNode(config.ctx+'_'+config.id);
                }
            }
        }
        ,'-'
        ,(config.published ? {
            id: 'btn_unpublish'
            ,text: _('unpublish')
            ,button: true
            ,listeners: {
                'click': {fn: this.unpublish,scope:this}
            }
        } : {
            id: 'btn_publish'
            ,text: _('publish')
            ,button: true
            ,listeners: {
                'click': {fn: this.publish,scope:this}
            }
        }),{
            process: 'duplicate'
            ,text: _('duplicate')
            ,confirm: _('confirm_duplicate_document')
            ,method: 'remote'
        },(config.deleted ? {
            id: 'btn_undelete'
            ,text: _('undelete')
            ,button: true
            ,listeners: {
                'click': {fn: this.unremove,scope:this}
            }
        } : {
            id: 'btn_delete'
            ,text: _('delete')
            ,button: true
            ,listeners: {
                'click': {fn: this.remove,scope:this}
            }
        })
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
    MODx.UpdateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.UpdateResource,MODx.Component,{
    unpublish: function(btn,e) {
        e.preventDefault();
        Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'unpublish'
                ,id: this.config.id
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    var t = parent.Ext.getCmp('modx_document_tree');
                    t.refreshNode(this.config.ctx+'_'+this.config.id,false);
                    btn.removeListener('click',this.unpublish,this);
                    btn.setText(_('publish'));
                    btn.on('click',this.publish,this);
                } else FormHandler.errorJSON(r);
            }
        });
    }
    
    ,publish: function(btn,e) {
        e.preventDefault();
        Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'publish'
                ,id: this.config.id
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    var t = parent.Ext.getCmp('modx_document_tree');
                    t.refreshNode(this.config.ctx+'_'+this.config.id,false);
                    btn.removeListener('click',this.publish,this);
                    btn.setText(_('unpublish'));
                    btn.on('click',this.unpublish,this);
                } else FormHandler.errorJSON(r);
            }
        });
    }
    
    
    ,unremove: function(btn,e) {
        e.preventDefault();
        Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'undelete'
                ,id: this.config.id
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    var t = parent.Ext.getCmp('modx_document_tree');
                    t.refreshNode(this.config.ctx+'_'+this.config.id,false);
                    btn.removeListener('click',this.unremove,this);
                    btn.setText(_('delete'));
                    btn.on('click',this.remove,this);
                } else FormHandler.errorJSON(r);
            }
        });
    }
    
    ,remove: function(btn,e) {
        e.preventDefault();
        Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'delete'
                ,id: this.config.id
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    var t = parent.Ext.getCmp('modx_document_tree');
                    t.refreshNode(this.config.ctx+'_'+this.config.id,false);
                    btn.removeListener('click',this.remove,this);
                    btn.setText(_('undelete'));
                    btn.on('click',this.unremove,this);
                } else FormHandler.errorJSON(r);
            }
        });
    }
    
    ,preview: function(id) {
        window.open(MODx.config.base_url+'index.php?id='+id);
        return false;
    }
});
Ext.reg('resource-update',MODx.UpdateResource);