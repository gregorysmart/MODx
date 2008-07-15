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
        ,tabs: this.getTabs(config)
	});
	MODx.Resource.Data.superclass.constructor.call(this,config);	
};
Ext.extend(MODx.Resource.Data,MODx.Component,{
	getTabs: function(config) {
		var it = [{contentEl: 'tab_data', title: _('page_data_title')}]
		if (config.show_preview) {
			it.push({contentEl: 'tab_preview', title: _('preview')});
		}
		it.push({contentEl: 'tab_source', title: _('page_data_source')});
		return it;
	}
});
Ext.reg('resource-data',MODx.Resource.Data);