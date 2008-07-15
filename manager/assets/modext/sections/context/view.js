Ext.namespace('MODx');

MODx.Context = function(config) {
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
	MODx.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Context,MODx.Component,{	
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