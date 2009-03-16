/**
 * Loads the Resource TV Panel
 * 
 * @class MODx.panel.ResourceTV
 * @extends MODx.Panel
 * @param {Object} config
 * @xtype panel-resource-tv
 */
MODx.panel.ResourceTV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        contentEl: 'modx-tab-tvs'
        ,id: 'modx-panel-resource-tv'
        ,title: _('settings_templvars')
        ,class_key: ''
        ,resource: ''
        ,autoLoad: this.autoload(config)
        ,templateField: 'modx-resource-template'
    });
    MODx.panel.ResourceTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ResourceTV,MODx.Panel,{
    /**
     * Autoloads the TV panel
     * @param {Object} config
     */
    autoload: function(config) {
        var t = Ext.getCmp(config.templateField);
        if (!t) { return false; }
        var template = config.template ? config.template : t.getValue();
        var a = {
            url: MODx.config.manager_url+'index.php?a='+MODx.action['resource/tvs']
            ,method: 'GET'
            ,params: {
               'a': MODx.action['resource/tvs']
               ,'class_key': config.class_key
               ,'template': template
               ,'resource': config.resource
            }
            ,scripts: true
        };
        return a;        	
    }
});
Ext.reg('modx-panel-resource-tv',MODx.panel.ResourceTV);