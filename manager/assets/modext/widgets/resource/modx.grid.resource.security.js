/**
 * Loads a grid of resource groups assigned to a resource. 
 * 
 * @class MODx.grid.ResourceSecurity
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-resource-security
 */
MODx.grid.ResourceSecurity = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('security')
        ,url: MODx.config.connectors_url+'resource/resourcegroup.php'
        ,baseParams: {
            action: 'getList'
            ,resource: config.resource
        }
        ,saveParams: {
            resource: config.resource
        }
        ,fields: ['id','name','access','menu']
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },{
            header: _('access')
            ,dataIndex: 'access'
            ,width: 80
            ,sortable: true
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        }]
        
    });
    MODx.grid.ResourceSecurity.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ResourceSecurity,MODx.grid.Grid);
Ext.reg('grid-resource-security',MODx.grid.ResourceSecurity);