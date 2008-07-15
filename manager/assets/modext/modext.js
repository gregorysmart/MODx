Ext.namespace('MODx');

MODx = function(config) {
    config = config || {};
    MODx.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx,Ext.Component,{
    config: {}
    
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
});
Ext.reg('modx',MODx);
MODx = new MODx();