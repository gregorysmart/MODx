Ext.onReady(function() {
    MODx.load({ xtype: 'page-messages' });
});

/**
 * @class MODx.page.Messages
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype page-messages
 */
MODx.page.Messages = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        tabs: [{
            contentEl: 'tab_list', title: _('messages')
        }]
        ,components: [{
            xtype: 'grid-message'
            ,renderTo: 'grid-message'
        }]
    });
    MODx.page.Messages.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Messages,MODx.Component);
Ext.reg('page-messages',MODx.page.Messages);