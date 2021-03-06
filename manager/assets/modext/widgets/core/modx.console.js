MODx.Console = function(config) {
	config = config || {};
	Ext.Updater.defaults.showLoadIndicator = false;
	Ext.applyIf(config,{
        title: _('console')
	    ,modal: Ext.isIE ? false : true
        ,shadow: true
        ,resizable: false
        ,collapsible: false
        ,closable: false
        ,maximizable: true
        ,autoScroll: true
        ,height: 400
        ,width: 650
        ,refreshRate: 2
        ,cls: 'modx-window modx-console'
        ,items: [{
            itemId: 'header'
            ,cls: 'modx-console-text'
            ,html: _('console_running')
            ,border: false
        },{
            xtype: 'panel'
            ,itemId: 'body'
            ,cls: 'x-form-text modx-console-text'
        }]
        ,buttons: [{
            text: _('console_download_output')
            ,handler: this.download
            ,scope: this
        },{
            text: _('ok')
            ,id: 'modx-console-ok'
            ,itemId: 'okBtn'
            ,disabled: true
            ,scope: this
            ,handler: this.hideConsole
        }]
	});
	MODx.Console.superclass.constructor.call(this,config);
	this.config = config;
    this.addEvents({
        'shutdown': true
        ,'complete': true
    });
    this.on('show',this.init,this);
    this.on('hide',function() {
        this.getComponent('body').el.update('');
    });
    
};
Ext.extend(MODx.Console,Ext.Window,{
    mgr: null
    ,running: false
    
    ,init: function() {
        Ext.Msg.hide();
        this.fbar.getComponent('okBtn').setDisabled(true);
        this.getComponent('body').el.dom.innerHTML = '';
        
        this.provider = new Ext.direct.PollingProvider({
            type:'polling'
            ,url: MODx.config.connectors_url+'system/index.php'
            ,interval: 1000
            ,baseParams: {
                action: 'console'
                ,register: this.config.register || ''
                ,topic: this.config.topic || ''
                ,show_filename: this.config.show_filename || 0
                ,format: this.config.format || 'html_log'
            }
        });
        Ext.Direct.addProvider(this.provider);
        Ext.Direct.on('message', function(e,p) {
            if (e.data.search('COMPLETED') != -1) {
                this.provider.disconnect();
                this.fireEvent('complete');
                this.fbar.getComponent('okBtn').setDisabled(false);
            } else {
                var out = this.getComponent('body');
                if (out) {
                    out.el.insertHtml('beforeEnd',e.data);
                    e.data = '';
                    out.el.scroll('b', out.el.getHeight(), true);
                }
            }
            delete e;
        },this);
    }
    
    ,download: function() {
        var c = this.getComponent('body').getEl().dom.innerHTML;
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'system/index.php'
            ,params: {
                action: 'downloadOutput'
                ,data: c
            }
            ,listeners: {
                'success':{fn:function(r) {
                    location.href = MODx.config.connectors_url+'system/index.php?action=downloadOutput&download='+r.message;
                },scope:this}
            }            
        });
    }
        
    ,setRegister: function(register,topic) {
    	this.config.register = register;
        this.config.topic = topic;
    }
    
    ,hideConsole: function() {
        this.provider.disconnect();
        this.fireEvent('shutdown');
        this.hide();
    }
});
Ext.reg('modx-console',MODx.Console);