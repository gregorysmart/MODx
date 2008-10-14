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
    Ext.applyIf(config,{
        title: _('packages')
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,fields: ['signature','created','updated','installed','state','workspace','provider','disabled','source','manifest','attributes','menu']
        ,columns: [
            { header: _('package_signature') ,dataIndex: 'signature' }
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
        this.loadConsole(btn,topic);
        
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'remove'
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


/** 
 * Generates the Package Downloader wizard.
 *  
 * @class MODx.window.PackageDownloader
 * @extends Ext.Window
 * @param {Object} config An object of options.
 * @xtype window-package-downloader
 */
MODx.window.PackageDownloader = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('package_retriever')
        ,id: 'window-package-downloader'
        ,layout: 'card'
        ,activeItem: 0
        ,closeAction: 'hide'
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,width: '90%'
        ,firstPanel: 'pd-start'
        ,lastPanel: 'pd-selpackage'
        ,defaults: { border: false }
        ,modal: true
        ,bbar: [{
            id: 'pd-btn-bck'
            ,text: _('back')
            ,handler: this.navHandler.createDelegate(this,[-1])
            ,scope: this
            ,disabled: true         
        },{
            id: 'pd-btn-fwd'
            ,text: _('next')
            ,handler: this.navHandler.createDelegate(this,[1])
            ,scope: this
        }]
        ,items: [{
            xtype: 'panel-pd-first'
        },{
            xtype: 'panel-pd-selprov'
        },{
            xtype: 'panel-pd-newprov'
        },{
            xtype: 'panel-pd-selpackage'
        }]
        ,listeners: {
            'show': {fn: this.onShow,scope: this}
        }
	});
	MODx.window.PackageDownloader.superclass.constructor.call(this,config);
	this.config = config;
	this.lastActiveItem = this.config.firstPanel;
};
Ext.extend(MODx.window.PackageDownloader,Ext.Window,{
	windows: {}
	
    ,onShow: function() {
        this.getBottomToolbar().items.item(1).setText(_('next'));
        this.proceed('pd-start');
    }
    
	,navHandler: function(dir) {
        this.doLayout();
        var a = this.getLayout().activeItem;
        if (dir === -1) {
            this.proceed(a.config.back || a.config.id);
        } else {
            a.submit({
            	scope: this
                ,proceed: this.proceed
            });
        }
	}
	
	,proceed: function(id) {
		this.getLayout().setActiveItem(id);
        if (id === this.config.firstPanel) {
            this.getBottomToolbar().items.item(0).setDisabled(true);
        } else if (id === this.config.lastPanel) {
        	this.getBottomToolbar().items.item(1).setText(_('finish'));
        } else {
            this.getBottomToolbar().items.item(0).setDisabled(false);
            this.getBottomToolbar().items.item(1).setText(_('next'));
        }
        this.center();
	}
});
Ext.reg('window-package-downloader',MODx.window.PackageDownloader);


MODx.panel.PDFirst = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        id: 'pd-start'
        ,back: 'pd-start'
        ,autoHeight: true
        ,defaults: { labelSeparator: '', border: false }
        ,bodyStyle: 'padding: 3em 3em'
        ,items: [{
        	html: '<h2>'+_('package_retriever')+'</h2>'
		},{
		    html: '<p>'+_('package_obtain_method')+'</p>'	
		    ,style: 'padding-bottom: 2em'
		},{
            boxLabel: _('provider_select')
            ,xtype: 'radio'
            ,inputValue: 'selprov'
            ,name: 'method'
            ,checked: true
        },{
            boxLabel: _('provider_add')
            ,xtype: 'radio'
            ,inputValue: 'newprov'
            ,name: 'method'
        },{
            boxLabel: _('package_search_local_title')
            ,xtype: 'radio'
            ,inputValue: 'local'
            ,name: 'method'
        }]
	});
	MODx.panel.PDFirst.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.panel.PDFirst,Ext.FormPanel,{
    submit: function(o) {
    	var va = this.getForm().getValues();
    	if (!va.method) {
    		
    	} else if (va.method === 'local') {
    	   this.searchLocal();
    	} else {
    	   Ext.callback(o.proceed,o.scope || this,['pd-'+va.method]);
    	}
    	// handle first panel submission here
    }
    
    ,searchLocal: function() {
    	MODx.msg.confirm({
           title: _('package_search_local_title')
           ,text: _('package_search_local_confirm')
           ,url: MODx.config.connectors_url+'workspace/packages.php'
           ,params: {
                action: 'scanLocal' 
    	   }
           ,listeners: {
                'success':{fn:function(r) {
                    Ext.getCmp('grid-package').refresh();
                    Ext.getCmp('window-package-downloader').hide();
                },scope:this}
           }
    	});
    }
});
Ext.reg('panel-pd-first',MODx.panel.PDFirst);


MODx.panel.PDSelProv = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pd-selprov'
        ,back: 'pd-start'
        ,autoHeight: true
        ,defaults: {border: false}
        ,bodyStyle: 'padding: 3em'
        ,items: [{
        	html: '<h2>'+ _('provider_select')+'</h2>'
        },{
            html: '<p>'+_('provider_select_desc')+'</p>'
            ,style: 'padding-bottom: 2em;'
        },{
            fieldLabel: _('provisioner')
            ,xtype: 'combo-provider'
            ,allowBlank: false
        },{
            text: _('provider_add_or')
            ,xtype: 'button'
            ,style: 'padding-top: 2em;'
            ,scope: this
            ,handler: function() {
            	Ext.getCmp('window-package-downloader').proceed('pd-newprov');
            }
        }]
    });
    MODx.panel.PDSelProv.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.panel.PDSelProv,Ext.FormPanel,{
    submit: function(o) {
    	if (this.getForm().isValid()) {
        	var vs = this.getForm().getValues();
            Ext.getCmp('tree-package-download').setProvider(vs.provider);
            Ext.getCmp('pd-selpackage').provider = vs.provider;
            Ext.callback(o.proceed,o.scope || this,['pd-selpackage']);
    	}
    }
});
Ext.reg('panel-pd-selprov',MODx.panel.PDSelProv);


MODx.panel.PDNewProv = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pd-newprov'
        ,back: 'pd-start'
        ,autoHeight: true
        ,defaults: { border: false }
        ,bodyStyle: 'padding: 3em'
        ,url: MODx.config.connectors_url+'workspace/providers.php'
        ,baseParams: {
        	action: 'create'
        }
        ,items: [{
        	html: '<h2>'+_('provider_add')+'</h2>'
        },{
            fieldLabel: _('name')
            ,xtype: 'textfield'
            ,name: 'name'
            ,allowBlank: false
            ,width: 200
        },{
            fieldLabel: _('description')
            ,xtype: 'textarea'
            ,name: 'description'
            ,width: 200
        },{
            fieldLabel: _('provider_url')
            ,xtype: 'textfield'
            ,name: 'service_url'
            ,vtype: 'url'
            ,allowBlank: false
            ,width: 300
        }]
    });
    MODx.panel.PDNewProv.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.panel.PDNewProv,Ext.FormPanel,{
    submit: function(o) {
    	if (this.getForm().isValid()) {
    		this.getForm().submit({
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(f,a) {
                    MODx.form.Handler.errorExt(a.result,f);
                }
                ,success: function(f,a) {
                	var p = a.result.object;
                    Ext.getCmp('tree-package-download').setProvider(p.id);
                    Ext.getCmp('pd-selpackage').provider = p.id;
                	Ext.callback(o.proceed,o.scope || this,['pd-selpackage']);
                    Ext.getCmp('window-package-downloader').center();
                }
    		});
    	}
    }
});
Ext.reg('panel-pd-newprov',MODx.panel.PDNewProv);



MODx.panel.PDSelPackage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pd-selpackage'
        ,back: 'pd-selprov'
        ,autoHeight: true
        ,bodyStyle: 'padding: 3em'
        ,url: MODx.config.connectors_url+'workspace/providers.php'
        ,baseParams: {
            action: 'download'
        }
        ,items: [{
        	html: '<h2>'+_('package_select_download')+'</h2>'
        	,border: false
        },{
            html: '<p>'+_('package_select_download_desc')+'</p>'
            ,style: 'padding-bottom: 2em'
            ,border: false
        },{
            xtype: 'panel-package-download'
        }]
    });
    MODx.panel.PDSelPackage.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.panel.PDSelPackage,Ext.FormPanel,{
    provider: null
    
    ,submit: function(o) {
        var pkgs = Ext.getCmp('tree-package-download').encode();        
        if (pkgs.length > 0) {
            this.getForm().submit({
                waitMsg: _('downloading')
                ,params: {
                    packages: pkgs
                    ,provider: this.provider
                }
                ,scope: this
                ,failure: function(f,a) {
                    MODx.form.Handler.errorExt(a.result,f);
                }
                ,success: function(f,a) {
                    Ext.getCmp('grid-package').refresh();
                    Ext.getCmp('window-package-downloader').hide();
                }
            });
        } else { Ext.Msg.alert('',_('package_select_download_ns')); }
    }
});
Ext.reg('panel-pd-selpackage',MODx.panel.PDSelPackage);