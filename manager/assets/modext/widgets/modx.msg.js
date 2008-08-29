Ext.namespace('MODx.msg');
Ext.onReady(function() {
    MODx.msg = new MODx.Msg();
});

/**
 * Abstraction for Ext.Msg, adds connector handling ability
 * and spotlight features.
 *  
 * @class MODx.msg
 * @extends Ext.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-msg
 */
MODx.Msg = function(config) {
    config = config || {};
    
    this.sl = new Ext.Spotlight({
        easing: 'easeOut'
        ,duration: .3
    });
    MODx.Msg.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Msg,Ext.Component,{
    /**
     * @var {Ext.Spotlight} sl The spotlight object
     * @access private
     */
    sl: null
    
    /**
     * Loads a confirm dialog that, if proceeding, will post to a connector.
     * 
     * @access public
     * @param {Object} options An object of options to initialize with.
     */
    ,confirm: function(config) {
        Ext.Msg.confirm(config.title || _('warning'),config.text,function(e) {
            this.sl.hide();
            if (e == 'yes') {
                Ext.Ajax.request({
                    url: config.connector || config.url
                    ,params: config.params || {}
                    ,method: 'post'
                    ,scope: config.scope || this
                    ,success: function(r,o) {
                        r = Ext.decode(r.responseText);
                        if (r.success && config.success) {
                            Ext.callback(config.success,config.scope || this,[r,o]);
                        } else MODx.form.Handler.errorJSON(r);
                    }
                    ,failure: function(r,o) {
                        r = Ext.decode(r.responseText);
                        MODx.form.Handler.errorJSON(r);
                    }
                });
            }
        },this);
        this.sl.show(this.getWindow().getEl());
    }
    
    /**
     * Gets the Ext.Window being shown
     *
     * @access public
     * @return {Ext.Window} The window of the dialog
     */
    ,getWindow: function() {
        return Ext.Msg.getDialog();
    }
    
    /**
     * Displays a spotlighted alert box
     * 
     * @access public
     */
    ,alert: function(title,text,fn,scope) {
        scope = scope || this;
        if (typeof(fn) != 'function') {
            fn = function() { this.sl.hide(); };
        } else {
            fn = fn.createInterceptor(function() { this.sl.hide(); return true; },this);
        }
        Ext.Msg.alert(title,text,fn,scope);
        this.sl.show(this.getWindow().getEl());
    }
});
Ext.reg('modx-msg',MODx.Msg);