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
        ,modal: Ext.isIE ? false : true
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
    });
    MODx.window.PackageDownloader.superclass.constructor.call(this,config);
    this.config = config;
    this.lastActiveItem = this.config.firstPanel;
    this.on('show',this.onShow,this);
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
                    Ext.getCmp('modx-grid-package').refresh();
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
            fieldLabel: _('provider')
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
                    Ext.getCmp('modx-grid-package').refresh();
                    Ext.getCmp('window-package-downloader').hide();
                }
            });
        } else { Ext.Msg.alert('',_('package_select_download_ns')); }
    }
});
Ext.reg('panel-pd-selpackage',MODx.panel.PDSelPackage);