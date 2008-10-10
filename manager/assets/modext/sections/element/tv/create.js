/**
 * Loads the TV creation page
 * 
 * @class MODx.page.CreateTV
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-tv-create
 */
MODx.page.CreateTV = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'panel-tv'
		,actions: {
            'new': MODx.action['element/tv/create']
            ,edit: MODx.action['element/tv/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,refresh: {
            	tree: 'modx_element_tree'
            	,node: (config.category != '' ? 'n_tv_category_'+config.category : 'n_type_tv')
            	,self: true
            }
            ,listeners: {
                'click': {fn:function(btn,e) {
                    var g = Ext.getCmp('grid-tv-templates');
                    var rg = Ext.getCmp('grid-tv-security');
                    Ext.apply(this.ab.config.params,{
                        templates: g.encodeModified()
                        ,resource_groups: rg.encodeModified()
                    });
                },scope:this}
                ,'success': function(o,i,r) {
                    Ext.getCmp('grid-tv-templates').getStore().commitChanges();
                    Ext.getCmp('grid-tv-security').getStore().commitChanges();
                }
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
        ,components: [{
            xtype: 'panel-tv'
            ,id: 'panel-tv'
            ,renderTo: 'panel-tv'
            ,tv: ''
            ,name: ''
        }]
	});
	MODx.page.CreateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateTV,MODx.Component);
Ext.reg('page-tv-create',MODx.page.CreateTV);