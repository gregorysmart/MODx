
/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */
Ext.tree.ColumnTree = Ext.extend(Ext.tree.TreePanel, {
    lines:false,
    borderWidth: Ext.isBorderBox ? 0 : 2, // the combined left/right border for each cell
    cls:'x-column-tree',
    
    onRender : function(){
        Ext.tree.ColumnTree.superclass.onRender.apply(this, arguments);
        this.headers = this.body.createChild(
            {cls:'x-tree-headers'},this.innerCt.dom);

        var cols = this.columns, c;
        var totalWidth = 0;

        for(var i = 0, len = cols.length; i < len; i++){
             c = cols[i];
             totalWidth += c.width;
             this.headers.createChild({
                 cls:'x-tree-hd ' + (c.cls?c.cls+'-hd':''),
                 cn: {
                     cls:'x-tree-hd-text',
                     html: c.header
                 },
                 style:'width:'+(c.width-this.borderWidth)+'px;'
             });
        }
        this.headers.createChild({cls:'x-clear'});
        // prevent floats from wrapping when clipped
        this.headers.setWidth(totalWidth);
        this.innerCt.setWidth(totalWidth);
    }
});
Ext.tree.ColumnNodeUI = Ext.extend(Ext.tree.TreeNodeUI, {
    focus: Ext.emptyFn, // prevent odd scrolling behavior

    renderElements : function(n, a, targetNode, bulkRender){
        this.indentMarkup = n.parentNode ? n.parentNode.ui.getChildIndent() : '';

        var t = n.getOwnerTree();
        var cols = t.columns;
        var bw = t.borderWidth;
        var c = cols[0];
        var buf = [
             '<li class="x-tree-node"><div ext:tree-node-id="',n.id,'" class="x-tree-node-el x-tree-node-leaf ', a.cls,'">',
                '<div class="x-tree-col" style="width:',c.width-bw,'px;">',
                    '<span class="x-tree-node-indent">',this.indentMarkup,"</span>",
                    '<img src="', this.emptyIcon, '" class="x-tree-ec-icon x-tree-elbow">',
                    '<img src="', a.icon || this.emptyIcon, '" class="x-tree-node-icon',(a.icon ? " x-tree-node-inline-icon" : ""),(a.iconCls ? " "+a.iconCls : ""),'" unselectable="on">',
                    '<a hidefocus="on" class="x-tree-node-anchor" href="',a.href ? a.href : "#",'" tabIndex="1" ',
                    a.hrefTarget ? ' target="'+a.hrefTarget+'"' : "", '>',
                    '<span unselectable="on">', n.text || (c.renderer ? c.renderer(a[c.dataIndex], n, a) : a[c.dataIndex]),"</span></a>",
                "</div>"];
         for(var i = 1, len = cols.length; i < len; i++){
             c = cols[i];

             buf.push('<div class="x-tree-col ',(c.cls?c.cls:''),'" style="width:',c.width-bw,'px;">',
                        '<div class="x-tree-col-text">',(c.renderer ? c.renderer(a[c.dataIndex], n, a) : a[c.dataIndex]),"</div>",
                      "</div>");
         }
         buf.push(
            '<div class="x-clear"></div></div>',
            '<ul class="x-tree-node-ct" style="display:none;"></ul>',
            "</li>");

        if(bulkRender !== true && n.nextSibling && n.nextSibling.ui.getEl()){
            this.wrap = Ext.DomHelper.insertHtml("beforeBegin",
                                n.nextSibling.ui.getEl(), buf.join(""));
        }else{
            this.wrap = Ext.DomHelper.insertHtml("beforeEnd", targetNode, buf.join(""));
        }

        this.elNode = this.wrap.childNodes[0];
        this.ctNode = this.wrap.childNodes[1];
        var cs = this.elNode.firstChild.childNodes;
        this.indentNode = cs[0];
        this.ecNode = cs[1];
        this.iconNode = cs[2];
        this.anchor = cs[3];
        this.textNode = cs[3].firstChild;
    }
});

/**
 * @class MODx.tree.ColumnTree
 * @extends Ext.tree.ColumnTree
 * @param {Object} config An object of configuration properties
 * @xtype tree-column
 */
MODx.tree.ColumnTree = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        
        rootVisible: false
        ,autoScroll: true
        ,autoHeight: true
        ,root: new Ext.tree.AsyncTreeNode({
            text: config.rootText || ''
        })
        ,loader: new Ext.tree.TreeLoader({
            dataUrl: config.url
            ,baseParams: config.baseParams || {}
            ,uiProviders: {
                'col': Ext.tree.ColumnNodeUI
            }
            ,listeners: config.loaderListeners || {
               'beforeload': {fn:function(treeLoader, node) {
                    if (node.attributes.class_key) {
                        var bp = {};
                        Ext.apply(bp,this.config.baseParams);
                        Ext.apply(bp,node.attributes);
                        bp.loader = null; bp.uiProvider = null;
                        this.getLoader().baseParams = bp;
                    }
                },scope:this}
            }
        })
        ,tbar: this._getToolbar()
    });
    MODx.tree.ColumnTree.superclass.constructor.call(this,config);
    this.on('contextmenu',this._showContextMenu,this);
    this.cm = new Ext.menu.Menu(Ext.id(),{});
    this.config = config;
};
Ext.extend(MODx.tree.ColumnTree,Ext.tree.ColumnTree,{
    windows: {}
    
    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} node The 
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(node,e) {
        node.select();
        this.cm.activeNode = node;
        this.cm.record = node.attributes;
        this.cm.record.id = node.attributes.pk;
        this.cm.removeAll();
        if (node.attributes.menu) {
            this._addContextMenuItem(node.attributes.menu);
            this.cm.show(node.ui.getEl(),'t?');
        }
    }
    /**
     * Add context menu items to the tree.
     * @param {Object, Array} items Either an Object config or array of Object configs.  
     */
    ,_addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            var o = a[i];
            
            if (o == '-') {
                this.cm.add('-');
                continue;
            }
            var h = Ext.emptyFn;
            if (o.handler) {
                h = eval(o.handler);
                if (h && typeof(h) == 'object' && h.xtype) {
                    h = this.loadWindow.createDelegate(this,[h],true);
                }
            } else {
                h = function(itm,e) {
                    var op = itm.o;    
                    if (op.confirm) {
                        Ext.Msg.confirm('',op.confirm,function(e) {
                            if (e == 'yes') {
                                var a = Ext.urlEncode(op.params || {action: op.action});
                                var s = 'index.php?&a='+RM.request.a;
                                location.href = s;
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(op.params);
                        var s = 'index.php?&a='+RM.request.a;
                        location.href = s;
                    }
                };
            }
            this.cm.add({
                id: o.id
                ,text: o.text
                ,scope: this
                ,options: o
                ,handler: h
            });
        }
    }
    
    
    ,loadWindow: function(btn,e,win) {
        var r = win.record || this.cm.record;
        if (!this.windows[win.xtype]) {  
            Ext.applyIf(win,{
                record: win.blankValues ? {} : r
                ,grid: this
                ,listeners: {
                    'success': {fn:win.success || this.refresh,scope:win.scope || this}
                }
            });
            this.windows[win.xtype] = Ext.ComponentMgr.create(win);
        }
        if (this.windows[win.xtype].setValues && win.blankValues !== true) {
            this.windows[win.xtype].setValues(r);
        }
        this.windows[win.xtype].show(e.target);
    }
    
    
    ,refresh: function(func,scope,args) {
        this.getLoader().baseParams = this.config.baseParams;
        this.getRootNode().reload();
        this.getRootNode().expand(null,null);
        if (func) {
            scope = scope || this;
            args = args || [];
            this.getRootNode().on('load',function() { Ext.callback(func,scope,args); },scope);
        }
        return true;
    }
    
    ,_getToolbar: function() {
        var iu = MODx.config.template_url+'images/icons/';
        return [{
            icon: iu+'sort.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('tree_refresh')}
            ,handler: this.refresh
            ,scope: this
        }];
    }
    
    /**
     * Render the row to a colored Yes/No value.
     * 
     * @access public
     * @param {Object} d The data record
     * @param {Object} c The dom properties
     * @return {String} The value to return
     */
    ,rendYesNo: function(d,c,a) {
        switch(d) {
            case '':
                return '-';
            case 0:
                c.css = 'red';
                return _('no');
            case 1:
                c.css = 'green';
                return _('yes');
        }
    }
});
Ext.reg('tree-column',MODx.tree.ColumnTree);