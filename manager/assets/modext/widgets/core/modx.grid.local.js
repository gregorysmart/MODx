Ext.namespace('MODx.grid');
/**
 * An abstract class for Ext Grids with local stores in MODx. 
 * 
 * @class MODx.grid.LocalGrid
 * @extends Ext.grid.EditorGridPanel
 * @constructor
 * @param {Object} config An object of config options.
 */
MODx.grid.LocalGrid = function(config) {
    config = config || {};
    
    if (config.grouping) {
        Ext.applyIf(config,{
          view: new Ext.grid.GroupingView({ 
            forceFit: true 
            ,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "'
                +(config.pluralText || _('records')) + '" : "'
                +(config.singleText || _('record'))+'"]})' 
          })
        });
    }
    if (config.tbar) {
        for (var i = 0;i<config.tbar.length;i++) {
            var itm = config.tbar[i];
            if (itm.handler && typeof(itm.handler) == 'object' && itm.handler.xtype) {
                itm.handler = this.loadWindow.createDelegate(this,[itm.handler],true);
            }
            if (!itm.scope) { itm.scope = this; }
        }
    }
    Ext.applyIf(config,{
        title: ''
        ,store: new Ext.data.SimpleStore({
            fields: config.fields
            ,data: config.data || []
        })
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:false})
        ,loadMask: true
        ,loadMask: true
        ,autoHeight: true
        ,collapsible: true
        ,stripeRows: true
        ,viewConfig: {
            forceFit: true
            ,enableRowBody: true
            ,autoFill: true
            ,showPreview: true
        }
        ,tools: [{ 
            id: 'help'
            ,qtip: _('help')
            ,handler: this.help
            ,scope: this
        }]
    });
    this.menu = new Ext.menu.Menu({ defaultAlign: 'tl-b?' });
    MODx.grid.LocalGrid.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        beforeRemoveRow: true
        ,afterRemoveRow: true
    });
    this.on('rowcontextmenu',this._showMenu,this);
};
Ext.extend(MODx.grid.LocalGrid,Ext.grid.EditorGridPanel,{
    windows: {}
    
    ,loadWindow: function(btn,e,win,or) {
        var r = this.menu.record;
        if (!this.windows[win.xtype]) {  
            Ext.applyIf(win,{
                scope: this
                ,success: this.refresh
                ,record: win.blankValues ? {} : r
            });
            if (or) {
                Ext.apply(win,or);
            }
            this.windows[win.xtype] = Ext.ComponentMgr.create(win);
        }
        if (this.windows[win.xtype].setValues && win.blankValues !== true && r != undefined) {
            this.windows[win.xtype].setValues(r);
        }
        this.windows[win.xtype].show(e.target);
    }
    
    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();
        var m = this.getMenu();
        if (m) {
            this.addContextMenuItem(m);
            this.menu.show(e.target);
        }
    }
    
    ,getMenu: function() {
        return this.menu.record.menu;
    }
    /**
     * Adds menu items to the grid context menu
     * @param {Object} items
     */
    ,addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            var options = a[i];
            
            if (options == '-') {
                this.menu.add('-');
                continue;
            }
            var h = Ext.emptyFn;
            if (options.handler) {
                h = eval(options.handler);
                if (h && typeof(h) == 'object' && h.xtype) {
                    h = this.loadWindow.createDelegate(this,[h],true);
                }
            } else {
                h = function(itm,e) {
                    var o = itm.options;
                    var id = this.menu.record.id;
                    var w = Ext.get('modx_content');
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e == 'yes') {
                                var a = Ext.urlEncode(o.params || {action: o.action});
                                var s = 'index.php?id='+id+'&'+a;
                                if (w === null) {
                                    location.href = s;
                                } else { w.dom.src = s; }
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params || {action: o.action});
                        var s = 'index.php?id='+id+'&'+a;
                        if (w === null) {
                            location.href = s;
                        } else { w.dom.src = s; }
                    }
                };
            }
            this.menu.add({
                id: options.id || Ext.id()
                ,text: options.text
                ,scope: this
                ,options: options
                ,handler: h
            });
        }
    }
    
    
    ,remove: function(config) {
        var r = this.getSelectionModel().getSelected();
        if (this.fireEvent('beforeRemoveRow',r)) {
            Ext.Msg.confirm(config.title || '',config.text || '',function(e) {
                if (e == 'yes') {
                    this.getStore().remove(r);
                    this.fireEvent('afterRemoveRow',r);
                }
            },this);
        }
    }
    
    /**
     * Encodes modified record data into JSON array for form sending
     * 
     * @access public
     */
    ,encode: function() {
        var s = this.getStore();
        var ct = s.getCount();
        var rs = this.config.encodeByPk ? {} : [];
        var r;
        for (var j=0;j<ct;j++) {
        	r = s.getAt(j).data;
        	if (this.config.encodeAssoc) {
        	   rs[r[this.config.encodeByPk || 'id']] = r;
        	} else {
        	   rs.push(r);
        	}
        }
        
        return Ext.encode(rs);
    }
});
Ext.reg('grid-local',MODx.grid.LocalGrid);