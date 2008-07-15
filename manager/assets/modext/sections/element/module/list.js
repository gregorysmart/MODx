Ext.namespace('MODx','MODx.Module');
Ext.onReady(function() {
	MODx.load({ xtype: 'modx-modules' });
});

/**
 * Lists all current modules
 * 
 * @class MODx.Modules
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-modules 
 */
MODx.Modules = function(config) {
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
    })
	MODx.Modules.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Modules,MODx.Component);
Ext.reg('modx-modules',MODx.Modules);