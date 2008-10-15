/**
 * Loads the create plugin page
 * 
 * @class MODx.page.CreatePlugin
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-plugin-create
 */
MODx.page.CreatePlugin = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'panel-plugin'
	    ,actions: {
            'new': MODx.action['element/plugin/create']
            ,edit: MODx.action['element/plugin/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create', text: _('save'), method: 'remote'
            ,checkDirty: true
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
            xtype: 'panel-plugin'
            ,id: 'panel-plugin'
            ,renderTo: 'panel-plugin'
            ,plugin: config.id
            ,category: config.category
            ,name: ''
        }]
	});
	MODx.page.CreatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreatePlugin,MODx.Component);
Ext.reg('page-plugin-create',MODx.page.CreatePlugin);