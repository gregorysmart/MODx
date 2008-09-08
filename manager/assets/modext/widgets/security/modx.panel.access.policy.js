/**
 * 
 * @class MODx.panel.AccessPolicy
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-access-policy
 */
MODx.panel.AccessPolicy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/access/policy.php'
        ,baseParams: {
            action: 'update'
        }
        ,id: 'panel-access-policy'
        ,class_key: 'modAccessPolicy'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('policy')+': '+config.name+'</h2>'
            ,id: 'policy-name'
            ,border: false
        },{
            html: '<p>'+_('policy_desc')+'</p>'
            ,border: false
        },{
            xtype: 'hidden'
            ,name: 'id'
            ,value: config.plugin
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,width: 300
            ,maxLength: 255
            ,enableKeyEvents: true
            ,allowBlank: false
            ,listeners: {
                'keyup': {scope:this,fn:function(f,e) {
                    Ext.getCmp('policy-name').getEl().update('<h2>'+_('policy')+': '+f.getValue()+'</h2>');
                }}
            }
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,width: 300
            ,grow: true
        },{
            xtype: 'button'
            ,text: _('save')
            ,handler: this.submit
            ,scope: this
        },{
            html: '<br /><h2>'+_('policy_data')+'</h2>'
            ,border: false
        },{
            xtype: 'grid-policy-property'
            ,id: 'grid-policy-property'
            ,policy: null
            ,source: null
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.AccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.AccessPolicy,MODx.FormPanel,{
    setup: function() {
        if (this.config.policy == '' || this.config.policy == 0) return;
        Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.policy
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    this.getForm().setValues(r.object);
                    var data = Ext.util.JSON.decode(r.object.policy_data);
                    var g = Ext.getCmp('grid-policy-property');
                    g.setSource(data);
                    g.config.policy = r.object.id;
                    g.getView().refresh();
                    
                    Ext.getCmp('policy-name').getEl().update('<h2>'+_('policy')+': '+r.object.name+'</h2>');
                } else MODx.form.Handler.errorJSON(r);
            }
        })
    }
});
Ext.reg('panel-access-policy',MODx.panel.AccessPolicy);




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
        source: null
        ,height: 300
        ,maxHeight: 300
        ,autoHeight: true
        ,minColumnWidth: 250
        ,autoExpandColumn: 'name'
        ,autoWidth: true
        ,collapsible: true
        ,stripeRows: true
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
                        } else MODx.form.Handler.errorJSON(r);
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
                } else MODx.form.Handler.errorJSON(r);
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
               } else {
                   MODx.form.Handler.errorJSON(r);
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
Ext.reg('grid-policy-property',MODx.grid.PolicyProperty);