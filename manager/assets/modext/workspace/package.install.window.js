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
        ,hideLabels: true
        ,defaults: { labelSeparator: '', border: false }
        ,items: [{
            html: '<h2>'+_('license_agreement')+'</h2>'
        },{
            html: '<p>'+_('license_agreement_desc')+'</p>'   
            ,style: 'padding-bottom: 2em'
        },{
            xtype: 'textarea'
            ,style: 'font: arial; font-size: .9em'
            ,name: 'license'
            ,id: 'pi-license-box'
            ,width: '90%'
            ,height: 300
            ,value: ''
        },{
            boxLabel: _('license_agree')
            ,xtype: 'radio'
            ,inputValue: 'agree'
            ,name: 'agree'
        },{
            boxLabel: _('license_disagree')
            ,xtype: 'radio'
            ,inputValue: 'disagree'
            ,name: 'agree'
        }]
    });
    MODx.panel.PILicense.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PILicense,MODx.panel.WizardPanel,{
    submit: function() {
        var va = this.getForm().getValues();
        if (!va.agree) {
            
        } else if (va.agree === 'disagree') {
           Ext.getCmp('window-package-installer').hide();
        } else {
           Ext.getCmp('window-package-installer').fireEvent('proceed','pi-readme');
        }
    }
    
    ,fetch: function() {
        var sig = Ext.getCmp('grid-package').menu.record.signature;
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'workspace/packages.php'
            ,params: {
                action: 'getAttribute'
                ,signature: sig
                ,attr: 'license'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var a = r.object.attr;
                    var b = Ext.getCmp('pi-license-box');
                    if (a !== null && a !== 'null') {
                        b.setValue(a);
                    } else {
                        b.setValue('');
                    }
                },scope:this}
            }
        });
    }
});
Ext.reg('panel-pi-license',MODx.panel.PILicense);

MODx.panel.PIReadme = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pi-readme'
        ,back: 'pi-license'
        ,hideLabels: true
        ,defaults: { labelSeparator: '', border: false }
        ,items: [{
            html: '<h2>'+_('readme')+'</h2>'
        },{
            html: '<p>'+_('readme_desc')+'</p>'   
            ,style: 'padding-bottom: 2em'
        },{
            xtype: 'textarea'
            ,style: 'font: arial; font-size: .9em'
            ,name: 'readme'
            ,id: 'pi-readme-box'
            ,width: '90%'
            ,height: 300
            ,value: ''
        }]
    });
    MODx.panel.PIReadme.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PIReadme,MODx.panel.WizardPanel,{
    submit: function() {
        var va = this.getForm().getValues();
        Ext.getCmp('window-package-installer').fireEvent('proceed','pi-install');
    }
    ,fetch: function() {
        var sig = Ext.getCmp('grid-package').menu.record.signature;
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'workspace/packages.php'
            ,params: {
                action: 'getAttribute'
                ,signature: sig
                ,attr: 'readme'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var a = r.object.attr;
                    var b = Ext.getCmp('pi-readme-box');
                    if (a !== null && a !== 'null') {
                        b.setValue(a);
                    } else {
                        b.setValue('');
                    }
                },scope:this}
            }
        });
    }
});
Ext.reg('panel-pi-readme',MODx.panel.PIReadme);

MODx.panel.PIInstall = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pi-install'
        ,back: 'pi-readme'
        ,hideLabels: true
        ,defaults: { labelSeparator: '', border: false }
        ,bodyStyle: 'padding: 3em 3em'
        ,items: [{
            html: '<h2>'+_('setup_options')+'</h2>'
        },{
            html: '<p>'+_('setup_options_desc')+'</p>'   
            ,style: 'padding-bottom: 2em'
        },{
            html: ''
            ,id: 'setup-options'
        }]
    });
    MODx.panel.PIInstall.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PIInstall,MODx.panel.WizardPanel,{
    submit: function() {
        var va = this.getForm().getValues();
        Ext.getCmp('window-package-installer').fireEvent('finish');        
    }
    ,fetch: function() {
        var sig = Ext.getCmp('grid-package').menu.record.signature;
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'workspace/packages.php'
            ,params: {
                action: 'getAttribute'
                ,signature: sig
                ,attr: 'setup-options'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var a = r.object.attr;
                    var el = Ext.getCmp('setup-options').getEl();
                    if (a !== null && a !== 'null') {
                        el.update(a);
                    } else {
                        el.update('');
                    }
                },scope:this}
            }
        });
    }
});
Ext.reg('panel-pi-install',MODx.panel.PIInstall);