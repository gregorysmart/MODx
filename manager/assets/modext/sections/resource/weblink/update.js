Ext.namespace('MODx.Resource');
/**
 * Loads the update weblink resource page
 * 
 * @class MODx.Resource.UpdateWeblink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype weblink-update
 */
MODx.Resource.UpdateWebLink = function(config) {
    config = config || {};
        
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/document.php'
        ,which_editor: 'none'
        ,formpanel: 'panel-weblink'
        ,actions: {
            'new': MODx.action['resource/create']
            ,edit: MODx.action['resource/update']
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
    MODx.Resource.UpdateWebLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Resource.UpdateWebLink,MODx.Component,{
    getButtons: function(config) {
    	var b = [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,refresh: {
                tree: 'modx_document_tree'
                ,node: config.ctx+'_'+config.id
            }
        },'-'];
        if (config.published) {
            b.push({
                process: 'unpublish'
                ,text: _('unpublish')
                ,method: 'remote'
                ,reload: true
                ,refresh: {
                    tree: 'modx_document_tree'
                    ,node: config.ctx+'_'+config.id
                }
            });
        } else {
            b.push({
                process: 'publish'
                ,text: _('publish')
                ,method: 'remote'
                ,reload: true
                ,refresh: {
                    tree: 'modx_document_tree'
                    ,node: config.ctx+'_'+config.id
                }
            });
        }
        b.push({
            process: 'duplicate'
            ,text: _('duplicate')
            ,confirm: _('confirm_duplicate_document')
            ,method: 'remote'
        });
        if (config.deleted) {
            b.push({
                process: 'undelete'
                ,text: _('undelete')
                ,method: 'remote'
                ,reload: true
                ,refresh: {
                    tree: 'modx_document_tree'
                    ,node: config.ctx+'_'+config.id
                }
            });
        } else {
            b.push({
                process: 'delete'
                ,text: _('delete')
                ,method: 'remote'
                ,reload: true
                ,confirm: _('confirm_delete_document')
                ,refresh: {
                	tree: 'modx_document_tree'
                	,node: config.ctx+'_'+config.id
                }
            });
        }
        b.push('-',{
            process: 'preview'
            ,text: _('preview')
            ,handler: this.preview.createDelegate(this,[config.id])
            ,scope: this
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: { a:MODx.action['welcome'] }
        });
        return b;
    }
    
    ,preview: function(id) {
        window.open(MODx.config.base_url+'index.php?id='+id);
        return false;
    }
});
Ext.reg('weblink-update',MODx.Resource.UpdateWebLink);