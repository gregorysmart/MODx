Ext.onReady(function() {
    MODx.load({ xtype: 'rm-page-repository'});
});
/**
 * Loads the repository management page
 * 
 * @class RM.page.Repository
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-repository
 */
RM.page.Repository = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'rm-grid-repository'
            ,renderTo: 'grid-repository'
        }]
    }); 
    RM.page.Repository.superclass.constructor.call(this,config);
};
Ext.extend(RM.page.Repository,MODx.Component);
Ext.reg('rm-page-repository',RM.page.Repository);