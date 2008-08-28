/**
 * Displays a running console showing logs until a success messsage is sent
 * from the processor.
 * 
 * @class MODx.Console
 * @extends Ext.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-console
 */
MODx.Console = function(config) {
	config = config || {};
	Ext.Updater.defaults.showLoadIndicator = false;
	Ext.applyIf(config,{
        title: _('progress')
	    ,url: MODx.config.connectors_url+'index.php'
	    ,baseParams: {
	    	action: 'console'
	    }
	    ,modal: true
        ,shadow: true
        ,resizable: false
        ,collapsible: false
        ,closable: false
        ,maximizable: true
        ,autoScroll: true
        ,height: 400
        ,width: 550
        ,bodyStyle: 'background-color: white; padding: .75em; font-family: Courier'
        ,items: [{
            xtype: 'panel'
            ,id: 'console-body'
            ,cls: 'modx-console'
        }]
        ,buttons: [{
            text: _('ok')
            ,id: 'modx-console-ok'
            ,disabled: true
            ,scope: this
            ,handler: function() { this.hide(); }
        }]
        ,listeners: {
        	'show': {fn:this.init ,scope:this}
        }
	});
	MODx.Console.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.Console,Ext.Window,{
    init: function() {
       Ext.Msg.hide();
       if (MODx.util.LoadingBox) MODx.util.LoadingBox.disable();
       
       var mgr = new Ext.Updater('console-body');
       mgr.startAutoRefresh(1,this.config.url,this.config.baseParams || {},function(el,s,r,o) {
           r = Ext.decode(r.responseText);
           if (r.success == true) {
               mgr.stopAutoRefresh();
               el.update(r.message);
               Ext.getCmp('modx-console-ok').setDisabled(false);
               return false;
           } else {
               el.update(r.message);
           }
       },true);
    }
});
Ext.reg('modx-console',MODx.Console);