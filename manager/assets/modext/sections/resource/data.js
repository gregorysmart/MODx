Ext.namespace('MODx','MODx.Resource');

/**
 * Loads the resource data page
 * 
 * @class MODx.Resource.Data
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype resource-data
 */
MODx.Resource.Data = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		form: 'document_data'
		,actions: {
            'new': MODx.action['resource/create']
            ,edit: MODx.action['resource/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'edit'
            ,text: _('edit')
            ,params: { a: MODx.action['resource/update'] }
        },'-',{
            process: 'duplicate'
            ,text: _('duplicate')
            ,method: 'remote'
            ,confirm: _('confirm_duplicate_document')
        },{
            process: 'delete'
            ,text: _('delete')
            ,method: 'remote'
            ,confirm: _('confirm_delete_document')
            ,refresh: {
            	tree: 'modx_document_tree'
            	,node: config.ctx+'_'+config.id
            }
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,params: { a: MODx.action['welcome'] }
        }]
        ,components: [{
            xtype: 'panel-resource-data'
            ,renderTo: 'panel-data'
            ,resource: config.id
            ,context: config.ctx
            ,class_key: config.class_key
            ,pagetitle: config.pagetitle
        }]
	});
	MODx.Resource.Data.superclass.constructor.call(this,config);	
};
Ext.extend(MODx.Resource.Data,MODx.Component);
Ext.reg('resource-data',MODx.Resource.Data);