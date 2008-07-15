Ext.namespace('MODx','MODx.grid','MODx.toolbar','MODx.dialog', 'MODx.combo');
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
        this.menu.record.policy_data = eval(this.menu.record.policy_data);
		if (this.windows.policy_update) {
            this.windows.policy_update.destroy();
        }
		this.windows.policy_update = new MODx.window.UpdateAccessPolicy({
            policy: this.menu.record.id
            ,scope: this
            ,success: this.refresh
			,record: this.menu.record
            ,grid: this
        });
		this.windows.policy_update.show(e.target);
    }
});
Ext.reg('grid-accesspolicy',MODx.grid.AccessPolicy);

/**
 * Generates a window for updating Access Policies.
 *  
 * @class MODx.window.UpdateAccessPolicy
 * @extends MODx.Window 
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-accesspolicy-update
 */
MODx.window.UpdateAccessPolicy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        width: 600
        ,height: 600
        ,policy: 0
        ,id: 'window-policy-update'
		,title: _('policy_update')
    });
    MODx.window.UpdateAccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateAccessPolicy,MODx.Window,{
    combos: {}
    ,_loadForm: function() {
        if (this.checkIfLoaded(this.config.record)) return false;
        if (this.config.policy) {
            Ext.Ajax.request({
                url: MODx.config.connectors_url+'security/access/policy.php'
                ,params: {
                    action: 'get'
                    ,id: this.config.policy
                }
                ,scope: this
                ,success: function(r,o) {
                    r = Ext.decode(r.responseText);
                    if (r.success) {
                        this.prepareForm(r);
                    } else FormHandler.errorJSON(r);
                }
            });
        } else {
            this.prepareForm(null,null);
        }
    }
	
    ,prepareForm: function(r) {
        var data = r.object;
        
        if (data.policy_data == '{\nNULL\n}') data.policy_data = '{}';
        this.grid = new MODx.grid.PolicyProperty({
            policy: data.id
            ,grid: this.config.grid
            ,source: Ext.util.JSON.decode(data.policy_data)
        });
        
        this.config.values = data;
        this.fp = this.createForm({
            url: this.config.connector || MODx.config.connectors_url+'security/access/policy.php'
            ,baseParams: this.config.baseParams || { action: 'update' }
			,items: [{
                xtype: 'tabpanel'
                ,activeTab: 0
                ,border: false
                ,bodyStyle: {
                    padding: '1em'
                }
                ,autoHeight: true
                ,defaults: { border: false, autoHeight: true, labelAlign: 'top' }
                ,items: [{
                    title: 'General Information'
                    ,layout: 'form'
                    ,defaults: { autoHeight: true }
                    ,items: [{
                        fieldLabel: _('name')
                        ,name: 'name'
                        ,xtype: 'textfield'
                        ,width: 200
                        ,value: data.name || ''
                    },{
                        fieldLabel: _('description')
                        ,name: 'description'
                        ,xtype: 'textarea'
                        ,width: 300
                        ,height: 50
                        ,value: data.description || ''
                    },{
                        name: 'class'
                        ,xtype: 'hidden'
                        ,value: data.type || ''
                    },{
                        name: 'id'
                        ,xtype: 'hidden'
                        ,value: data.id || 0
                    }]
                },this.grid]
            }]
        });
        
        this.renderForm();
        this.setPosition(30,30);
    }
    
	/**
	 * Sets the form values for the dialog.
	 * @param {Object} r An object of values to set.
	 */
	,setValues: function(r) {
		if (r == null) return false;
        this.fp.getForm().setValues(r);
        if (this.grid) this.grid.setSource(r.data);
	}
});
Ext.reg('window-accesspolicy-update',MODx.window.UpdateAccessPolicy);


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


/**
 * Loads a property grid of modAccessPolicies properties.
 * 
 * @class MODx.grid.PolicyProperty
 * @extends Ext.grid.PropertyGrid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-policyproperty
 */
MODx.grid.PolicyProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('policy_data')
        ,source: null
        ,height: 300
        ,maxHeight: 300
        ,minColumnWidth: 150
        ,autoExpandColumn: 'name'
        ,autoWidth: true
        ,collapsible: true
        ,tbar: [{
            text: _('policy_property_new')
            ,scope: this
            ,handler: this.create
        }]
    });
    MODx.grid.PolicyProperty.superclass.constructor.call(this,config);
    this.config = config;
    
    this.on('afteredit',this.update,this);
    this.menu = new Ext.menu.Menu({ defaultAlign: 'tl-b?' });
    this.on('rowcontextmenu',this.showMenu,this);
};
Ext.extend(MODx.grid.PolicyProperty,Ext.grid.PropertyGrid,{
    create: function() {
        Ext.Msg.prompt(_('policy_property_create'),_('policy_property_specify_name'),function(btn,v) {
            if (btn == 'ok') {
                Ext.Ajax.request({
                    url: MODx.config.connectors_url+'security/access/policy.php'
                    ,params: {
                        action: 'createPolicyData'
                        ,id: this.config.policy
                        ,key: v
                    }
                    ,scope: this
                    ,success: function(r,o) {
                        r = Ext.decode(r.responseText);
                        if (r.success) {
                            var s = this.getSource();
                            s[v] = true;
                            this.setSource(s);
                            this.config.grid.refresh();
                        } else FormHandler.errorJSON(r);
                    }
                });
            }
        },this);
    }
    
    ,remove: function() {
        Ext.Ajax.request({
            url: MODx.config.connectors_url+'security/access/policy.php'
            ,params: {
                action: 'removePolicyData'
                ,id: this.config.policy
                ,key: this.menu.record
            }
            ,scope: this
            ,success: function(r,o) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    var s = this.getSource();
                    s[this.menu.record] = null;
                    this.setSource(s);
                    this.config.grid.refresh();
                } else FormHandler.errorJSON(r);
            }
        });
    }
    
    ,update: function(e) {
        Ext.Ajax.request({
           url: MODx.config.connectors_url+'security/access/policy.php'
           ,params: {
               action: 'updatePolicyData'
               ,id: this.config.policy
               ,key: e.record.data.name
               ,value: e.value
           }
           ,scope: this
           ,success: function(r,o) {
               r = Ext.decode(r.responseText);
               if (r.success) {
                   e.record.commit();
                   this.config.grid.refresh();
               } else {
                   FormHandler.errorJSON(r);
               }
           }
        });
    }
    
    ,showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        var sm = this.getSelectionModel();
                
        this.menu.removeAll();
        this.menu.record = sm.selection.record.id;
        this.menu.add({
            text: _('policy_property_remove')
            ,scope: this
            ,handler: this.remove
        })
        
        this.menu.show(e.target);
    }
});
Ext.reg('grid-policyproperty',MODx.grid.PolicyProperty);
