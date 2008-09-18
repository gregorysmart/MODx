/**
 * Generates the Document Tree in Ext
 * 
 * @class MODx.tree.Document
 * @extends MODx.tree.Tree
 * @constructor
 * @param {Object} config An object of options.
 * @xtype tree-document
 */
MODx.tree.Document = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		rootVisible: false
		,expandFirst: true
		,enableDrag: true
		,enableDrop: true
		,sortBy: 'menuindex'
		,title: ''
		,remoteToolbar: true
		,url: MODx.config.connectors_url+'layout/tree/document.php'
	});
	MODx.tree.Document.superclass.constructor.call(this,config);
    if (config.el) {
        var el = Ext.get(config.el);
        el.createChild({ tag: 'div', id: 'modx_doctree_tb' });
        el.createChild({ tag: 'div', id: 'modx_doctree_filter' });
    }
};
Ext.extend(MODx.tree.Document,MODx.tree.Tree,{
	forms: {}
	,windows: {}
	,stores: {}
	
	,_initExpand: function() {
		var treeState = Ext.state.Manager.get(this.treestate_id);
		if (treeState === undefined) {
			if (this.root) { this.root.expand(); }
			var wn = this.getNodeById('web_0');
			if (wn && this.config.expandFirst) {
				wn.select();
				wn.expand();
			}
		} else {
            this.expandPath(treeState);
        }
	}
	
	,duplicateResource: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		
		var r = { 
		    resource: id
		    ,is_folder: node.getUI().hasClass('folder')
	    };
		if (this.windows.duplicate) {
           this.windows.duplicate.destroy(); 
        }
        this.windows.duplicate = MODx.load({
        	xtype: 'window-resource-duplicate'
            ,resource: id
            ,is_folder: node.getUI().hasClass('folder')
            ,listeners: {
            	'success': {fn:this.refreshParentNode,scope:this}
            }
        });
		this.windows.duplicate.setValues(r);
		this.windows.duplicate.show(e.target);
	}
	
    ,preview: function(item,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_'); id = id[1];
        window.open(MODx.config.base_url+'index.php?id='+id);
    }
    
	,deleteDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.msg.confirm({
			title: _('delete_document')
			,text: _('confirm_delete_document')
			,url: MODx.config.connectors_url+'resource/document.php'
			,params: {
				action: 'delete'
				,id: id
			}
			,listeners: {
				'success': {fn:this.refreshParentNode,scope:this}
			}
		});
	}
	
	,undeleteDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.Ajax.request({
			url: MODx.config.connectors_url+'resource/document.php'
			,params: {
				action: 'undelete'
				,id: id
			}
			,listeners: {
				'success': {fn:this.refreshParentNode,scope:this}
			}
		});
	}
	
	,publishDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.msg.confirm({
			title: _('publish_document')
			,text: _('confirm_publish')
			,url: MODx.config.connectors_url+'resource/document.php'
			,params: {
				action: 'publish'
				,id: id
			}
			,listeners: {
				'success': {fn:this.refreshParentNode,scope:this}
			}
		});
	}
	
	,unpublishDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.msg.confirm({
			title: _('unpublish_document')
			,text: _('confirm_unpublish')
			,url: MODx.config.connectors_url+'resource/document.php'
			,params: {
				action: 'unpublish'
				,id: id
			}
			,listeners: {
				'success': {fn:this.refreshParentNode,scope:this}
			}
		});
	}
	
	,emptyRecycleBin: function(item,e) {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'resource/document.php'
            ,params: {
				action: 'emptyRecycleBin'
			}
			,listeners: {
				'success':{fn:this.refresh,scope:this}
			}
       	});
	}
	
	,showFilter: function(itm,e) {
		if (this._filterVisible) { return false; }
		
		var t = Ext.get('modx_doctree_filter');
		var fbd = t.createChild({tag: 'div'});
		var tb = new Ext.Toolbar(fbd);
		var cb = new Ext.form.ComboBox({
			store: new Ext.data.SimpleStore({
				fields: ['name','value']
				,data: [
					[_('menu_order'),'menuindex']
					,[_('page_title'),'pagetitle']
					,[_('publish_date'),'pub_date']
					,[_('createdon'),'createdon']
					,[_('editedon'),'editedon']
				]
			})
			,displayField: 'name'
			,valueField: 'value'
			,editable: false
			,mode: 'local'
			,triggerAction: 'all'
			,selectOnFocus: false
			,width: 100
			,value: this.config.sortBy
            ,listeners: {
                'select': {fn:this.filterSort,scope:this}
            }
		});
		tb.add(_('sort_by')+':');
		tb.addField(cb);
		tb.add('-',{
			scope: this
			,cls: 'x-btn-icon'
			,icon: MODx.config.template_url+'images/icons/close.gif'
			,handler: this.hideFilter
		});
		this.filterBar = tb;
		this._filterVisible = true;
	}
	
	,filterSort: function(cb,r,i) {
		this.config.sortBy = cb.getValue();
		this.getLoader().baseParams = {
			action: this.config.action
			,sortBy: this.config.sortBy
		};
		this.refresh();
	}
	
	,hideFilter: function(itm,e) {
		this.filterBar.destroy();
		this._filterVisible = false;
	}
	
	
    ,_handleDrop:  function(e){
        var dropNode = e.dropNode;
        var targetParent = e.target;

        if (targetParent.findChild('id',dropNode.attributes.id) !== null) { return false; }        
        var ap = true;
        if (targetParent.attributes.type == 'context' && e.point != 'append') {
        	ap = false;
        }
        
        return dropNode.attributes.text != 'root' && dropNode.attributes.text !== '' 
            && targetParent.attributes.text != 'root' && targetParent.attributes.text !== ''
            && ap;
    }
});
Ext.reg('tree-document',MODx.tree.Document);