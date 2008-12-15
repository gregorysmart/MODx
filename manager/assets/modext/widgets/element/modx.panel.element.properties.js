MODx.panel.ElementProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'panel-element-properties'
        ,title: _('properties')
        ,bodyStyle: 'padding: 1.5em;'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{        
            html: '<p>'+_('element_properties_desc')+'</p>'
            ,border: false
        },MODx.PanelSpacer,{
            xtype: 'grid-element-properties'
            ,panel: config.elementPanel
            ,elementId: config.elementId
            ,elementType: config.elementType
        }]
    });
    MODx.panel.ElementProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ElementProperties,MODx.Panel);
Ext.reg('panel-element-properties',MODx.panel.ElementProperties);