Ext.onReady(function() {
    MODx.load({
        xtype: 'modx-page-context-update'
        ,context: MODx.request.key
    });
});

/** 
 * @class MODx.page.UpdateContext
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-context-update
 */
MODx.page.UpdateContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-context'
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
            xtype: 'modx-panel-context'
            ,renderTo: 'modx-panel-context-div'
            ,context: config.context
        }]
    });
    MODx.page.UpdateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateContext,MODx.Component);
Ext.reg('modx-page-context-update',MODx.page.UpdateContext);