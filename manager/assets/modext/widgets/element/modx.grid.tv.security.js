/**
 * Loads a grid of resource groups assigned to a resource. 
 * 
 * @class MODx.grid.TVSecurity
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-tv-security
 */
MODx.grid.TVSecurity = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('access_permissions')
        ,id: 'modx-grid-tv-security'
        ,url: MODx.config.connectors_url+'element/tv/resourcegroup.php'
        ,baseParams: {
            action: 'getList'
            ,tv: config.tv
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
    MODx.grid.TVSecurity.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.TVSecurity,MODx.grid.Grid);
Ext.reg('modx-grid-tv-security',MODx.grid.TVSecurity);