Ext.namespace('MODx.tree');
/**
 * Generates the Tree in Ext. All modTree classes extend this base class.
 * 
 * @class MODx.tree.Tree
 * @extends Ext.tree.TreePanel
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-tree
 */
MODx.tree.Tree = function(config) {
	config = config || {};    
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    Ext.applyIf(config,{
        baseParams: {}
        ,action: 'getNodes'
        ,loaderConfig: {}
    });
    if (config.action) {
        config.baseParams.action = config.action;
    }
    config.loaderConfig.dataUrl = config.url;
    config.loaderConfig.baseParams = config.baseParams;
    Ext.applyIf(config.loaderConfig,{
        preloadChildren: true
        ,clearOnLoad: true
    });
        
    this.config = config;
    if (this.config.url) {
    	var tl = new Ext.tree.TreeLoader(config.loaderConfig);
    	tl.on('beforeload',function(loader,node) {
    		tl.dataUrl = this.config.url+'?action='+this.config.action+'&id='+node.attributes.id;
            if (node.attributes.type) {
                tl.dataUrl += '&type='+node.attributes.type;
            }
    	},this);
        var root = {
            nodeType: 'async'
            ,text: config.root_name || ''
            ,draggable: false
            ,id: config.root_id || 'root'
        };
    } else {        
        var tl = new Ext.tree.TreeLoader({
            preloadChildren: true
            ,baseAttrs: {
                uiProvider: MODx.tree.CheckboxNodeUI
            }
        });
        var root = new Ext.tree.TreeNode({
            text: this.config.rootName || ''
            ,draggable: false
            ,id: this.config.rootId || 'root'
            ,children: this.config.data || []
        });
    }
	Ext.applyIf(config,{
        useArrows: true
        ,autoScroll: true
        ,animate: true
        ,enableDD: true
        ,enableDrop: true
		,ddAppendOnly: false
        ,containerScroll: true
        ,collapsible: true
        ,border: false
		,autoHeight: true
		,rootVisible: true
		,loader: tl
		,hideBorders: true
		,bodyBorder: false
        ,cls: 'modx-tree'
        ,root: root
	});
	if (config.remoteToolbar === true && (config.tbar === undefined || config.tbar === null)) {
		Ext.Ajax.request({
			url: config.url
			,params: {
                action: 'getToolbar'
            }
            ,success:function(r) {
                r = Ext.decode(r.responseText);
                var itms = this._formatToolbar(r.results);
                var tb = this.getTopToolbar();
                var l = r.results;
                for (var i=0;i<itms.length;i++) {
                    tb.add(itms[i]);
                }
                tb.doLayout();
            }
            ,scope:this
        });
        config.tbar = {bodyStyle: 'padding: 0'};
	} else {
		var tb = this.getToolbar();
        if (config.tbar && config.useDefaultToolbar) {
            tb.push('-');
            for (var i=0;i<config.tbar.length;i++) {
                tb.push(config.tbar[i]);
            }
        } else if (config.tbar) {
            tb = config.tbar;
        }
        Ext.apply(config,{tbar: tb});
	}
    this.setup(config);
    this.config = config;
};
Ext.extend(MODx.tree.Tree,Ext.tree.TreePanel,{
	menu: null
	,options: {}
    ,disableHref: false
	
	/**
	 * Sets up the tree and initializes it with the specified options.
	 * @param {Object} options
	 */
	,setup: function(config) {
	    MODx.tree.Tree.superclass.constructor.call(this,config);
	    this.cm = new Ext.menu.Menu();
	    this.on('contextmenu',this._showContextMenu,this);
	    this.on('beforenodedrop',this._handleDrop,this);
	    this.on('nodedragover',this._handleDrop,this);
	    this.on('nodedrop',this._handleDrag,this);
	    this.on('click',this._saveState,this);
        this.on('contextmenu',this._saveState,this);
        this.on('click',this._handleClick,this);
	    
	    this.treestate_id = this.config.id || Ext.id();
	    this.on('load',this._initExpand,this,{single: true});
        this.root.expand();
	    
        this.on('render',function() {
            var tl = this.getLoader();
            Ext.apply(tl,{fullMask : new Ext.LoadMask(this.getEl(),{msg:_('loading')}) });
            tl.fullMask.removeMask=false;
            tl.on({
                'load' : function(){this.fullMask.hide();}
                ,'loadexception' : function(){this.fullMask.hide();}
                ,'beforeload' : function(){this.fullMask.show();}
                ,scope : tl
            });
        },this);
	}
	
	/**
	 * Expand the tree upon initialization.
	 */
	,_initExpand: function() {
		var treeState = Ext.state.Manager.get(this.treestate_id);
		if (treeState === undefined && this.root) {
			this.root.expand();
			if (this.root.firstChild && this.config.expandFirst) {
				this.root.firstChild.select();
				this.root.firstChild.expand();
			}
		} else { this.expandPath(treeState); }		
	}
	
	/**
	 * Add context menu items to the tree.
	 * @param {Object, Array} items Either an Object config or array of Object configs.  
	 */
	,addContextMenuItem: function(items) {
		var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            a[i].scope = this;
            this.cm.add(a[i]);
        }
	}
	
    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} node The 
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(node,e) {
        node.select();
        this.cm.activeNode = node;
        var nar = node.id.split('_');
        
        this.cm.removeAll();
        if (node.attributes.menu && node.attributes.menu.items) {
            this.addContextMenuItem(node.attributes.menu.items);
            this.cm.show(node.ui.getEl(),'t?');
        }
        e.stopEvent();
    }
    
	/**
	 * Checks to see if a node exists in a tree node's children.
	 * @param {Object} t The parent node.
	 * @param {Object} n The node to find.
	 * @return {Boolean} True if the node exists in the parent's children. 
	 */
	,hasNode: function(t, n) {
        return (t.findChild('id', n.id)) || (t.leaf === true && t.parentNode.findChild('id', n.id));
    }
	
	/**
	 * Refreshes the tree and runs an optional func.
	 * @param {Function} func The function to run.
	 * @param {Object} scope The scope to run the function in.
	 * @param {Array} args An array of arguments to run with.
	 * @return {Boolean} True if successful.
	 */
	,refresh: function(func,scope,args) {
		var treeState = Ext.state.Manager.get(this.treestate_id);
		this.root.reload();
		treeState === undefined
			? this.root.expand(null,null)
			: this.expandPath(treeState,null);
		if (func) {
			scope = scope || this;
			args = args || [];
			this.root.on('load',function() { Ext.callback(func,scope,args); },scope);
		}
		return true;
	}
    
    ,removeChildren: function(node) {
        while(node.firstChild){
             var c = node.firstChild;
             node.removeChild(c);
             c.destroy();
        }
    }
    ,loadRemoteData: function(data) {
        this.removeChildren(this.getRootNode());
        for (var c in data) {
            if (typeof data[c] === 'object') {
                this.getRootNode().appendChild(data[c]);
            }
        }
    }
	
	,reloadNode: function(n) {
        this.getLoader().load(n);
        n.expand();
	}
    
    /**
     * Abstracted remove function
     */
    ,remove: function(text,substr,split) {
        var node = this.cm.activeNode;
        var id = this._extractId(node.id,substr,split);
        var p = { action: 'remove' };
        var pk = this.config.primaryKey || 'id';
        p[pk] = id;
        MODx.msg.confirm({
            title: _('warning')
            ,text: _(text)
            ,url: this.config.url
            ,params: p
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        }); 
    }
    
    ,_extractId: function(id,substr,split) {
        substr = substr || false;
        split = split || false;
        if (substr !== false) {
            id = node.id.substr(substr);
        }
        if (split !== false) {
            id = node.id.split('_');
            id = id[split];
        }
        return id;
    }
	
	/**
	 * Expand the tree and all children.
	 */
	,expandNodes: function() {
		if (this.root) {
            this.root.expand();
            this.root.expandChildNodes();
        }
	}
	
	/**
	 * Completely collapse the tree.
	 */
	,collapseNodes: function() {
		if (this.root) {
            this.root.collapseChildNodes();
            this.root.collapse();
        }
	}
	
	/**
	 * Save the state of the tree's open children for a certain node.
	 * @param {Ext.tree.TreeNode} n The most recent clicked-on node.
	 */
	,_saveState: function(n) {
		Ext.state.Manager.set(this.treestate_id,n.getPath());
	}
    
    /**
     * Handles tree clicks
     * @param {Object} n The node clicked 
     */
	,_handleClick: function (n,e) {
        e.stopEvent();
        
        if (this.disableHref) return false;
        if (e.ctrlKey) return false;
        if (n.attributes.href && n.attributes.href !== '') {
            location.href = n.attributes.href;
        }
    }
    
    
    ,encode: function(node) {
        if (!node) { node = this.getRootNode(); }
        var _encode = function(node) {
            var resultNode = {};
            var kids = node.childNodes;
            for (var i = 0;i < kids.length;i=i+1) {
                var n = kids[i];
                resultNode[n.id] = {
                    id: n.id
                    ,checked: n.ui.isChecked()
                    ,type: n.attributes.type || ''
                    ,data: n.attributes.data || {}
                    ,children: _encode(n)
                };
            }
            return resultNode;
        };
        var nodes = _encode(node);
        return Ext.encode(nodes);
    }
    
    
    
	/**
	 * Handles all drag events into the tree.
	 * @param {Object} dropEvent The node dropped on the parent node.
	 */
	,_handleDrag: function(dropEvent) {		
		function simplifyNodes(node) {
			var resultNode = {};
			var kids = node.childNodes;
			var len = kids.length;
			for (var i = 0; i < len; i++) {
				resultNode[kids[i].id] = simplifyNodes(kids[i]);
			}
			return resultNode;
		}
		
		var encNodes = Ext.encode(simplifyNodes(dropEvent.tree.root));
		MODx.Ajax.request({
			url: this.config.url
			,params: {
				data: encodeURIComponent(encNodes)
				,action: 'sort'
			}
			,listeners: {
				'success': {fn:function(r) {
    				this.reloadNode(dropEvent.target.parentNode);
				},scope:this}
				,'failure': {fn:function(r) {
                    MODx.form.Handler.errorJSON(r);
                    return false;
				},scope:this}
			}
		});
	}
	
	/**
	 * Abstract definition to handle drop events.
	 */
	,_handleDrop: function() { }
	
	
	/**
	 * Semi unique ids across edits
	 * @param {String} prefix Prefix the guid.
	 * @return {String} The newly generated guid.
	 */
	,_guid: function(prefix){
        return prefix+(new Date().getTime());
    }
	
	/**
	 * Redirects the page or the content frame to the correct location.
	 * @param {String} loc The URL to direct to.
	 */
	,redirect: function(loc) {
        location.href = loc;
	}
	
    ,loadAction: function(p) {
        var id = this.cm.activeNode.id.split('_'); id = id[1];
        var u = 'index.php?id='+id+'&'+p;
        location.href = u;
    }
	/**
	 * Loads the default toolbar for the tree.
	 * @access private
	 * @see Ext.Toolbar
	 */
	,_loadToolbar: function() {}
	
	/**
	 * Refreshes a given tree node.
	 * @access public
	 * @param {String} id The ID of the node
	 * @param {Boolean} self If true, will refresh self rather than parent.
	 */
	,refreshNode: function(id,self) {
		var node = this.getNodeById(id);
		if (node) {
            var n = self ? node : node.parentNode;
            var l = this.getLoader().load(n);
            n.expand();
		}
	}
	
	/**
	 * Refreshes selected active node
	 * @access public
	 */
	,refreshActiveNode: function() {
        this.getLoader().load(this.cm.activeNode);
        this.cm.activeNode.expand();
    }
    
    /**
     * Refreshes selected active node's parent
     * @access public
     */
    ,refreshParentNode: function() {
        this.getLoader().load(this.cm.activeNode.parentNode);
        this.cm.activeNode.parentNode.expand();
    }
    
    /**
     * Removes specified node
     * @param {String} id The node's ID
     */
    ,removeNode: function(id) {
    	var node = this.getNodeById(id);
        if (node) {
            node.remove(); 
        }
    }
    
    /**
     * Dynamically removes active node
     * @access public 
     */
    ,removeActiveNode: function() {
        this.cm.activeNode.remove();
    }
	
    /**
     * Gets a default toolbar setup
     */
	,getToolbar: function() {
		var iu = MODx.config.template_url+'images/restyle/icons/';
        return [{
            icon: iu+'arrow_down.png'
            ,cls: 'x-btn-icon'
            ,scope: this
            ,tooltip: {text: _('tree_expand')}
            ,handler: this.expand
        },{
            icon: iu+'arrow_up.png'
            ,cls: 'x-btn-icon'
            ,scope: this
            ,tooltip: {text: _('tree_collapse')}
            ,handler: this.collapse
        },'-',{
            icon: iu+'refresh.png'
            ,cls: 'x-btn-icon'
            ,scope: this
            ,tooltip: {text: _('tree_refresh')}
            ,handler: this.refresh
        }];
    }
	
	/**
	 * Add Items to the toolbar.
	 * @param {Ext.Toolbar} tb The toolbar to attach to.
	 * @param {Array} items An array of items to add.
	 */
	,_formatToolbar: function(a) {
		var l = a.length;
		for (var i = 0; i < l; i++) {
            if (a[i].handler) {
                a[i].handler = eval(a[i].handler);
            }
			Ext.applyIf(a[i],{
				scope: this
				,cls: 'x-btn-icon'
			});
		}
		return a;
	}
	
    /**
     * If set for the tree, displays a help dialog.
     * @abstract
     */
    ,help: function() {
        MODx.msg.alert(_('help'),_('help_not_yet'));
    }
});
Ext.reg('modx-tree',MODx.tree.Tree);