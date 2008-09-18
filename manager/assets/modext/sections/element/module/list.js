Ext.onReady(function() {
	MODx.load({ xtype: 'page-modules' });
});
/**
 * Lists all current modules
 * 
 * @class MODx.page.Modules
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-modules 
 */
MODx.page.Modules = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        actions: {
            'new': MODx.action['element/module/create']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            method: 'new', text: _('new'), params: {a:MODx.action['element/module/create']}
        },'-',{
            method: 'cancel', text: _('cancel'), params: {a:MODx.action['element/module']}
        }]
        ,components: [{
            xtype: 'grid-module'
            ,renderTo: 'module_grid'
        }]
    });
	MODx.page.Modules.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Modules,MODx.Component);
Ext.reg('page-modules',MODx.page.Modules);