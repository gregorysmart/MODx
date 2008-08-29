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
};
Ext.extend(MODx,Ext.Component,{
    config: {}
    ,util:{},window:{},panel:{},tree:{},form:{},grid:{},combo:{},toolbar:{},page:{}
    
    ,load: function() {
        var a = arguments, l = a.length;
        var os = [];
        for(var i = 0; i < l; i++) {
            var o = a[i];
            if (!o.xtype || o.xtype == '') return false;
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
        var arg = new Object();
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
MODx = new MODx();