/**
 * Loads the create template page
 * 
 * @class MODx.page.CreateTemplate
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-template-create
 */
MODx.page.CreateTemplate = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'panel-template'
		,actions: {
            'new': MODx.action['element/template/create']
            ,edit: MODx.action['element/template/update']
            ,cancel: MODx.action['welcome']
        }
		,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
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
	MODx.page.CreateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateTemplate,MODx.Component);
Ext.reg('page-template-create',MODx.page.CreateTemplate);