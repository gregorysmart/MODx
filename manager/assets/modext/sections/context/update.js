Ext.onReady(function() {
    MODx.load({
        xtype: 'modx-context-update'
        ,context: MODx.request.key
    });
});

/** 
 * @class MODx.UpdateContext
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-context-update
 */
MODx.UpdateContext = function(config) {
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
            ,refresh: {
                tree: 'modx_resource_tree'
                ,node: 'n_'+config.context
                ,self: true
            }
            ,listeners: {
                'click': {fn:function(btn,e) {
                    var g = Ext.getCmp('grid-context-setting');
                    Ext.apply(this.ab.config.params,{
                        settings: g.encodeModified()
                    });
                },scope:this}
                ,'success': function(o,i,r) {
                    Ext.getCmp('grid-context-setting').getStore().commitChanges();
                }
            }
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
    MODx.UpdateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.UpdateContext,MODx.Component);
Ext.reg('modx-context-update',MODx.UpdateContext);