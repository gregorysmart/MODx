Ext.namespace('MODx','MODx.tree');
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
		if (treeState == undefined) {
			if (this.root) this.root.expand();
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
        this.windows.duplicate = new MODx.window.DuplicateResource({
            resource: id
            ,is_folder: node.getUI().hasClass('folder')
        	,scope: this
        	,success: this.refreshParentNode
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
			,connector: MODx.config.connectors_url+'resource/document.php'
			,params: {
				action: 'delete'
				,id: id
			}
			,scope: this
			,success: this.refreshParentNode
		});
	}
	
	,undeleteDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		Ext.Ajax.request({
			url: MODx.config.connectors_url+'resource/document.php'
			,params: {
				action: 'undelete'
				,id: id
			}
			,scope: this
			,success: function (r,o) {
				r = Ext.decode(r.responseText);
				r.success ?	this.refreshParentNode() : FormHandler.errorJSON(r);
			}
		});
	}
	
	,publishDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.msg.confirm({
			title: _('publish_document')
			,text: _('confirm_publish')
			,connector: MODx.config.connectors_url+'resource/document.php'
			,params: {
				action: 'publish'
				,id: id
			}
			,scope: this
			,success: this.refreshParentNode
		});
	}
	
	,unpublishDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.msg.confirm({
			title: _('unpublish_document')
			,text: _('confirm_unpublish')
			,connector: MODx.config.connectors_url+'resource/document.php'
			,params: {
				action: 'unpublish'
				,id: id
			}
			,scope: this
			,success: this.refreshParentNode
		});
	}
	
	,emptyRecycleBin: function(item,e) {
        Ext.Ajax.request({
            url: MODx.config.connectors_url+'resource/document.php'
            ,params: {
				action: 'emptyRecycleBin'
			}
            ,scope: this
			,success: function(r,o) {
				r = Ext.decode(r.responseText);
				r.success ? this.refresh() : FormHandler.errorJSON(r);
			}
       	});
	}
	
	,showFilter: function(itm,e) {
		if (this._filterVisible) return false;
		
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
});
Ext.reg('tree-document',MODx.tree.Document);