Ext.namespace('MODx');
Ext.onReady(function() {
    MODx.load({ xtype: 'modx-lexicon-management' });
});

MODx.LexiconManagement = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'grid-lexicon'
            ,id: 'grid-lexicon'
            ,renderTo: 'grid-lexicon'
        }]
    })
    MODx.LexiconManagement.superclass.constructor.call(this,config);
};
Ext.extend(MODx.LexiconManagement,MODx.Component);
Ext.reg('modx-lexicon-management',MODx.LexiconManagement);