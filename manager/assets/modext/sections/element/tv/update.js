/**
 * Loads the TV update page
 * 
 * @class MODx.page.UpdateTV
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-tv-update
 */
MODx.page.UpdateTV = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'modx-panel-tv'		
		,actions: {
            'new': MODx.action['element/tv/create']
            ,edit: MODx.action['element/tv/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: 's'
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
            xtype: 'modx-panel-tv'
            ,renderTo: 'modx-panel-tv'
            ,tv: config.id
            ,name: ''
        }]
	});
	MODx.page.UpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateTV,MODx.Component);
Ext.reg('modx-page-tv-update',MODx.page.UpdateTV);