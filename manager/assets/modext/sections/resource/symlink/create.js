/**
 * Loads the create resource page
 * 
 * @class MODx.page.CreateSymLink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-symlink-create
 */
MODx.page.CreateSymLink = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,formpanel: 'modx-panel-symlink'
        ,which_editor: 'none'
        ,actions: {
            'new': MODx.action['resource/symlink/create']
            ,edit: MODx.action['resource/symlink/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
            ,javascript: config.which_editor != 'none' ? "cleanupRTE('"+config.which_editor+"');" : ';'
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: { a: MODx.action['welcome'] }
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-symlink'
            ,renderTo: 'modx-panel-symlink'
            ,resource: 0
            ,class_key: config.class_key
            ,context_key: config.context_key
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
    MODx.page.CreateSymLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateSymLink,MODx.Component);
Ext.reg('modx-page-symlink-create',MODx.page.CreateSymLink);