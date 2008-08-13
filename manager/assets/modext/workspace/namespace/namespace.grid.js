Ext.namespace('MODx.grid','MODx.window');
/**
 * Loads a grid for managing namespaces.
 * 
 * @class MODx.grid.Namespace
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype grid-namespace
 */
MODx.grid.Namespace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('namespaces')
        ,url: MODx.config.connectors_url+'workspace/namespace.php'
        ,fields: ['id','name','path','menu']
        ,width: '97%'
        ,paging: true
        ,autosave: true
        ,primaryKey: 'name'
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },{
            header: _('path')
            ,dataIndex: 'path'
            ,width: 500
            ,sortable: false
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            text: _('search_by_key')
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'lf_filter_name'
            ,listeners: {
                'change': {fn:this.filter.createDelegate(this,['name'],true),scope:this}
                ,'render': {fn:function(tf) {
                    tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
                        tf.fireEvent('change'); 
                    },this);
                }}
            }
        },{
            text: _('create_new')
            ,handler: { xtype: 'window-namespace-create' ,blankValues: true }
            ,scope: this
        }]
    });
    MODx.grid.Namespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Namespace,MODx.grid.Grid,{
    filter: function(cb,nv,ov,name) {
        if (!name) return false;
        this.store.baseParams[name] = nv;
        this.refresh();
    }
});
Ext.reg('grid-namespace',MODx.grid.Namespace);
