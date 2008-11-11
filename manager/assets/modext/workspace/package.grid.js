/**
 * Loads a grid of Packages.
 * 
 * @class MODx.grid.Package
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-package
 */
MODx.grid.Package = function(config) {
    config = config || {};
    var exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p style="padding: .7em 1em .3em;"><i>{readme}</i></p>'
        )
    });
    Ext.applyIf(config,{
        title: _('packages')
        ,id: 'grid-package'
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,fields: ['signature','created','updated','installed','state','workspace','provider','disabled','source','manifest','attributes','readme','menu']
        ,plugins: [exp]
        ,columns: [exp,{
               header: _('package_signature') ,dataIndex: 'signature' }
            ,{ header: _('created') ,dataIndex: 'created' }
            ,{ header: _('updated') ,dataIndex: 'updated' }
            ,{ header: _('installed') ,dataIndex: 'installed' ,renderer: this._rins }
            ,{ 
               header: _('workspace')
               ,dataIndex: 'workspace'
               ,editor: { xtype:'combo-workspace' ,renderer: true }
               ,editable: false
            },{ 
                header: _('provisioner')
                ,dataIndex: 'provider'
                ,editor: { xtype: 'combo-provider' ,renderer: true }
                ,editable: false
            },{
                header: _('disabled')
                ,dataIndex: 'disabled'
                ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
            }
        ]
        ,primaryKey: 'signature'
        ,paging: true
        ,autosave: true
        ,tbar: [{
            text: _('package_add')
            ,handler: { xtype: 'window-package-downloader' }
        }]
    });
    MODx.grid.Package.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Package,MODx.grid.Grid,{
    console: null
    
    ,update: function(btn,e) {
    	var r = this.menu.record;
        var topic = '/workspace/package/update/'+r.signature+'/';
        this.loadConsole(btn,topic);
        
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'update'
                ,signature: r.signature
                ,register: 'mgr'
                ,topic: topic
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.console.complete();
                    Ext.Msg.hide();
                    this.refresh();
                },scope:this}
                ,'failure': {fn:function(r) {
                	this.console.complete();
                	Ext.Msg.hide();
                	return false;
                },scope:this}
            }
        });
    }
    
    ,_rins: function(d,c) {
        switch(d) {
            case '':
            case null:
                c.css = 'not-installed';
                return _('not_installed');
            default:
                c.css = '';
                return d;
        }
    }
    
    ,loadConsole: function(btn,topic) {
    	if (this.console === null) {
            this.console = MODx.load({
               xtype: 'modx-console'
               ,register: 'mgr'
               ,topic: topic
            });
        } else {
            this.console.setRegister('mgr',topic);
        }
        this.console.show(btn);
    }
    
    ,getConsole: function() {
        return this.console;
    }
    
    ,uninstall: function(btn,e) {
    	var r = this.menu.record;
        var topic = '/workspace/package/uninstall/'+r.signature+'/';
        this.loadConsole(btn,topic);
        
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'uninstall'
                ,signature: r.signature
                ,register: 'mgr'
                ,topic: topic
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    this.console.complete();
                    Ext.Msg.hide();
                    this.refresh();
                },scope:this}
                ,'failure': {fn:function(r) {
                    this.console.complete();
                    Ext.Msg.hide();
                    this.refresh();
                },scope:this}
        	}
        });
    }
    
    ,remove: function(btn,e) {
    	var r = this.menu.record;
        var topic = '/workspace/package/remove/'+r.signature+'/';
        
        this.loadWindow(btn,e,{
            xtype: 'window-package-remove'
            ,record: {
                signature: r.signature
                ,topic: topic
                ,register: 'mgr'
            }
        });
    }
    
    ,install: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'window-package-installer'
            ,listeners: {
                'finish': {fn: function() { this._install(this.menu.record); },scope:this}
            }
        });
    }
    
    ,_install: function(r) {
        var topic = '/workspace/package/install/'+r.signature+'/';
        this.loadConsole(Ext.getBody(),topic);
        
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'install'
                ,signature: r.signature
                ,register: 'mgr'
                ,topic: topic
            }
            ,listeners: {
                'success': {fn:function() {
                    Ext.getCmp('window-package-installer').hide();
                    this.console.complete();
                    this.refresh();
                },scope:this}
                ,'failure': {fn:function() {
                    this.console.complete();
                    Ext.Msg.hide();
                    this.refresh();
                },scope:this}
            }
        });
    }
});
Ext.reg('grid-package',MODx.grid.Package);

MODx.window.RemovePackage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_remove')
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,baseParams: {
            action: 'uninstall'
        }
        ,defaults: { border: false }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'signature'
            ,value: config.signature
        },{
            html: _('package_remove_confirm')
        },MODx.PanelSpacer,{
            html: _('package_remove_force_desc') 
            ,border: false
        },MODx.PanelSpacer,{
            xtype: 'checkbox'
            ,name: 'force'
            ,boxLabel: _('package_remove_force')
            ,id: 'pr-force'
            ,labelSeparator: ''
            ,inputValue: 'true'
        }]
        ,saveBtnText: _('package_remove')
    });
    MODx.window.RemovePackage.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RemovePackage,MODx.Window,{
    submit: function() {
        var r = this.config.record;
        if (this.fp.getForm().isValid()) {            
            Ext.getCmp('grid-package').loadConsole(Ext.getBody(),r.topic);
            this.fp.getForm().baseParams = {
                action: 'remove'
                ,signature: r.signature
                ,register: 'mgr'
                ,topic: r.topic
                ,force: Ext.getCmp('pr-force').getValue()
            };
            
            this.fp.getForm().submit({ 
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(frm,a) {
                    this.fireEvent('failure',frm,a);
                    var g = Ext.getCmp('grid-package');
                    g.getConsole().complete();
                    g.refresh();
                    Ext.Msg.hide();
                    this.hide();
                }
                ,success: function(frm,a) {
                    this.fireEvent('success',{f:frm,a:a});
                    var g = Ext.getCmp('grid-package');
                    g.getConsole().complete();
                    g.refresh();
                    Ext.Msg.hide();
                    this.hide();
                }
            });
        }
    }
});
Ext.reg('window-package-remove',MODx.window.RemovePackage);