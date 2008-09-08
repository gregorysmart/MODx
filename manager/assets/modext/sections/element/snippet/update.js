/**
 * Loads the update snippet page
 * 
 * @class MODx.page.UpdateSnippet
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-snippet-update
 */
MODx.page.UpdateSnippet = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'panel-snippet'
		,actions: {
            'new': MODx.action['element/snippet/create']
            ,edit: MODx.action['element/snippet/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_snippet'
                ,self: true
            }
            ,keys: [{
                key: "s"
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'duplicate'
            ,text: _('duplicate')
            ,method: 'remote'
            ,confirm: _('snippet_duplicate_confirm')
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_snippet'
                ,self: true
            }
        },{
            process: 'delete'
            ,text: _('delete')
            ,method: 'remote'
            ,confirm: _('snippet_delete_confirm')
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_snippet'
                ,self: true
            }
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params:{a:MODx.action['welcome']}
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'panel-snippet'
            ,id: 'panel-snippet'
            ,renderTo: 'panel-snippet'
            ,snippet: config.id
            ,name: ''
        }]
	});
	MODx.page.UpdateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateSnippet,MODx.Component);
Ext.reg('page-snippet-update',MODx.page.UpdateSnippet);