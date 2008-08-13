Ext.namespace('MODx','MODx.grid');
/**
 * A grid created that allows for dynamic editors for each column
 * based upon the data's xtype property, as well as key filtering. 
 * 
 * @class MODx.grid.SettingsGrid
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-grid-settings
 */
MODx.grid.SettingsGrid = function(config) {
    config = config || {};
    var exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p style="padding: .7em 1em .3em;"><i>{description}</i></p>'
        )
    });
    if (!config.tbar) {
        config.tbar = [{
            text: _('create_new')
            ,scope: this
            ,handler: { 
                xtype: 'window-setting-create'
                ,url: MODx.config.connectors_url+'system/settings.php'
            }
        }];
    }
    config.tbar.push('-',{
        xtype: 'textfield'
        ,name: 'filter_key'
        ,id: 'filter_key'
        ,emptyText: _('filter_by_key')
        ,listeners: {
            'change': {fn: this.filterByKey, scope: this}
            ,'render': {fn: this._addEnterKeyHandler}
        }
    });
    Ext.applyIf(config,{
        title: _('settings')
        ,url: MODx.config.connectors_url+'system/settings.php'
        ,baseParams: {
            action: 'getList'
        }
        ,fields: ['key','name','value','description','xtype','oldkey','menu']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,primaryKey: 'key'
        ,viewConfig: { 
            forceFit: true
            ,autoFill: true
            ,showPreview: true
            ,enableRowBody: true
        }
        ,plugins: exp
        ,columns: [exp,{
            header: _('setting')
            ,dataIndex: 'name'
            ,width: 250
            ,editor: { xtype: 'textfield' }
        },{
            header: _('value')
            ,id: 'value'
            ,dataIndex: 'value'
            ,width: 150
            ,renderer: this.renderDynField.createDelegate(this,[this],true)
        },{
            header: _('key')
            ,dataIndex: 'key'
            ,width: 100
            ,sortable: true
        }]
    });
    MODx.grid.SettingsGrid.superclass.constructor.call(this,config);
    this.removeListener('celldblclick',this.onCellDblClick,this);
    this.on('celldblclick',this.changeEditor,this);
};
Ext.extend(MODx.grid.SettingsGrid,MODx.grid.Grid,{
    /**
     * Adds an enter key handler to the object.
     */
    _addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change'); 
        },this);
    }
    
    /**
     * Filters the grid by the key column.
     */
    ,filterByKey: function(tf,newValue,oldValue) {
        this.getStore().load({
            params: {
                key: newValue
                ,start: 0
                ,limit: 20
            }
            ,scope: this
            ,callback: this.refresh
        });
    }
    /**
     * Dynamically change the editor for the row via its xtype property.
     * @param {MODx.grid.SettingsGrid} g The grid object
     * @param {Integer} ri The row index
     * @param {Integer} ci The column index
     * @param {Ext.EventObject} e The event object that occurred
     */
    ,changeEditor: function(g,ri,ci,e) {
        var cm = this.getColumnModel();
        if (cm.getColumnId(ci) != 'value') {
            this.onCellDblClick(g,ri,ci,e);
        } else {
            e.preventDefault();
            var r = this.getStore().getAt(ri).data;
            this.initEditor(cm,ci,ri,r);
            this.startEditing(ri,ci);
        }
    }
    
    /**
     * Initializes the editor for the cell
     * @param {Ext.grid.ColumnModel} cm The column model for the grid
     * @param {Integer} ri The row index
     * @param {Integer} ci The column index
     * @param {Object} r The data record for the cell
     */
    ,initEditor: function(cm,ci,ri,r) {
        cm.setEditable(ci,true);
        var o = Ext.ComponentMgr.create({ xtype: r.xtype || 'textfield'});
        var ed = new Ext.grid.GridEditor(o);
        cm.setEditor(ci,ed);
    }
    /**
     * A custom renderer that renders the custom xtype editor
     * @param {String} v The raw value
     * @param {Object} md The metadata for the cell
     * @param {Object} rec The store data record
     * @param {Integer} ri The row index
     * @param {Integer} ci The column index
     * @param {Ext.data.Store} s The store for the grid
     * @param {MODx.grid.SettingsGrid} g The grid object 
     */
    ,renderDynField: function(v,md,rec,ri,ci,s,g) {
        var r = s.getAt(ri).data;
        if (r.xtype == 'combo-boolean') {
            var f = MODx.grid.Grid.prototype.rendYesNo;
            return f(v == 1 ? true : false,md);
        } else if (r.xtype === 'datefield') {
            var f = Ext.util.Format.dateRenderer('Y-m-d');
            return f(v);
        } else if (r.xtype.substr(0,5) == 'combo') {
            var cm = g.getColumnModel();
            var ed = cm.getCellEditor(ci,ri);
            if (!ed) {
                var o = Ext.ComponentMgr.create({ xtype: r.xtype || 'textfield'});
                ed = new Ext.grid.GridEditor(o);
                cm.setEditor(ci,ed);
            }
            var f = MODx.combo.Renderer(ed.field);
            return f(v);
        }
        return v;
    }
});
Ext.reg('modx-grid-settings',MODx.grid.SettingsGrid);

/**
 * A window for creating settings
 * 
 * @class MODx.window.CreateSetting
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype window-setting-create
 */
MODx.window.CreateSetting = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('create_new')
        ,width: 300
        ,url: config.url
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'fk'
            ,value: config.fk || 0
        },{
            xtype: 'textfield'
            ,fieldLabel: _('key')
            ,name: 'key'
            ,maxLength: 100
        },{
            xtype: 'textfield'
            ,fieldLabel: _('xtype')
            ,description: _('xtype_desc')
            ,name: 'xtype'
            ,maxLength: 100
            ,value: 'textfield'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('value')
            ,name: 'value'
        }]
    });
    MODx.window.CreateSetting.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateSetting,MODx.Window);
Ext.reg('window-setting-create',MODx.window.CreateSetting);


/**
 * Fixes problem with PagingToolbar and loading different renderers for each row
 */
Ext.override(Ext.PagingToolbar,{
    doLoad : function(start){
        var o = {}, pn = this.paramNames;
        o[pn.start] = start;
        o[pn.limit] = this.pageSize;
        this.store.load({
            params:o
            ,scope: this
            ,callback: function() { this.store.reload(); }
        });
    }
});
