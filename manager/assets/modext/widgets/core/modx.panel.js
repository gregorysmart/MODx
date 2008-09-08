Ext.namespace('MODx.panel');
/**
 * An abstract class for Ext Panels in MODx. 
 * 
 * @class MODx.Panel
 * @extends Ext.Panel
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-panel
 */
MODx.Panel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        collapsible: true
    });
    MODx.Panel.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.Panel,Ext.Panel);
Ext.reg('modx-panel',MODx.Panel);

/**
 * An abstract class for Ext FormPanels in MODx. 
 * 
 * @class MODx.FormPanel
 * @extends Ext.FormPanel
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-formpanel
 */
MODx.FormPanel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        autoHeight: true
        ,collapsible: true
        ,bodyStyle: 'padding: 1em'
        ,border: false
        ,method: 'POST'
        ,errorReader: MODx.util.JSONReader
    });
    MODx.Panel.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        setup: true
    });
    this.getForm().addEvents({
        beforeSubmit: true
        ,success: true
    });
    this.fireEvent('setup',config);
};
Ext.extend(MODx.FormPanel,Ext.FormPanel,{
    /**
     * Submits the form to the connector.
     */
    submit: function(o) {
        var fm = this.getForm();
        if (fm.isValid()) {
        	this.fireEvent('beforeSubmit',{
        	   form: fm
        	   ,options: o
        	   ,config: this.config
        	});
            fm.submit({
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(f,a) {
                    MODx.form.Handler.errorExt(a.result,f);
                }
                ,success: function(f,a) {
                    if (this.config.success) {
                        Ext.callback(this.config.success,this.config.scope || this,[f,a]);
                    }
                    this.fireEvent('success',{
                        form:f
                        ,result:a.result
                        ,options:o
                        ,config:this.config
                    });
                    this.fireEvent('setup',this.config);
                }
            });
        }
    }
});
Ext.reg('modx-formpanel',MODx.FormPanel);


/**
 * An abstract class for Wizard panels in MODx
 * 
 * @class MODx.panel.Wizard
 * @extends Ext.Panel
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-wizard
 */
MODx.panel.Wizard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'card'
        ,activeItem: 0
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,width: 750
        ,firstPanel: ''
        ,lastPanel: ''
        ,defaults: { border: false }
        ,modal: true
        ,txtFinish: _('finish')
        ,txtNext: _('next')
        ,txtBack: _('back')
        ,bbar: [{
            id: 'pi-btn-bck'
            ,text: config.txtBack || _('back')
            ,handler: this.navHandler.createDelegate(this,[-1])
            ,scope: this
            ,disabled: true         
        },{
            id: 'pi-btn-fwd'
            ,text: config.txtNext || _('next')
            ,handler: this.navHandler.createDelegate(this,[1])
            ,scope: this
        }]
    });
    MODx.panel.Wizard.superclass.constructor.call(this,config);
    this.config = config;
    this.lastActiveItem = this.config.firstPanel;
    this._go();
};
Ext.extend(MODx.panel.Wizard,Ext.Panel,{
    /**
     * @var {Object} windows The object collection of windows
     * @access private
     */
    windows: {}
    
    /**
     * Launches the wizard.
     * 
     * @access private
     */
    ,_go: function() {
        this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        this.proceed(this.config.firstPanel);
    }
    
    /**
     * Handles navigation between panels
     * 
     * @access public
     * @param {Integer} dir Either 1 for forward, or -1 for backward
     */
    ,navHandler: function(dir) {
        this.doLayout();
        var a = this.getLayout().activeItem;
        if (dir == -1) {
            this.proceed(a.config.back || a.config.id);
        } else {
            a.submit({
                scope: this
                ,proceed: this.proceed
            });
        }
    }
    
    /**
     * Proceeds to the next frame
     * 
     * @access public
     * @param {String} id The id of the panel to proceed to
     */
    ,proceed: function(id) {
        this.doLayout();
        this.getLayout().setActiveItem(id);
        if (id == this.config.firstPanel) {
            this.getBottomToolbar().items.item(0).setDisabled(true);
            this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        } else if (id == this.config.lastPanel) {
            this.getBottomToolbar().items.item(1).setText(this.config.txtFinish);
        } else {
            this.getBottomToolbar().items.item(0).setDisabled(false);
            this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        }
    }
});
Ext.reg('modx-panel-wizard',MODx.panel.Wizard);