/**
 * Loads a grid of Manager Logs.
 * 
 * @class MODx.grid.ManagerLog
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-manager-log
 */
MODx.grid.ManagerLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('manager_log')
        ,id: 'grid-manager-log'
        ,url: MODx.config.connectors_url+'system/log.php'
        ,fields: ['id','user','occurred'
            ,'action','classKey','item','menu']
        ,width: 800
        ,autosave: true
        ,paging: true
        ,columns: [{
            header: _('occurred')
            ,dataIndex: 'occurred'
            ,width: 125
        },{
            header: _('user')
            ,dataIndex: 'user'
            ,width: 200
            ,editor: { xtype: 'combo-user' ,renderer: true }
            ,editable: false
        },{
            header: _('action')
            ,dataIndex: 'action'
            ,width: 125
        }]
    });
    MODx.grid.ManagerLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ManagerLog,MODx.grid.Grid);
Ext.reg('grid-manager-log',MODx.grid.ManagerLog);

/**
 * Loads the Manager Log filter panel.
 * 
 * @class MODx.panel.ManagerLog
 * @extends MODx.FormPanel
 * @constructor
 * @param {Object} config An object of options.
 * @xtype panel-manager-log
 */
MODx.panel.ManagerLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('filter')
        ,width: 450
        ,items: this.getItems()
        ,buttons: [{
            text: _('filter_clear')
            ,scope: this
            ,handler: function() {
                this.getForm().reset();
                this.filter();
            }
        }]
    });
    MODx.panel.ManagerLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ManagerLog,MODx.FormPanel,{
    /**
     * Gets the items for this panel
     * @return array An array of items 
     */
    getItems: function() {
        var lsr = {
            'change': {fn:this.filter,scope: this}
            ,'render': {fn:this._addEnterKeyHandler}
        };
        return [{
            xtype: 'combo-user'
            ,fieldLabel: _('user')
            ,name: 'user'
            ,listeners: {
                'select': {fn: this.filter, scope: this}
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('action')
            ,name: 'action_type'
            ,listeners: lsr
        },{
            xtype: 'datefield'
            ,fieldLabel: _('date_start')
            ,name: 'date_start'
            ,allowBlank: true
            ,listeners: lsr
        },{
            xtype: 'datefield'
            ,fieldLabel: _('date_end')
            ,name: 'date_end'
            ,allowBlank: true
            ,listeners: lsr
        }]
    }
    /**
     * Filters the grid via the panel fields
     * @param {Ext.form.Field} tf
     * @param {String} newValue
     * @param {String} oldValue
     */
    ,filter: function(tf,newValue,oldValue) {
        var p = this.getForm().getValues();
        p.start = 0;
        p.limit = 20;
        Ext.getCmp('grid-manager-log').getStore().load({
            params: p
            ,scope: this
        });
    }
    /**
     * Adds an enter key handler to a field
     */
    ,_addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change'); 
        },this);
    }
});
Ext.reg('panel-manager-log',MODx.panel.ManagerLog);