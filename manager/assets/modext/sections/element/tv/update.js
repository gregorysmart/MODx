/**
 * Loads the TV update page
 * 
 * @class MODx.page.UpdateTV
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-tv-update
 */
MODx.page.UpdateTV = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'panel-tv'		
		,actions: {
            'new': MODx.action['element/tv/create']
            ,edit: MODx.action['element/tv/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,refresh: {
            	tree: 'modx_element_tree'
            	,node: 'n_type_tv'
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
            process: 'duplicate'
            ,text: _('duplicate')
            ,method: 'remote'
            ,confirm: _('tv_duplicate_confirm')
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_tv'
                ,self: true
            }
        },{
            process: 'delete'
            ,text: _('delete')
            ,method: 'remote'
            ,confirm: _('tv_delete_confirm')
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_tv'
                ,self: true
            }
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['welcome']}
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'panel-tv'
            ,id: 'panel-tv'
            ,renderTo: 'panel-tv'
            ,tv: config.id
            ,name: ''
        }]
	});
	MODx.page.UpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateTV,MODx.Component);
Ext.reg('page-tv-update',MODx.page.UpdateTV);