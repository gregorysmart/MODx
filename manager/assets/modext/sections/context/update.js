Ext.onReady(function() {
    MODx.load({
        xtype: 'page-context-update'
        ,context: MODx.request.key
    });
});

/** 
 * @class MODx.page.UpdateContext
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-context-update
 */
MODx.page.UpdateContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'panel-context'
        ,actions: {
            'new': MODx.action['context/create']
            ,edit: MODx.action['context/update']
            ,'delete': MODx.action['context/delete']
            ,cancel: MODx.action['context/view']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,keys: [{
                key: "s"
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {
                a: MODx.action['context']
            }
        }]
        ,components: [{
            xtype: 'panel-context'
            ,id: 'panel-context'
            ,renderTo: 'panel-context'
            ,context: config.context
        }]
    });
    MODx.page.UpdateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateContext,MODx.Component);
Ext.reg('page-context-update',MODx.page.UpdateContext);