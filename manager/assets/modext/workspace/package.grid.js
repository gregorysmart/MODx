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
            ,{ header: _('package_state') ,dataIndex: 'state' }
            ,{ 
               header: _('workspace')
               ,dataIndex: 'workspace'
               ,editor: { xtype:'combo-workspace' ,renderer: true }
            },{ 
                header: _('provisioner')
                ,dataIndex: 'provider'
                ,editor: { xtype: 'combo-provider' ,renderer: true }
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
            ,handler: { xtype: 'window-package-installer' }
        }]
    });
    MODx.grid.Package.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Package,MODx.grid.Grid,{
    update: function() {
    	Ext.Msg.alert(_('information'), 'This feature is not yet implemented.');
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
    	if (this.console == null) {
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
    
    ,uninstall: function() {
    	
    }
    
    ,remove: function(btn,e) {
    	var r = this.menu.record;
        var topic = '/workspace/package/remove/'+r.signature+'/';
        this.loadConsole(btn,topic);
        
        Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'remove'
                ,signature: r.signature
                ,register: 'mgr'
                ,topic: topic
            }
            ,scope: this
            ,success: function(r) {
                this.console.complete();
                Ext.Msg.hide();
                this.refresh();
            }
            ,failure: function(r) {
            	this.console.complete();
                Ext.Msg.hide();
                this.refresh();
            }
        });
    }
    
    ,install: function(btn) {
    	var r = this.menu.record;
    	var topic = '/workspace/package/install/'+r.signature+'/';
    	this.loadConsole(btn,topic);
    	
    	Ext.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'install'
                ,signature: r.signature
                ,register: 'mgr'
                ,topic: topic
            }
            ,scope: this
            ,success: function(r,o) {
            	r = Ext.decode(r.responseText);
            	this.console.complete();
            	if (r.success == false) {
            	   Ext.Msg.hide();
            	}
            	this.refresh();
            }
    	});
    }
});
Ext.reg('grid-package',MODx.grid.Package);


/** 
 * Generates the Package Installer wizard.
 *  
 * @class MODx.window.CreateProvider
 * @extends Ext.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-package-installer
 */
MODx.window.PackageInstaller = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('package_retriever')
        ,id: 'window-package-installer'
        ,layout: 'card'
        ,activeItem: 0
        ,closeAction: 'hide'
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,width: 750
        ,firstPanel: 'pi-start'
        ,lastPanel: 'pi-selpackage'
        ,defaults: { border: false }
        ,modal: true
        ,bbar: [{
            id: 'pi-btn-bck'
            ,text: _('back')
            ,handler: this.navHandler.createDelegate(this,[-1])
            ,scope: this
            ,disabled: true         
        },{
            id: 'pi-btn-fwd'
            ,text: _('next')
            ,handler: this.navHandler.createDelegate(this,[1])
            ,scope: this
        }]
        ,items: [{
            xtype: 'panel-pi-first'
        },{
            xtype: 'panel-pi-selprov'
        },{
            xtype: 'panel-pi-newprov'
        },{
            xtype: 'panel-pi-selpackage'
        }]
        ,listeners: {
            'show': {fn: this.onShow,scope: this}
        }
	});
	MODx.window.PackageInstaller.superclass.constructor.call(this,config);
	this.config = config;
	this.lastActiveItem = this.config.firstPanel;
};
Ext.extend(MODx.window.PackageInstaller,Ext.Window,{
	windows: {}
	
    ,onShow: function() {
        this.getBottomToolbar().items.item(1).setText(_('next'));
        this.proceed('pi-start');
    }
    
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
	
	,proceed: function(id) {
		this.getLayout().setActiveItem(id);
        if (id == this.config.firstPanel) {
            this.getBottomToolbar().items.item(0).setDisabled(true);
        } else if (id == this.config.lastPanel) {
        	this.getBottomToolbar().items.item(1).setText(_('finish'));
        } else {
            this.getBottomToolbar().items.item(0).setDisabled(false);
            this.getBottomToolbar().items.item(1).setText(_('next'));
        }
	}
});
Ext.reg('window-package-installer',MODx.window.PackageInstaller);


MODx.panel.PiFirst = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        id: 'pi-start'
        ,back: 'pi-start'
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
	MODx.panel.PiFirst.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.panel.PiFirst,Ext.FormPanel,{
    submit: function(o) {
    	var va = this.getForm().getValues();
    	if (!va.method) {
    		
    	} else if (va.method == 'local') {
    	   this.searchLocal();
    	} else {
    	   Ext.callback(o.proceed,o.scope || this,['pi-'+va.method]);
    	}
    	// handle first panel submission here
    }
    
    ,searchLocal: function() {
    	MODx.msg.confirm({
           title: _('package_search_local_title')
           ,text: _('package_search_local_confirm')
           ,connector: MODx.config.connectors_url+'workspace/packages.php'
           ,params: {
                action: 'scanLocal' 
    	   }
    	   ,scope: this
    	   ,success: function(r) {
                Ext.getCmp('grid-package').refresh();
                Ext.getCmp('window-package-installer').hide();
    	   }
    	});
    }
});
Ext.reg('panel-pi-first',MODx.panel.PiFirst);


MODx.panel.PiSelProv = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pi-selprov'
        ,back: 'pi-start'
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
            	Ext.getCmp('window-package-installer').proceed('pi-newprov');
            }
        }]
    });
    MODx.panel.PiSelProv.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.panel.PiSelProv,Ext.FormPanel,{
    submit: function(o) {
    	if (this.getForm().isValid()) {
        	var vs = this.getForm().getValues();
            Ext.getCmp('grid-package-download').setProvider(vs.provider);          
            Ext.callback(o.proceed,o.scope || this,['pi-selpackage']);
    	}
    }
});
Ext.reg('panel-pi-selprov',MODx.panel.PiSelProv);


MODx.panel.PiNewProv = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pi-newprov'
        ,back: 'pi-start'
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
    MODx.panel.PiNewProv.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.panel.PiNewProv,Ext.FormPanel,{
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
                    var g = Ext.getCmp('grid-package-download').setProvider(p.id);
                	Ext.callback(o.proceed,o.scope || this,['pi-selpackage']);
                }
    		});
    	}
    }
});
Ext.reg('panel-pi-newprov',MODx.panel.PiNewProv);



MODx.panel.PiSelPackage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pi-selpackage'
        ,back: 'pi-selprov'
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
            xtype: 'grid-package-download'
            ,id: 'grid-package-download'
        }]
    });
    MODx.panel.PiSelPackage.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.panel.PiSelPackage,Ext.FormPanel,{
    submit: function(o) {
        var grid = Ext.getCmp('grid-package-download');
        var sels = grid.getSelectionModel().getSelections();
        var pkgs = [];
        for (var i=0,l=sels.length;i<l;i++) {
        	pkgs.push(sels[i].data);
        }
        if (pkgs.length > 0) {
            this.getForm().submit({
                waitMsg: _('downloading')
                ,params: {
                    packages: Ext.encode(pkgs)
                }
                ,scope: this
                ,failure: function(f,a) {
                    MODx.form.Handler.errorExt(a.result,f);
                }
                ,success: function(f,a) {
                    Ext.getCmp('grid-package').refresh();
                    grid.getSelectionModel().clearSelections();
                    Ext.getCmp('window-package-installer').hide();
                }
            });
        } else Ext.Msg.alert('',_('package_select_download_ns'));
    }
});
Ext.reg('panel-pi-selpackage',MODx.panel.PiSelPackage);


MODx.grid.PackageDownload = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('packages')
        ,url: MODx.config.connectors_url+'workspace/providers.php'
        ,baseParams: {
        	action: 'getPackages'
        }
        ,fields: ['signature'
            ,'name'
            ,'version'
            ,'release'
            ,'description'
            ,'location'
            ,'menu']
        ,paging: true
        ,layout: 'fit'
        ,preventRender: true
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:false})
        ,columns: [
            new Ext.grid.CheckboxSelectionModel()
            ,{
                header: _('name')
                ,dataIndex: 'signature'
            },{
                header: _('version')
                ,dataIndex: 'version'
            },{
                header: _('release')
                ,dataIndex: 'release'
            },{
                header: _('description')
                ,dataIndex: 'description'
            }
        ]
    });
    MODx.grid.PackageDownload.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.PackageDownload,MODx.grid.Grid,{
    setProvider: function(provider) {
    	this.baseParams.provider = provider;
        this.refresh();
    }
});
Ext.reg('grid-package-download',MODx.grid.PackageDownload);

