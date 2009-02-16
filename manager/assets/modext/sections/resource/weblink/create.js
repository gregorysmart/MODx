/**
 * Loads the create resource page
 * 
 * @class MODx.page.CreateWebLink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-weblink-create
 */
MODx.page.CreateWebLink = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,formpanel: 'modx-panel-weblink'
        ,which_editor: 'none'
        ,actions: {
            'new': MODx.action['resource/weblink/create']
            ,edit: MODx.action['resource/weblink/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
            ,params: {
                class_key: 'modWebLink'
            }
            ,javascript: config.which_editor != 'none' ? "cleanupRTE('"+config.which_editor+"');" : ';'
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: { a: MODx.action['welcome'] }
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-weblink'
            ,renderTo: 'modx-panel-weblink'
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
    MODx.page.CreateWebLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateWebLink,MODx.Component);
Ext.reg('modx-page-weblink-create',MODx.page.CreateWebLink);