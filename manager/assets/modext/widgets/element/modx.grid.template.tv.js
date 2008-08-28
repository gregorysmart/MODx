Ext.namespace('MODx','MODx.grid');
/**
 * Loads a grid of TVs assigned to the Template.
 * 
 * @class MODx.grid.TemplateTV
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-template-tv
 */
MODx.grid.TemplateTV = function(config) {
    config = config || {};
	Ext.applyIf(config,{
        title: _('template_assignedtv_tab')
        ,url: MODx.config.connectors_url+'element/template/tv.php'
		,fields: ['id','name','description','rank','access','menu']
        ,baseParams: {
            action: 'getList'
            ,template: config.template
        }
        ,saveParams: {
            template: config.template
        }
		,width: 800
        ,paging: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 350
            ,editor: { xtype: 'textfield' }
        },{
            header: _('access')
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
	MODx.grid.TemplateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.TemplateTV,MODx.grid.Grid);
Ext.reg('grid-template-tv',MODx.grid.TemplateTV);