Ext.onReady(function() {
    MODx.load({ xtype: 'page-content-type'});
});
/**
 * Loads the content type management page
 * 
 * @class MODx.page.ContentType
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-content-type
 */
MODx.page.ContentType = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        formpanel: 'panel-contenttype'
        ,buttons: [{
            process: 'updateFromGrid'
            ,text: _('save')
            ,method: 'remote'
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
		,components: [{
            xtype: 'panel-contenttype'
            ,title: ''
            ,renderTo: 'panel-contenttype'
        }]
	});	
	MODx.page.ContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ContentType,MODx.Component);
Ext.reg('page-content-type',MODx.page.ContentType);