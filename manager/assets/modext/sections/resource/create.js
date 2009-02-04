/**
 * Loads the create resource page
 * 
 * @class MODx.page.CreateResource
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-resource-create
 */
MODx.page.CreateResource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,formpanel: 'modx-panel-resource'
        ,which_editor: 'none'
    	,actions: {
            'new': MODx.action['resource/create']
            ,edit: MODx.action['resource/update']
            ,cancel: MODx.action['welcome']
        }
    	,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,javascript: config.which_editor != 'none' ? "cleanupRTE('"+config.which_editor+"');" : ';'
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,params: { a: MODx.action['welcome'] }
        }]
    	,loadStay: true
        ,components: [{
            xtype: 'modx-panel-resource'
            ,renderTo: 'modx-panel-resource'
            ,resource: 0
            ,class_key: config.class_key
            ,publish_document: config.publish_document
            ,edit_doc_metatags: config.edit_doc_metatags
            ,access_permissions: config.access_permissions
            ,template: config.template
            ,parent: config.parent
        },{
            xtype: 'modx-panel-resource-tv'
            ,class_key: config.class_key
            ,resource: 0
        }]
    });
    MODx.page.CreateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateResource,MODx.Component);
Ext.reg('modx-page-resource-create',MODx.page.CreateResource);