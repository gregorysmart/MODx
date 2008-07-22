Ext.namespace('MODx');

/**
 * Loads the update plugin page
 * 
 * @class MODx.UpdatePlugin
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype plugin-update
 */
MODx.UpdatePlugin = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'panel-plugin'
		,actions: {
            'new': MODx.action['element/plugin/create']
            ,edit: MODx.action['element/plugin/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,refresh: {
            	tree: 'modx_element_tree'
            	,node: 'n_type_plugin'
            	,self: true
            }
            ,listeners: {
                'click': {fn:function(btn,e) {
                    var g = Ext.getCmp('grid-plugin-event');
                    Ext.apply(this.ab.config.params,{
                        events: g.encodeModified()
                    });
                },scope:this}
                ,'success': function(o,i,r) {
                    Ext.getCmp('grid-plugin-event').getStore().commitChanges();
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
            ,confirm: _('plugin_duplicate_confirm')
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_plugin'
                ,self: true
            }
        },{
            process: 'delete'
            ,text: _('delete')
            ,method: 'remote'
            ,confirm: _('plugin_delete_confirm')
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_plugin'
                ,self: true
            }
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['welcome']}
        }]
		,loadStay: true
        ,components: [{
            xtype: 'panel-plugin'
            ,id: 'panel-plugin'
            ,renderTo: 'panel-plugin'
            ,plugin: config.id
            ,category: config.category
            ,name: ''
        }]
	});
	MODx.UpdatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.UpdatePlugin,MODx.Component);
Ext.reg('plugin-update',MODx.UpdatePlugin);