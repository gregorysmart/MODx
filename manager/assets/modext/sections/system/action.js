Ext.namespace('MODx');
Ext.onReady(function() {
	MODx.load({ xtype: 'modx-action'});
});

/**
 * Loads the actions page
 * 
 * @class MODx.Action
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-action
 */
MODx.Action = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		tabs: [
            {contentEl: 'tab_actions', title: _('actions')}
            ,{contentEl: 'tab_menus', title: _('topmenu')}
        ]
        ,components: [{
            xtype: 'tree-action'
            ,el: 'modx_atree'
        },{
            xtype: 'tree-menu'
            ,el: 'modx_mtree'
        }]
	});
	MODx.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Action,MODx.Component);
Ext.reg('modx-action',MODx.Action);