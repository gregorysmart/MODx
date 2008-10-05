Ext.onReady(function() {
	MODx.load({ xtype: 'page-action'});
});

/**
 * Loads the actions page
 * 
 * @class MODx.page.Action
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-action
 */
MODx.page.Action = function(config) {
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
	MODx.page.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Action,MODx.Component);
Ext.reg('page-action',MODx.page.Action);