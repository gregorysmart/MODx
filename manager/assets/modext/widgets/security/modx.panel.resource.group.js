MODx.panel.ResourceGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-resource-groups'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{ 
             html: '<h2>'+_('resource_groups')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-resource-groups-header'
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 1.5em;'
            ,defaults: { border: false ,autoHeight: true }
            ,items: [{
                html: '<p>'+_('rrg_drag')+'</p>'
            },{
                layout: 'column'
                ,defaults: { border: false }
                ,items: [{
                    columnWidth: .4
                    ,layout: 'fit'
                    ,style: 'padding: .4em;'
                    ,items: [{
                        xtype: 'tree-resourcegroup'
                        ,id: 'gr-tree-resourcegroup'
                        ,height: 400
                    }]
                },{
                    columnWidth: .4
                    ,layout: 'fit'
                    ,style: 'padding: .4em;'
                    ,items: [{
                        xtype: 'tree-resource'
                        ,id: 'gr-tree-resource'
                        ,title: _('resources')
                        ,width: 300
                        ,remoteToolbar: false
                        ,enableDrop: true
                    }]
                }]
            }]
        }]
    });
    MODx.panel.ResourceGroups.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ResourceGroups,MODx.FormPanel);
Ext.reg('modx-panel-resource-groups',MODx.panel.ResourceGroups);

