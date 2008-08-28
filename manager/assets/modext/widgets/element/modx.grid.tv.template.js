Ext.namespace('MODx','MODx.grid');
/**
 * Loads a grid of TVs assigned to the Template.
 * 
 * @class MODx.grid.TemplateVarTemplate
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-templatevartemplate
 */
MODx.grid.TemplateVarTemplate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('tv_tmpl_access')
        ,url: MODx.config.connectors_url+'element/tv/template.php'
        ,fields: ['id','templatename','description','rank','access','menu']
        ,baseParams: {
            action: 'getList'
            ,tv: config.tv
        }
        ,saveParams: {
            tv: config.tv
        }
        ,width: 800
        ,paging: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'templatename'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 350
            ,editor: { xtype: 'textfield' }
        },{
            header: _('has_access')
            ,dataIndex: 'access'
            ,width: 100
            ,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        },{
            header: _('rank')
            ,dataIndex: 'rank'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        }]
    });
    MODx.grid.TemplateVarTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.TemplateVarTemplate,MODx.grid.Grid);
Ext.reg('grid-tv-template',MODx.grid.TemplateVarTemplate);