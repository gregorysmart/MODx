Ext.namespace('MODx');
/**
 * Loads the create snippet page
 * 
 * @class MODx.CreateSnippet
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype snippet-create
 */
MODx.CreateSnippet = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'panel-snippet'
		,actions: {
            'new': MODx.action['element/snippet/create']
            ,edit: MODx.action['element/snippet/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create', text: _('save'), method: 'remote'
            ,refresh: {
            	tree: 'modx_element_tree'
            	,node: 'n_type_snippet'
            	,self: true
            }
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'panel-snippet'
            ,id: 'panel-snippet'
            ,renderTo: 'panel-snippet'
            ,snippet: 0
            ,name: ''
        }]
	});
	MODx.CreateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.CreateSnippet,MODx.Component);
Ext.reg('snippet-create',MODx.CreateSnippet);