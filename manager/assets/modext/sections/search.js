Ext.namespace('MODx','MODx.panel','MODx.grid');
Ext.onReady(function() {
    MODx.load({ xtype: 'modx-search' });
});

/**
 * Loads the Search page
 * 
 * @class MODx.Search
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-search
 */
MODx.Search = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'grid-search'
            ,renderTo: 'search_grid'
        },{
            xtype: 'panel-search'
            ,id: 'panel-search'
        }]
    });
	MODx.Search.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Search,MODx.Component);
Ext.reg('modx-search',MODx.Search);

/**
 * Loads the search filter panel
 * 
 * @class MODx.panel.Search
 * @extends MODx.FormPanel
 * @constructor
 * @param {Object} config An object of options.
 * @xtype panel-search
 */
MODx.panel.Search = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	   title: _('search_criteria')
	   ,renderTo: 'search_panel'
	   ,width: 500
	   ,items: this.getFields()
	});
	MODx.panel.Search.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Search,MODx.FormPanel,{
    filters: {}
	
	,getFields: function() {
		var lsr = {
            'change': {fn:this.filter,scope: this}
            ,'render': {fn:this._addEnterKeyHandler}
        };
        var csr = {'check': {fn:this.filter, scope:this}};
        return [{
            xtype: 'textfield'
            ,name: 'id'
            ,fieldLabel: _('id')
            ,listeners: lsr
        },{
            xtype: 'textfield'
            ,name: 'pagetitle'
            ,fieldLabel: _('pagetitle')
            ,listeners: lsr
        },{
            xtype: 'textfield'
            ,name: 'longtitle'
            ,fieldLabel: _('long_title')
            ,listeners: lsr
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,fieldLabel: _('content')
            ,width: 300
            ,grow: true
            ,listeners: lsr
        },{
            xtype: 'checkbox'
            ,name: 'published'
            ,fieldLabel: _('published')
            ,listeners: csr
        },{
            xtype: 'checkbox'
            ,name: 'unpublished'
            ,fieldLabel: _('unpublished')
            ,listeners: csr
        },{
            xtype: 'checkbox'
            ,name: 'deleted'
            ,fieldLabel: _('deleted')
            ,listeners: csr
        },{
            xtype: 'checkbox'
            ,name: 'undeleted'
            ,fieldLabel: _('undeleted')
            ,listeners: csr
        }];
	}
	
	,filter: function(tf,newValue,oldValue) {
        var p = this.getForm().getValues();
        p.start = 0;
        p.limit = 20;
        Ext.getCmp('grid-search').getStore().load({
            params: p
            ,scope: this
        });
    }
	    
    ,_addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change'); 
        },this);
    }
});
Ext.reg('panel-search',MODx.panel.Search);

/**
 * Loads the search result grid
 * 
 * @class MODx.grid.Search
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-search
 */
MODx.grid.Search = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	    title: _('search_results')
        ,id: 'grid-search'
        ,url: MODx.config.connectors_url+'resource/document.php'
        ,baseParams: {
            action: 'search'
        }
        ,fields: ['id','pagetitle','description','published','deleted','menu']
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 20
            ,sortable: true
        },{
            header: _('pagetitle')
            ,dataIndex: 'pagetitle'
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
        },{
            header: _('published')
            ,dataIndex: 'published'
            ,width: 30
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
            ,editable: false
        },{
            header: _('deleted')
            ,dataIndex: 'deleted'
            ,width: 30
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
            ,editable: false
        }]
	});
	MODx.grid.Search.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Search,MODx.grid.Grid);
Ext.reg('grid-search',MODx.grid.Search);