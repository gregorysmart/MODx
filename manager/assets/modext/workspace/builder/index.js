Ext.namespace('MODx');
Ext.onReady(function() {
    new MODx.PackageBuilder();
});

MODx.PackageBuilder = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'panel-package-builder'
            ,id: 'panel-package-builder'
            ,renderTo: 'panel-package-builder'
        }]
    })
    MODx.PackageBuilder.superclass.constructor.call(this,config);
    Ext.Ajax.timeout = 0;
};
Ext.extend(MODx.PackageBuilder,MODx.Component);
Ext.reg('modx-package-builder',MODx.PackageBuilder);