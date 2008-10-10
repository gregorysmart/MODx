/**
 * Loads the chunk update page
 * 
 * @class MODx.page.UpdateChunk
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-chunk-update
 */
MODx.page.UpdateChunk = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	   formpanel: 'panel-chunk'
	   ,actions: {
            'new': MODx.action['element/chunk/create']
            ,edit: MODx.action['element/chunk/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,refresh: {
            	tree: 'modx_element_tree'
            	,node: (config.category != '' ? 'n_chunk_category_'+config.category : 'n_type_chunk')
                ,self: true
            }
            ,keys: [{
                key: "s"
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['welcome']}
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'panel-chunk'
            ,id: 'panel-chunk'
            ,renderTo: 'panel-chunk'
            ,chunk: config.id
            ,name: config.name
        }]
	});
	MODx.page.UpdateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateChunk,MODx.Component);
Ext.reg('page-chunk-update',MODx.page.UpdateChunk);