Ext.onReady(function() {
    MODx.load({ xtype: 'modx-namespace' });
});

MODx.Namespace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'grid-namespace'
            ,renderTo: 'grid-namespace'
        }]
    });
    MODx.Namespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Namespace,MODx.Component);
Ext.reg('modx-namespace',MODx.Namespace);