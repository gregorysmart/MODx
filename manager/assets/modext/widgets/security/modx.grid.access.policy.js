Ext.namespace('MODx.grid','MODx.window');
/**
 * Loads a grid of modAccessPolicies.
 * 
 * @class MODx.grid.AccessPolicy
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-accesspolicy
 */
MODx.grid.AccessPolicy = function(config) {
    config = config || {};    
    Ext.applyIf(config,{
		title: _('policies')
        ,url: MODx.config.connectors_url+'security/access/policy.php'
        ,fields: ['id','name','description','class','data','parent','menu']
		,paging: true
        ,autosave: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('policy_name')
            ,dataIndex: 'name'
            ,width: 200
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 375
            ,editor: { xtype: 'textfield' }
        }]
		,tbar: [{
        	text: _('add')
        	,scope: this
        	,handler: { xtype: 'window-accesspolicy-create' }
		}]
    });
    MODx.grid.AccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessPolicy,MODx.grid.Grid,{		
    editPolicy: function(itm,e) {
        location.href = '?a='+MODx.action['security/access/policy/update']+'&id='+this.menu.record.id;
    }
});
Ext.reg('grid-accesspolicy',MODx.grid.AccessPolicy);

/**
 * Generates a window for creating Access Policies.
 *  
 * @class MODx.window.CreateAccessPolicy
 * @extends MODx.Window 
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-accesspolicy-create
 */
MODx.window.CreateAccessPolicy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        width: 400
        ,title: _('policy_create')
        ,url: MODx.config.connectors_url+'security/access/policy.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,xtype: 'textarea'
            ,width: 230
            ,height: 50
        },{
            name: 'class'
            ,xtype: 'hidden'
        },{
            name: 'id'
            ,xtype: 'hidden'
        }]
    });
    MODx.window.CreateAccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateAccessPolicy,MODx.Window);
Ext.reg('window-accesspolicy-create',MODx.window.CreateAccessPolicy);
