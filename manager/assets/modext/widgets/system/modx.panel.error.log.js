MODx.panel.ErrorLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'system/errorlog.php'
        ,id: 'modx-panel-error-log'
        ,baseParams: {
            action: 'clear'
        }
        ,buttonAlign: 'center'
        ,items: [{
            html: '<h2>'+_('error_log')+'</h2>'
            ,id: 'modx-error-log-header'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 1.5em;'
            ,hideLabels: true
            ,width: '100%'
            ,autoHeight: true
            ,buttonAlign: 'center'
            ,items: [{
                html: '<p>'+_('error_log_desc')+'</p>'
                ,border: false
            },{
                xtype: 'textarea'
                ,name: 'log'
                ,grow: true
                ,width: '98%'
            }]
        }]
        ,buttons: [{
            text: _('reload')
            ,handler: this.setup
            ,scope: this
        },{
            text: _('clear')
            ,handler: this.clear
            ,scope: this
            ,hidden: MODx.hasEraseErrorLog ? false : true
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
Ext.reg('modx-panel-error-log',MODx.panel.ErrorLog);