MODx.panel.Workspace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-workspace'
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('package_management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-workspace-header'
        },{
            xtype: 'portal'
            ,items: [{
                columnWidth: 1
                ,items: [{
                    title: _('packages')
                    ,items: [{
                        html: '<p>'+_('packages_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'modx-grid-package'
                        ,id: 'modx-grid-package'
                        ,title: ''
                        ,preventRender: true
                    }]
                },{
                    title: _('providers')
                    ,collapsed: true
                    ,items: [{
                        html: '<p>'+_('providers_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'modx-grid-provider'
                        ,id: 'modx-grid-provider'
                        ,title: ''
                        ,preventRender: true
                    }]
                }]
            }]
        }]
    });
    MODx.panel.Workspace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Workspace,MODx.FormPanel);
Ext.reg('modx-panel-workspace',MODx.panel.Workspace);
