/**
 * Loads a grid of modAccessContexts.
 * 
 * @class MODx.grid.AccessContext
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-accesscontext
 */
MODx.grid.AccessContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ugc_grid_title')
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: {
            action: 'getList'
            ,type: config.type || 'modAccessContext'
        }
        ,fields: ['id','target','target_name','principal_class','principal','principal_name','authority','policy','policy_name','menu']
		,type: 'modAccessContext'
		,paging: true
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 40 }
            ,{ header: _('context_id') ,dataIndex: 'target' ,width: 40 }
            ,{ header: _('context') ,dataIndex: 'target_name' ,width: 150 }
            ,{ header: _('user_group_id') ,dataIndex: 'principal' ,width: 40 }
            ,{ header: _('user_group') ,dataIndex: 'principal_name' ,width: 150 }
            ,{ header: _('authority') ,dataIndex: 'authority' ,width: 75 }
            ,{ header: _('policy') ,dataIndex: 'policy_name' ,width: 175 }
        ]
		,tbar: this.getToolbar()
    });
    MODx.grid.AccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessContext,MODx.grid.Grid,{
	combos: {}
	,windows: {}
	
	,createAcl: function(itm,e) {
        var r = this.menu.record || {};
        Ext.applyIf(r,{
            context: r.target
            ,user_group: r.principal
        });
		if (!this.windows.create_acl) {
			this.windows.create_acl = new MODx.window.AccessContext({
	            scope: this
	            ,success: function(frm,a) {
	                var o = a.result.object;
	                this.getStore().baseParams = { 
	                    action: 'getList'
	                    ,type: this.config.type
	                    ,target: this.combos.ctx.getValue()
	                    ,principal: this.combos.ug.getValue()
	                    ,principal_class: 'modUserGroup'
	                };
	                this.refresh();
	            }
				,record: r
	        });
		} else {
			this.windows.create_acl.setValues(r);
		}
		        
        this.windows.create_acl.show(e.target);
	}
    
	,editAcl: function(itm,e) {
        var r = this.menu.record;
        Ext.applyIf(r,{
            context: r.target
            ,user_group: r.principal
        });
        if (!this.windows.update_acl) {
			this.windows.update_acl = new MODx.window.AccessContext({
	            id: r.id
	            ,scope: this
	            ,success: this.refresh
				,record: r
	        });
		} else {
			this.windows.update_acl.setValues(r);
		}
        this.windows.update_acl.show(e.target);
    }
	
    ,removeAcl: function(itm,e) {
        MODx.msg.confirm({
            title: _('ugc_remove')
            ,text: _('access_confirm_remove')
            ,connector: this.config.url
            ,params: {
                action: 'removeAcl'
                ,id: this.menu.record.id
                ,type: this.config.type
            }
            ,scope: this
            ,success: this.refresh
        });
    }
	
	,clearFilter: function(btn,e) {
        this.getStore().baseParams = { 
            action: 'getList'
            ,type: this.config.type
            ,target: ''
            ,principal: ''
            ,principal_class: 'modUserGroup'
        };
        this.combos.ug.setValue('');
        this.combos.ctx.setValue('');
        this.getStore().load();
	}
	
	,getToolbar: function() {
		this.combos.ug = new MODx.combo.UserGroup();
	    this.combos.ug.on('select',function(btn,e) {
	        this.getStore().baseParams = {
	            action: 'getList'
	            ,type: this.config.type
	            ,target: this.combos.rg.getValue()
	            ,principal: this.combos.ug.getValue()
	        }
	        this.getStore().load();
	    },this);
	    this.combos.ctx = new MODx.combo.Context();
	    this.combos.ctx.on('select',function(btn,e) {
	        this.getStore().baseParams = {
	            action: 'getList'
	            ,type: this.config.type
	            ,target: this.combos.ctx.getValue()
	            ,principal: this.combos.ug.getValue()
	        }
	        this.getStore().load();
	    },this);
	    
		return [
	    	_('context') +': '
			,this.combos.ctx
			,'-'
			,_('user_group') + ': '
			,this.combos.ug
			,'-'
			,{
		        text: _('clear_filter')
		        ,scope: this
		        ,handler: this.clearFilter
		    }
			,'->'
			,{
		        text: _('add')
		        ,scope: this
		        ,handler: this.createAcl
			}
	    ];
	}
});
Ext.reg('grid-accesscontext',MODx.grid.AccessContext);

/** 
 * Generates the modAccessContext window.
 *  
 * @class MODx.window.AccessContext
 * @extends MODx.Window
 * @constructor
 * @param {Object} An object of configuration options.
 * @xtype window-accesscontext
 */
MODx.window.AccessContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,height: 250
        ,width: 350
        ,type: 'modAccessContext'
        ,id: 0
    });
    MODx.window.AccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AccessContext,MODx.Window,{
    combos: {}
	
    ,_loadForm: function() {
        if (this.checkIfLoaded(this.config.record)) return false;
        if (this.config.id) {
            Ext.Ajax.request({
                url: MODx.config.connectors_url+'security/access/index.php'
                ,params: {
                    action: 'getAcl'
                    ,id: this.config.id
                    ,type: this.config.type
                }
                ,scope: this
                ,success: this.prepareForm
            });
        } else {
            this.prepareForm(null,null);
        }
    }
	
    ,prepareForm: function(r,o) {
        var data = {};
        if (r) {
            r = Ext.decode(r.responseText);
            if (r.success) {
                data = r.object;
                this.config.baseParams = {
                    action: 'updateAcl',
                    type: this.config.type
                }
            }
        }
        this.config.values = data;		
				
        this.fp = this.createForm({
            url: this.config.connector || MODx.config.connectors_url+'security/access/index.php'
            ,baseParams: this.config.baseParams || { action: 'addAcl', type: this.config.type }
			,items: [ 
            	{
                    fieldLabel: _('context')
                    ,name: 'target'
                    ,hiddenName: 'target'
                    ,xtype: 'combo-context'
                    ,value: data.context
                },{
                    fieldLabel: _('user_group')
                    ,name: 'principal'
                    ,hiddenName: 'principal'
                    ,xtype: 'combo-usergroup'
                    ,value: data.principal || ''
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                },{
	                fieldLabel: _('authority')
	                ,name: 'authority'
                    ,xtype: 'textfield'
	                ,width: 40
	                ,value: data.authority
	            },{
                    fieldLabel: _('policy')
                    ,name: 'policy'
                    ,hiddenName: 'policy'
                    ,xtype: 'combo-policy'
                    ,value: data.policy || ''
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                },{
	                name: 'principal_class'
                    ,xtype: 'hidden'
	                ,value: 'modUserGroup'
	            },{
	                name: 'id'
                    ,xtype: 'hidden'
	                ,value: data.id
	            }
			]
        });
        
        this.renderForm();
    }
});
Ext.reg('window-accesscontext',MODx.window.AccessContext);