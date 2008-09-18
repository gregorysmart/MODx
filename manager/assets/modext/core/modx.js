Ext.namespace('MODx');
/**
 * @class MODx
 * @extends Ext.Component
 * @param {Object} config An object of config properties
 * @xtype modx
 */
MODx = function(config) {
    config = config || {};
    MODx.superclass.constructor.call(this,config);
    this.config = config;
    this.initQuickTips();
    this.request = this.getURLParameters();
    this.Ajax = this.load({ xtype: 'modx-ajax' });
};
Ext.extend(MODx,Ext.Component,{
    config: {}
    ,util:{},window:{},panel:{},tree:{},form:{},grid:{},combo:{},toolbar:{},page:{},msg:{}
    ,Ajax:{}
    
    ,load: function() {
        var a = arguments, l = a.length;
        var os = [];
        for(var i = 0; i < l; i++) {
            var o = a[i];
            if (!o.xtype || o.xtype === '') {
                return false;
            }
            os.push(Ext.ComponentMgr.create(o));
        }
        return (os.length == 1) ? os[0] : os;
    }
    
    ,initQuickTips: function() {
        Ext.QuickTips.init();
        Ext.apply(Ext.QuickTips.getQuickTip(), {
            dismissDelay: 2300
        });
    }
    
    ,getURLParameters: function() {
        var arg = {};
        var href = document.location.href;
        
        if (href.indexOf( "?") != -1) {
            var params = href.split( "?")[1];
            var param = params.split("&");        
            for (var i = 0; i < param.length; ++i) {
                arg[param[i].split("=")[0]] = param[i].split("=")[1];
            }
        }
        return arg;
    }
});
Ext.reg('modx',MODx);


/**
 * An override class for Ext.Ajax, which adds success/failure events.
 * 
 * @class MODx.Ajax
 * @extends Ext.Component
 * @param {Object} config An object of config properties
 * @xtype modx-ajax
 */
MODx.Ajax = function(config) {
    config = config || {};
    MODx.Ajax.superclass.constructor.call(this,config);
    this.addEvents({
        'success': true
        ,'failure': true
    });
};
Ext.extend(MODx.Ajax,Ext.Component,{
    request: function(config) {
        this.purgeListeners();
        if (config.listeners) {
            for (var i in config.listeners) {
              var l = config.listeners[i];
              this.addListener(i,l.fn,l.scope || this,l.options || {});
            }
        }
        
        Ext.applyIf(config,{
            success: function(r,o) {
                r = Ext.decode(r.responseText);
                r.options = o;
                if (r.success) {
                    this.fireEvent('success',r);
                } else if (this.fireEvent('failure',r)) {
                    MODx.form.Handler.errorJSON(r);
                }
            }
            ,failure: function(r,o) {
            	r = Ext.decode(r.responseText);
            	r.options = o;
            	if (this.fireEvent('failure',r)) {
            		MODx.form.Handler.errorJSON(r);
            	}
            }
            ,scope: this
        });
        Ext.Ajax.request(config);
    }
});
Ext.reg('modx-ajax',MODx.Ajax);


MODx = new MODx();