Ext.namespace('MODx');

/**
 * Loads the update template page
 * 
 * @class MODx.UpdateTemplate
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype template-update
 */
MODx.UpdateTemplate = function(config) {
	config = config || {};
	
	Ext.applyIf(config,{
		formpanel: 'panel-template'
		,actions: {
            'new': MODx.action['element/template/create']
            ,edit: MODx.action['element/template/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_template'
                ,self: true
            }
            ,listeners: {
                'click': {fn:function(btn,e) {
                    var g = Ext.getCmp('grid-template-tv');
                    Ext.apply(this.ab.config.params,{
                        tvs: g.encodeModified()
                    });
                },scope:this}
                ,'success': function(o,i,r) {
                    Ext.getCmp('grid-template-tv').getStore().commitChanges();
                }
            }
        },{
            process: 'duplicate'
            ,text: _('duplicate')
            ,method: 'remote'
            ,confirm: _('template_duplicate_confirm')
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_template'
                ,self: true
            }
        },{
            process: 'delete'
            ,text: _('delete')
            ,method: 'remote'
            ,confirm: _('template_delete_confirm')
            ,refresh: {
            	tree: 'modx_element_tree'
            	,node: 'n_type_template'
            	,self: true
            }
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['welcome']}
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'panel-template'
            ,id: 'panel-template'
            ,renderTo: 'panel-template'
            ,template: config.id
            ,name: ''
        }]
	});
	MODx.UpdateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.UpdateTemplate,MODx.Component);
Ext.reg('template-update',MODx.UpdateTemplate);