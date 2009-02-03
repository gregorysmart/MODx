/**
 * Loads the TV creation page
 * 
 * @class MODx.page.CreateTV
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-tv-create
 */
MODx.page.CreateTV = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'modx-panel-tv'
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
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-tv'
            ,renderTo: 'modx-panel-tv'
            ,tv: 0
            ,category: config.category || 0
            ,name: ''
        }]
	});
	MODx.page.CreateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateTV,MODx.Component);
Ext.reg('modx-page-tv-create',MODx.page.CreateTV);