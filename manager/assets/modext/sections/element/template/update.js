/**
 * Loads the update template page
 * 
 * @class MODx.page.UpdateTemplate
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-template-update
 */
MODx.page.UpdateTemplate = function(config) {
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
            ,checkDirty: true
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: (config.category != '' ? 'n_template_category_'+config.category : 'n_type_template')
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
            xtype: 'panel-template'
            ,id: 'panel-template'
            ,renderTo: 'panel-template'
            ,template: config.id
            ,name: ''
        }]
	});
	MODx.page.UpdateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateTemplate,MODx.Component);
Ext.reg('page-template-update',MODx.page.UpdateTemplate);