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
        title: _('console')
	    ,url: MODx.config.connectors_url+'system/registry/register.php'
	    ,baseParams: {
	    	action: 'read'
	    	,register: config.register || ''
	    	,topic: config.topic || ''
	    	,format: 'html_log'
	    	,remove_read: 0
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
            id: 'console-header'
            ,html: _('console_running')
            ,border: false
        },{
            xtype: 'panel'
            ,id: 'console-body'
            ,cls: 'modx-console'            
        }]
        ,buttons: [{
            text: 'Copy to Clipboard'
            ,handler: this.copyToClipboard
            ,scope: this
        },{
            text: _('ok')
            ,id: 'modx-console-ok'
            ,disabled: true
            ,scope: this
            ,handler: this.shutdown
        }]
        ,listeners: {
        	'show': {fn:this.init ,scope:this}
        }
	});
	MODx.Console.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.Console,Ext.Window,{
    mgr: null
    ,running: false
    
    ,init: function() {
       Ext.Msg.hide();
       if (MODx.util.LoadingBox) MODx.util.LoadingBox.disable();
       Ext.get('console-body').update('');
       if (this.running != true) {
           this.mgr = new Ext.Updater('console-body');
       }
       this.mgr.startAutoRefresh(.5,this.config.url,this.config.baseParams || {},this.renderMsg,true);
       this.running = true;
    }
    
    ,copyToClipboard: function() {
    	var c = Ext.get('console-body').dom.innerHTML;
    	c = Ext.util.Format.stripTags(c);
    	MODx.util.Clipboard.copy(c);
    }
    
    ,renderMsg: function(el,s,r,o) {
        r = Ext.decode(r.responseText);
        el.update(r.message);
    }
    
    ,setRegister: function(register,topic) {
    	this.config.baseParams.register = register;
        this.config.baseParams.topic = topic;
    }
    
    ,complete: function() {
    	Ext.getCmp('modx-console-ok').setDisabled(false);
    }
    
    ,shutdown: function() {
        this.mgr.stopAutoRefresh();
        if (MODx.util.LoadingBox) MODx.util.LoadingBox.enable();
    	Ext.Ajax.request({
    	    url: this.config.url
    	    ,params: {
                action: 'read'
                ,register: this.config.register || ''
                ,topic: this.config.topic || ''
                ,format: 'html_log'
            }
    	    ,scope: this
    	    ,success: function(r,o) {
    	    	r = Ext.decode(r.responseText);
    	    	if (r.success) {
    	    		Ext.getCmp('modx-console-ok').setDisabled(true);
                    this.hide();
    	    	} else MODx.form.Handler.errorJSON(r);
    	    }
    	});
    }
});
Ext.reg('modx-console',MODx.Console);