/**
 * Renders pages and abstracts component creation to xtypes.
 * 
 * @class MODx.Component
 * @extends Ext.Component
 * @constructor
 * @param {Object} config A configuration object.
 */
MODx.Component = function(config) {
    config = config || {};
    MODx.Component.superclass.constructor.call(this,config);
    this.config = config;
    
    this._loadForm();
    if (this.config.tabs) {
        this._loadTabs();
    }
    this._loadComponents();
    this._loadActionButtons();
};
Ext.extend(MODx.Component,Ext.Component,{
	/**
     * @var {Object} The form fields for this component.
     * @access protected
     */
    fields: {}
    
    /**
     * Loads the form for the component.
     * 
     * @access protected
     */
	,_loadForm: function() {
		if (!this.config.form) { return false; }
        this.form = new Ext.form.BasicForm(Ext.get(this.config.form),{ errorReader : MODx.util.JSONReader });
        
        if (this.config.fields) {
        	for (var i in this.config.fields) {
        	   var f = this.config.fields[i];
               if (f.xtype) {
                f = Ext.ComponentMgr.create(f);
               }
        	   this.fields[i] = f;
        	   this.form.add(f);
        	}
        }
        this.form.render();
    }
    
    /**
     * Loads ActionButtons for the component.
     * 
     * @access protected
     */
	,_loadActionButtons: function() {
		if (!this.config.buttons) { return false; }
		this.ab = MODx.load({
            xtype: 'modx-actionbuttons'
            ,form: this.form || null
            ,formpanel: this.config.formpanel || null
            ,actions: this.config.actions || null
            ,id: this.config.id || null 
        });
        
        if (!this.config.buttons) {
            this.config.buttons = [];
        }        
        var l = this.config.buttons.length;
        for (var i=0; i<l; i++) {
        	var b = this.config.buttons[i];
        	if (b.refresh) {
        		b.onComplete = this.ab.refreshTreeNode.createDelegate(this,[b.refresh.tree,b.refresh.node,b.refresh.self || false]);
        	}
        	this.ab.create(b);
        }
        
        if (this.config.loadStay) { this.ab.loadStay(); }
	}
	
    /**
     * Loads MODx.Tabs
     * 
     * @access protected
     */
	,_loadTabs: function() {
		if (!this.config.tabs) { return false; }
        MODx.load({
            xtype: 'modx-tabs'
            ,renderTo: this.config.tabs_div || 'tabs_div'
            ,items: this.config.tabs
        });
	}
    
    /**
     * Loads all components by their xtype.
     * 
     * @access protected
     */
    ,_loadComponents: function() {
        if (!this.config.components) { return false; }
        var l = this.config.components.length;
        for (var i=0;i<l;i++) {
            Ext.ComponentMgr.create(this.config.components[i]);
        }
    }	
});
Ext.reg('modx-component',MODx.Component);