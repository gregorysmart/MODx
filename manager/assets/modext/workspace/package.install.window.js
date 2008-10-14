/**
 * @class MODx.Wizard
 * @extends Ext.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-wizard
 */
MODx.Wizard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'card'
        ,activeItem: 0
        ,closeAction: 'hide'
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,width: '90%'
        ,defaults: { border: false }
        ,modal: false
        ,bbar: [{
            id: 'pi-btn-bck'
            ,text: _('back')
            ,handler: function() { this.fireEvent('backward'); }
            ,scope: this
            ,disabled: true         
        },{
            id: 'pi-btn-fwd'
            ,text: _('next')
            ,handler: function() { this.fireEvent('forward'); }
            ,scope: this
        }]
        ,firstPanel: ''
        ,lastPanel: ''
    });
    MODx.Wizard.superclass.constructor.call(this,config);
    this.lastActiveItem = config.firstPanel;
    this.config = config;
    this.addEvents({
        'forward': true
        ,'backward': true
        ,'proceed': true
        ,'finish': true
    });
    
    this.on('show',this.onShow,this);
    this.on('forward',this.onForward,this);
    this.on('backward',this.onBackward,this);
    this.on('proceed',this.proceed,this);
};
Ext.extend(MODx.Wizard,Ext.Window,{
    windows: {}
    
    ,onForward: function() {
        this.navHandler(1);
    }
    ,onBackward: function() {
        this.navHandler(-1);
    }
    
    ,onShow: function() {
        this.getBottomToolbar().items.item(1).setText(_('next'));
        this.fireEvent('proceed',this.config.firstPanel);
    }
        
    ,navHandler: function(dir) {
        this.doLayout();
        var a = this.getLayout().activeItem;
        if (dir === -1) {
            this.fireEvent('proceed',a.config.back || a.config.id);
        } else {
            a.submit();
        }
    }
    
    ,proceed: function(panel) {
        this.getLayout().setActiveItem(panel);
        if (panel === this.config.firstPanel) {
            this.getBottomToolbar().items.item(0).setDisabled(true);
        } else if (panel === this.config.lastPanel) {
            this.getBottomToolbar().items.item(1).setText(_('finish'));
        } else {
            this.getBottomToolbar().items.item(0).setDisabled(false);
            this.getBottomToolbar().items.item(1).setText(_('next'));
        }
        this.center();
    }
});
Ext.reg('modx-wizard',MODx.Wizard);


/** 
 * Generates the Package Installer wizard.
 *  
 * @class MODx.window.PackageInstaller
 * @extends MODx.Wizard
 * @param {Object} config An object of options.
 * @xtype window-package-installer
 */
MODx.window.PackageInstaller = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: 'Package Installer'
        ,id: 'window-package-installer'
        ,firstPanel: 'pi-license'
        ,lastPanel: 'pi-install'
        ,items: [{
            xtype: 'panel-pi-license'
        },{
            xtype: 'panel-pi-readme'
        },{
            xtype: 'panel-pi-install'
        }]
    });
    MODx.window.PackageInstaller.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.PackageInstaller,MODx.Wizard);
Ext.reg('window-package-installer',MODx.window.PackageInstaller);




MODx.panel.PILicense = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pi-license'
        ,back: 'pi-license'
        ,autoHeight: true
        ,hideLabels: true
        ,defaults: { labelSeparator: '', border: false }
        ,bodyStyle: 'padding: 3em 3em'
        ,items: [{
            html: '<h2>'+'License Agreement'+'</h2>'
        },{
            html: '<p>'+'Please review the license agreement for this package.'+'</p>'   
            ,style: 'padding-bottom: 2em'
        },{
            xtype: 'textarea'
            ,style: 'font: arial; font-size: .9em'
            ,name: 'license'
            ,id: 'pi-license-box'
            ,width: '90%'
            ,height: 300
            ,value: 'license info would go here...'
        },{
            boxLabel: 'I Agree'
            ,xtype: 'radio'
            ,inputValue: 'agree'
            ,name: 'agree'
        },{
            boxLabel: 'I Disagree'
            ,xtype: 'radio'
            ,inputValue: 'disagree'
            ,name: 'agree'
        }]
    });
    MODx.panel.PILicense.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.panel.PILicense,Ext.FormPanel,{
    submit: function() {
        var va = this.getForm().getValues();
        if (!va.agree) {
            
        } else if (va.agree === 'disagree') {
           Ext.getCmp('window-package-installer').hide();
        } else {
           Ext.getCmp('window-package-installer').fireEvent('proceed','pi-readme');
        }
    }
});
Ext.reg('panel-pi-license',MODx.panel.PILicense);

MODx.panel.PIReadme = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pi-readme'
        ,back: 'pi-license'
        ,autoHeight: true
        ,hideLabels: true
        ,defaults: { labelSeparator: '', border: false }
        ,bodyStyle: 'padding: 3em 3em'
        ,items: [{
            html: '<h2>'+'Readme'+'</h2>'
        },{
            html: '<p>'+'Please review the README for this package.'+'</p>'   
            ,style: 'padding-bottom: 2em'
        },{
            xtype: 'textarea'
            ,style: 'font: arial; font-size: .9em'
            ,name: 'readme'
            ,id: 'pi-readme-box'
            ,width: '90%'
            ,height: 300
            ,value: 'readme would go here...'
        }]
    });
    MODx.panel.PIReadme.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.panel.PIReadme,Ext.FormPanel,{
    submit: function() {
        var va = this.getForm().getValues();
        Ext.getCmp('window-package-installer').fireEvent('proceed','pi-install');
    }
});
Ext.reg('panel-pi-readme',MODx.panel.PIReadme);



MODx.panel.PIInstall = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pi-install'
        ,back: 'pi-readme'
        ,autoHeight: true
        ,hideLabels: true
        ,defaults: { labelSeparator: '', border: false }
        ,bodyStyle: 'padding: 3em 3em'
        ,items: [{
            html: '<h2>'+'Setup Options'+'</h2>'
        },{
            html: '<p>'+'Please choose the appropriate options (if applicable) and click Finish to install the package.'+'</p>'   
            ,style: 'padding-bottom: 2em'
        }]
    });
    MODx.panel.PIInstall.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.panel.PIInstall,Ext.FormPanel,{
    submit: function() {
        var va = this.getForm().getValues();
        Ext.getCmp('window-package-installer').fireEvent('finish');        
    }
});
Ext.reg('panel-pi-install',MODx.panel.PIInstall);