Ext.namespace('MODx','MODx.panel');

/**
 * Loads the Package Builder
 * 
 * @class MODx.panel.PackageBuilder
 * @extends MODx.panel.Wizard
 * @param {Object} config An object of config properties
 * @xtype panel-package-builder
 */
MODx.panel.PackageBuilder = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_builder')
        ,id: 'panel-package-builder'
        ,layout: 'card'
        ,firstPanel: 'pb-info'
        ,lastPanel: 'pb-build'
        ,txtFinish: _('build')
        ,items: [{
            xtype: 'panel-pb-info'
        },{
            xtype: 'panel-pb-autoselects'        
        },{
            xtype: 'panel-pb-selvehicle'
        },{
            xtype: 'panel-pb-build'
        }]
    });
    MODx.panel.PackageBuilder.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageBuilder,MODx.panel.Wizard);
Ext.reg('panel-package-builder',MODx.panel.PackageBuilder);

/**
 * 
 * @class MODx.panel.PackageInfo
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-pb-info
 */
MODx.panel.PackageInfo = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pb-info'
        ,url: MODx.config.connectors_url+'workspace/builder/index.php'
        ,baseParams: {
            action: 'create'
        }
        ,bodyStyle: 'padding: 3em 3em'
        ,defaults: { labelSeparator: '', border: false }
        ,items: [{
            html: '<h2>'+_('package_info')+'</h2>'
        },{
            html: '<p>'+_('package_info_desc')+'</p>'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('version')
            ,name: 'version'
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('release')
            ,name: 'release'
            ,allowBlank: false
        },{
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,id: 'pb-namespace'
            ,name: 'namespace'
            ,allowBlank: true
        }]
        ,listeners: {
            'success': {fn:function(o) {
                var c = o.options;
                
                var ns = Ext.getCmp('pb-namespace').getValue();
                var df = ns == 'core' ? false : true; 
                Ext.getCmp('pb-as-cb-ss').setValue(df);
                Ext.getCmp('pb-as-cb-cs').setValue(df);
                Ext.getCmp('pb-as-cb-le').setValue(df);
                Ext.getCmp('pb-as-cb-lf').setValue(df);
                
                Ext.getCmp('grid-vehicle').refresh();
                Ext.callback(c.proceed,c.scope || this,['pb-autoselects']);
            },scope:this}
        }
    });
    MODx.panel.PackageInfo.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageInfo,MODx.FormPanel);
Ext.reg('panel-pb-info',MODx.panel.PackageInfo);



/**
 * 
 * @class MODx.panel.PackageInfo
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-pb-info
 */
MODx.panel.PackageAutoSelects = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pb-autoselects'
        ,back: 'pb-info'
        ,url: MODx.config.connectors_url+'workspace/builder/index.php'
        ,baseParams: {
            action: 'autoselects'
        }
        ,bodyStyle: 'padding: 3em 3em'
        ,defaults: { labelSeparator: '', border: false }
        ,items: [{
            html: '<h2>'+_('package_autoselects')+'</h2>'
        },{
            html: '<p>'+_('package_autoselects_desc')+'</p>'
        },{
        	xtype: 'checkboxgroup'
        	,fieldLabel: _('classes')
        	,columns: 1
        	,defaults: {
        	   checked: Ext.getCmp('panel-package-builder').coreSelected ? false : true
        	   ,name: 'classes[]'
        	}
        	,items: [
        		{boxLabel: _('as_system_settings') ,inputValue: 'modSystemSetting' ,id: 'pb-as-cb-ss'}
        		,{boxLabel: _('as_context_settings') ,inputValue: 'modContextSetting' ,id: 'pb-as-cb-cs'}
        		,{boxLabel: _('as_lexicon_entries') ,inputValue: 'modLexiconEntry', id: 'pb-as-cb-le'}
        		,{boxLabel: _('as_lexicon_foci') ,inputValue: 'modLexiconFocus' ,id: 'pb-as-cb-lf'}
    		]
        }]
        ,listeners: {
            'success': {fn:function(o) {
                var c = o.options;
                Ext.getCmp('grid-vehicle').refresh();
                Ext.callback(c.proceed,c.scope || this,['pb-selvehicle']);
            },scope:this}
        }
    });
    MODx.panel.PackageAutoSelects.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageAutoSelects,MODx.FormPanel,{

});
Ext.reg('panel-pb-autoselects',MODx.panel.PackageAutoSelects);



/**
 * 
 * @class MODx.panel.SelectVehicles
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-pb-selvehicle
 */
MODx.panel.SelectVehicles = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pb-selvehicle'
        ,back: 'pb-autoselects'
        ,url: MODx.config.connectors_url+'workspace/builder/vehicle.php'
        ,baseParams: {
            action: 'create'
        }
        ,bodyStyle: 'padding: 3em 3em'
        ,defaults: { labelSeparator: '' }
        ,items: [{
            html: '<h2>'+_('vehicles_add')+'</h2>'
            ,border: false
        },{
            html: '<p>'+_('vehicles_desc')+'</p>'
            ,border: false
        },{
            xtype: 'grid-vehicle'
            ,id: 'grid-vehicle'
            ,preventRender: true
        }]
    });
    MODx.panel.SelectVehicles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.SelectVehicles,MODx.FormPanel,{
    submit: function(o) {
        Ext.callback(o.proceed,o.scope || this,['pb-build']);
    }
});
Ext.reg('panel-pb-selvehicle',MODx.panel.SelectVehicles);


/**
 * 
 * @class MODx.panel.BuildPackage
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-pb-build
 */
MODx.panel.BuildPackage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pb-build'
        ,back: 'pb-selvehicle'
        ,url: MODx.config.connectors_url+'workspace/builder/index.php'
        ,baseParams: {
            action: 'build'
        }
        ,bodyStyle: 'padding: 3em 3em'
        ,defaults: { labelSeparator: '', border: false }
        ,items: [{
            html: '<h2>'+_('package_build')+'</h2>'
        },{
            html: '<p>'+_('package_build_desc')+'</p>'
        }]
        ,listeners: {
            'success': {fn:function(o) {
                MODx.msg.alert('',o.result.message,function() {
                    var c = o.options;
                    Ext.getCmp('pb-info').getForm().reset();
                    Ext.callback(c.proceed,c.scope || this,['pb-info']);
                },this);
            },scope:this}
        }
    });
    MODx.panel.BuildPackage.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.BuildPackage,MODx.FormPanel);
Ext.reg('panel-pb-build',MODx.panel.BuildPackage);