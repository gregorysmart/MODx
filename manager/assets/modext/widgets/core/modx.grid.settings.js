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
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p style="padding: .7em 1em .3em;"><i>{description}</i></p>'
        )
    });
    if (!config.tbar) {
        config.tbar = [{
            text: _('setting_create')
            ,scope: this
            ,handler: { 
                xtype: 'window-setting-create'
                ,url: config.url || MODx.config.connectors_url+'system/settings.php'
                ,blankValues: true
            }
        }];
    }
    config.tbar.push('->',{
        xtype: 'combo-namespace'
        ,name: 'namespace'
        ,id: 'filter_namespace'
        ,emptyText: _('namespace_filter')
        ,allowBlank: true
        ,listeners: {
            'select': {fn: this.filterByNamespace, scope:this}
        }
    },'-',{
        xtype: 'textfield'
        ,name: 'filter_key'
        ,id: 'filter_key'
        ,emptyText: _('search_by_key')+'...'
        ,listeners: {
            'change': {fn: this.filterByKey, scope: this}
            ,'render': {fn: this._addEnterKeyHandler}
        }
    },{
        xtype: 'button'
        ,id: 'filter_clear'
        ,text: _('filter_clear')
        ,listeners: {
        	'click': {fn: this.clearFilter, scope: this}
        }
    });
    Ext.applyIf(config,{
        title: _('settings')
        ,url: MODx.config.connectors_url+'system/settings.php'
        ,baseParams: {
            action: 'getList'
        }
        ,fields: ['key','name','value','description','xtype','namespace','area','area_text','editedon','oldkey','menu']
        ,paging: true
        ,pageSize: 50
        ,autosave: true
        ,remoteSort: true
        ,primaryKey: 'key'
        ,viewConfig: { 
            forceFit: true
            ,autoFill: true
            ,showPreview: true
            ,enableRowBody: true
        }
        ,grouping: true
        ,groupBy: 'area_text'
        ,singleText: _('setting')
        ,pluralText: _('settings')
        ,plugins: this.exp
        ,columns: [this.exp,{
            header: _('name')
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
        },{
            header: _('area')
            ,dataIndex: 'area_text'
            ,width: 100
            ,sortable: true
            ,hidden: true
        },{
            header: _('last_modified')
            ,dataIndex: 'editedon'
            ,width: 100
            ,sortable: true
        }]
        ,collapseFirst: false
        ,tools: [{
            id: 'plus'
            ,qtip: _('expand_all')
            ,handler: this.expandAll
            ,scope: this
        },{
            id: 'minus'
            ,hidden: true
            ,qtip: _('collapse_all')
            ,handler: this.collapseAll
            ,scope: this
        }]
    });
    MODx.grid.SettingsGrid.superclass.constructor.call(this,config);
    this.removeListener('celldblclick',this.onCellDblClick,this);
    this.on('celldblclick',this.changeEditor,this);
};
MODx.grid.ExpandAllTool = 
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
     * Resets the filter to empty
     */
    ,clearFilter: function() {
    	this.getStore().baseParams = {
    		action: 'getList'
    	};
        Ext.getCmp('filter_namespace').setValue('');
        Ext.getCmp('filter_key').setValue('');
    	this.refresh();
    	this.getBottomToolbar().changePage(1);
    }
    
    /**
     * Filters the grid by the key column.
     */
    ,filterByKey: function(tf,newValue,oldValue) {
        this.getStore().baseParams.key = newValue;
        this.refresh();
        this.getBottomToolbar().changePage(1);
    }
    /**
     * Filters the grid by the namespace column
     */
    ,filterByNamespace: function(cb,rec,ri) {
        this.getStore().baseParams['namespace'] = rec.data['name'];
        this.refresh();
        this.getBottomToolbar().changePage(1);
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
     * Starts editing the specified for the specified row/column
     * @param {Number} rowIndex
     * @param {Number} colIndex
     */
    ,startEditing : function(row, col){
        this.stopEditing();
        if(this.colModel.isCellEditable(col, row)){
            this.view.ensureVisible(row, col, true);
            var r = this.store.getAt(row);
            var field = this.colModel.getDataIndex(col);
            var e = {
                grid: this,
                record: r,
                field: field,
                value: r.data[field],
                row: row,
                column: col,
                cancel:false
            };
            if(this.fireEvent("beforeedit", e) !== false && !e.cancel){
                this.editing = true;
                var ed = this.colModel.getCellEditor(col, row);
                if(!ed.rendered){
                    ed.render(this.view.getEditorParent(ed));
                }
                (function(){ // complex but required for focus issues in safari, ie and opera
                    ed.row = row;
                    ed.col = col;
                    ed.record = r;
                    ed.on("complete", this.onEditComplete, this);
                    ed.on("specialkey", this.selModel.onEditorKey, this.selModel);
                    this.activeEditor = ed;
                    var v = this.preEditValue(r, field);
                    ed.startEdit(this.view.getCell(row, col).firstChild, v);
                }).defer(50, this);
            }
        }
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
        var f;
        if (r.xtype == 'combo-boolean') {
            f = MODx.grid.Grid.prototype.rendYesNo;
            return f(v == 1 ? true : false,md);
        } else if (r.xtype === 'datefield') {
            f = Ext.util.Format.dateRenderer('Y-m-d');
            return f(v);
        } else if (r.xtype.substr(0,5) == 'combo') {
            var cm = g.getColumnModel();
            var ed = cm.getCellEditor(ci,ri);
            if (!ed) {
                var o = Ext.ComponentMgr.create({ xtype: r.xtype || 'textfield'});
                ed = new Ext.grid.GridEditor(o);
                cm.setEditor(ci,ed);
            }
            f = MODx.combo.Renderer(ed.field);
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
        title: _('setting_create')
        ,width: 450
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
            ,fieldLabel: _('name')
            ,name: 'name'
            ,allowBlank: false
        },{
            xtype: 'combo-xtype-spec'
            ,fieldLabel: _('xtype')
            ,description: _('xtype_desc')
        },{
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,value: 'core'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('area_lexicon_string')
            ,description: _('area_lexicon_string_msg')
            ,name: 'area'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('value')
            ,name: 'value'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,allowBlank: true
            ,width: 225
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


/**
 * Displays a xtype combobox
 * 
 * @class MODx.combo.xType
 * @extends Ext.form.ComboBox
 * @constructor
 * @xtype combo-xtype
 */
MODx.combo.xType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [[_('textfield'),'textfield'],[_('textarea'),'textarea'],[_('yesno'),'combo-boolean']]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,name: 'xtype'
        ,hiddenName: 'xtype'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,value: 'textfield'
    });
    MODx.combo.xType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.xType,Ext.form.ComboBox);
Ext.reg('combo-xtype-spec',MODx.combo.xType);



/**
 * A window for updating settings
 * 
 * @class MODx.window.UpdateSetting
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype window-setting-update
 */
MODx.window.UpdateSetting = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('setting_update')
        ,width: 450
        ,url: config.grid.config.url
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'fk'
            ,value: config.fk || 0
        },{
            xtype: 'statictextfield'
            ,fieldLabel: _('key')
            ,name: 'key'
            ,submitValue: true
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,allowBlank: false
        },{
            xtype: 'combo-xtype-spec'
            ,name: 'xtype'
            ,hiddenName: 'xtype'
            ,fieldLabel: _('xtype')
            ,description: _('xtype_desc')
        },{
            xtype: 'combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,value: 'core'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('area_lexicon_string')
            ,description: _('area_lexicon_string_msg')
            ,name: 'area'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('value')
            ,name: 'value'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,allowBlank: true
            ,width: 225
        }]
    });
    MODx.window.UpdateSetting.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateSetting,MODx.Window);
Ext.reg('window-setting-update',MODx.window.UpdateSetting);
