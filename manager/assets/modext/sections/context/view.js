Ext.onReady(function() {
	MODx.load({
	   xtype: 'modx-context-view'
	   ,key: MODx.request.key
	});
});

/**
 * @class MODx.ViewContext
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-context-view
 */
MODx.ViewContext = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		form: 'context_data'
		,actions: {
            'new': MODx.action['context/create']
            ,edit: MODx.action['context/update']
            ,'delete': MODx.action['context/delete']
            ,cancel: MODx.action['context/view']
        }
        ,buttons: this.getButtons()
	});
	MODx.ViewContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.ViewContext,MODx.Component,{	
	getButtons: function(config) {
		var b = [];
	    b.push({
	        process: 'create'
	        ,text: _('new')
	        ,params: {
	            a: MODx.action['context/create']
	        }
	    },{
	        process: 'edit'
	        ,text: _('edit')
	        ,params: {
	            a: 'context/update'
	            ,key: config.key
	        }
	    },'-',{
	        process: 'duplicate'
	        ,text: _('duplicate')
	        ,method: 'remote'
	        ,confirm: _('context_duplicate_confirm')
	    });
		if (config.key != 'web' && config.key != 'mgr' && config.key != 'connector') {
			b.push({
				process: 'delete',
				text: _('delete'),
				method: 'remote',
				confirm: _('confirm_delete_context')
			});
		};
		b.push('-',{
	        process: 'cancel'
	        ,text: _('cancel')
	        ,params: {
	            a: MODx.action['context']
	        }
	    });
	    return b;
	}
});
Ext.reg('modx-context-view',MODx.ViewContext);