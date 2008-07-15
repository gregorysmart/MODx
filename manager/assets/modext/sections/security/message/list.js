Ext.namespace('MODx');
Ext.onReady(function() {
    new MODx.Messages();
});

MODx.Messages = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        tabs: [{
            contentEl: 'tab_list', title: _('messages')
        }]
        ,components: [{
            xtype: 'grid-message'
            ,el: 'grid-message'
        }]
    });
    MODx.Messages.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Messages,MODx.Component);
Ext.reg('modx-messages',MODx.Messages);