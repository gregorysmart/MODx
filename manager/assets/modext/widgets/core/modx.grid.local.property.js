/**
 * @class MODx.grid.LocalProperty
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype grid-local-property
 */
MODx.grid.LocalProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        dynProperty: 'xtype'
        ,dynField: 'value'
        ,propertyRecord: [{name: 'name'},{name: 'value'}]
        ,data: []
    });
    MODx.grid.LocalProperty.superclass.constructor.call(this,config);
    this.removeListener('celldblclick',this.onCellDblClick,this);
    this.on('celldblclick',this.changeEditor,this);
    this.propRecord = Ext.data.Record.create(config.propertyRecord);
};
Ext.extend(MODx.grid.LocalProperty,MODx.grid.LocalGrid,{
    /**
     * Dynamically change the editor for the row via its xtype property.
     * @param {MODx.grid.SettingsGrid} g The grid object
     * @param {Integer} ri The row index
     * @param {Integer} ci The column index
     * @param {Ext.EventObject} e The event object that occurred
     */
    changeEditor: function(g,ri,ci,e) {
        var cm = this.getColumnModel();
        if (cm.getColumnId(ci) != this.config.dynField) {
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
        var xtype = this.config.dynProperty;
        if (r[xtype] == 'list') {
            var o = this.createCombo(r);
        } else {
            var z = {};
            z[xtype] = r[xtype] || 'textfield';
            var o = Ext.ComponentMgr.create(z);
        }
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
        var xtype = this.config.dynProperty;
        if (!r[xtype] || r[xtype] == 'combo-boolean') {
            f = MODx.grid.Grid.prototype.rendYesNo;
            return f(v == 1 ? true : false,md);
        } else if (r[xtype] === 'datefield') {
            f = Ext.util.Format.dateRenderer('Y-m-d');
            return f(v);
        } else if (r[xtype].substr(0,5) == 'combo' || r[xtype] == 'list' || r[xtype].substr(0,9) == 'modx-combo') {
            var cm = g.getColumnModel();
            var ed = cm.getCellEditor(ci,ri);
            if (!ed) {
                r.xtype = r.xtype || 'combo-boolean';
                var o = this.createCombo(r);
                ed = new Ext.grid.GridEditor(o);
                cm.setEditor(ci,ed);
            }
            f = MODx.combo.Renderer(ed.field);
            return f(v);
        }
        return v;
    }
    
    ,createCombo: function(p) {
        var obj;
        try {
            obj = Ext.ComponentMgr.create({ xtype: r.xtype });
        } catch(e) {
            try {
                var flds = p.options;
                var data = [];
                for (var i=0;i<flds.length;i=i+1) {
                    data.push([flds[i].name,flds[i].value]);
                }
                obj = MODx.load({
                    xtype: 'modx-combo'
                    ,store: new Ext.data.SimpleStore({
                        fields: ['d','v']
                        ,data: data
                    })
                    ,displayField: 'd'
                    ,valueField: 'v'
                    ,mode: 'local'
                    ,triggerAction: 'all'
                    ,editable: false
                    ,selectOnFocus: false
                });
            } catch (e2) {
                obj = Ext.ComponentMgr.create({ xtype: 'combo-boolean' });
            }
        }
        return obj;
    }
});
Ext.reg('grid-local-property',MODx.grid.LocalProperty);