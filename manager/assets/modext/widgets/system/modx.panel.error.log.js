MODx.panel.ErrorLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'system/errorlog.php'
        ,baseParams: {
            action: 'clear'
        }
        ,width: '90%'
        ,autoHeight: true
        ,buttonAlign: 'center'
        ,hideLabels: true
        ,items: [{
            html: '<h2>'+_('error_log')+'</h2>'
            ,border: false
        },{
            html: '<p>'+_('error_log_desc')+'</p>'
            ,border: false
        },{
            xtype: 'textarea'
            ,name: 'log'
            ,grow: true
            ,width: '100%'
        }]
        ,buttons: [{
            text: _('clear')
            ,handler: this.clear
            ,scope: this
        }]
    });
    MODx.panel.ErrorLog.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.panel.ErrorLog,MODx.FormPanel,{
    setup: function() {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                },scope:this}
            }
        });
    }
    ,clear: function() {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'clear'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                },scope:this}
            }
        });
    }
});
Ext.reg('panel-error-log',MODx.panel.ErrorLog);