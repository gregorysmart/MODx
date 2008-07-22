Ext.namespace('MODx');
/**
 * Loads the chunk create page
 * 
 * @class MODx.CreateChunk
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype chunk-create
 */
MODx.CreateChunk = function(config) {
	config = config || {};
	
	Ext.applyIf(config,{
		form: 'mutate_chunk'
	    ,fields: {
	        name: {
                xtype: 'textfield'
                ,width: 300
                ,maxLength: 100
                ,applyTo: 'name'
            }
            ,description: {
                xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                ,applyTo: 'description'
            }
            ,category: {
                xtype: 'combo-category'
                ,transform: 'category'
                ,value: config.category
            }
            ,locked: {
                xtype: 'checkbox'
                ,boxLabel: _('chunk_lock')
                ,applyTo: 'locked'
            }
            ,chunk: {
                xtype: 'textarea'
                ,width: '95%'
                ,grow: true
                ,applyTo: 'chunk'
            }
            ,which_editor: {
                xtype: 'combo'
                ,id: 'which_editor'
                ,editable: false
                ,listWidth: 300
                ,triggerAction: 'all'
                ,transform: 'which_editor'
                ,listeners: {
                    'select': {fn:function() {
                        var w = Ext.getCmp('which_editor').getValue();
                        this.form.submit();
                        var u = '?a='+MODx.action['element/chunk/create']+'&which_editor='+w+'&category='+this.config.category;
                        location.href = u;
                    },scope:this}
                }
            }
        }
	    ,actions: {
            'new': MODx.action['element/chunk/create']
            ,edit: MODx.action['element/chunk/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create', text: _('save'), method: 'remote'
            ,refresh: {
            	tree: 'modx_element_tree'
            	,node: 'n_type_chunk'
            }
            ,keys: [{
                key: "s"
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,loadStay: true
	});
	MODx.CreateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.CreateChunk,MODx.Component);
Ext.reg('chunk-create',MODx.CreateChunk);