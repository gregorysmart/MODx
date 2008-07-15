// vim: ts=2:sw=2:nu:fdc=4:nospell

// Create user extensions namespace (Ext.ux)
Ext.namespace('Ext.ux');

/**
  * Ext.ux.FileTreePanel Extension Class
  *
  * @author  Ing. Jozef Sakalos
  * @version $Id: Ext.ux.FileTreePanel.js 58 2007-07-23 14:37:18Z jozo $
  *
  * @class Ext.ux.FileTreePanel
  * @extends Ext.tree.TreePanel
  * @constructor
  * Creates new Ext.ux.FileTreePanel
  */
Ext.ux.FileTreePanel = function(el, config) {

	// {{{
	// create tree loaeder if it doesn't exist in config
	if(config && !config.loader) {
		config.loader = new Ext.tree.TreeLoader({
			dataUrl: config.dataUrl
		});
		config.loader.baseParams.cmd = 'get';

		// do not rely on node.id attribute send path instead
		config.loader.on({
			beforeload:{
				fn: function(loader, node) {
					loader.baseParams.path = node.getPath('text').substr(1);
				}
		}});
	}
	// }}}
	// {{{
	// adjust drop configuration
	if(!config.dropConfig) {
		config.dropConfig = {
			ddGroup: config.ddGroup || "TreeDD"
			, appendOnly: config.ddAppendOnly === true
			, expandDelay: 3600000 // do not expand on drag over node
		};
	}
	// }}}
	// {{{
	// call parent constructor
	config.el = el;
	Ext.ux.FileTreePanel.superclass.constructor.call(this,config);
	// }}}
	// {{{
	// icons
	// iconPath
	this.iconPath = config && config.iconPath ? config.iconPath : '../img/silk/icons';

	// rename icon
	this.renameIcon = config && config.renameIcon ? config.renameIcon : 'pencil.png';
	this.renameIcon = this.iconPath + '/' + this.renameIcon;

	// delete icon
	this.deleteIcon = config && config.deleteIcon ? config.deleteIcon : 'cross.png';
	this.deleteIcon = this.iconPath + '/' + this.deleteIcon;

	// new directory icon
	this.newDirIcon = config && config.newDirIcon ? config.newDirIcon : 'folder_add.png';
	this.newDirIcon = this.iconPath + '/' + this.newDirIcon;

	// reload icon
	this.reloadIcon = config && config.reloadIcon ? config.reloadIcon : 'arrow_refresh.png';
	this.reloadIcon = this.iconPath + '/' + this.reloadIcon;

	// expand icon
	this.expandIcon = config && config.expandIcon ? config.expandIcon : 'arrow_right.png';
	this.expandIcon = this.iconPath + '/' + this.expandIcon;

	// collapse icon
	this.collapseIcon = config && config.collapseIcon ? config.collapseIcon : 'arrow_left.png';
	this.collapseIcon = this.iconPath + '/' + this.collapseIcon;

	// open icon
	this.openIcon = config && config.openIcon ? config.openIcon : 'application_go.png';
	this.openIcon = this.iconPath + '/' + this.openIcon;

	// open in popup icon
	this.openPopupIcon = config && config.openPopupIcon ? config.openPopupIcon : 'application_double.png';
	this.openPopupIcon = this.iconPath + '/' + this.openPopupIcon;

	// open in this window icon
	this.openSelfIcon = config && config.openSelfIcon ? config.openSelfIcon : 'application.png';
	this.openSelfIcon = this.iconPath + '/' + this.openSelfIcon;

	// open in new window icon
	this.openBlankIcon = config && config.openBlankIcon ? config.openBlankIcon : 'application_cascade.png';
	this.openBlankIcon = this.iconPath + '/' + this.openBlankIcon;
	// }}}
	// {{{
	// tree editor
	if(config && config.edit) {
		// create tree editor
		this.treeEditor = new Ext.ux.FileTreeEditor(this, {allowBlank:false});

		// install default handling of edit complete
		this.treeEditor.on('complete', this.onEditComplete, this);
	}
	// }}}
	// {{{
	// tree sorter
	if(config && config.sort) {
		this.treeSorter = new Ext.tree.TreeSorter(this, {folderSort:true});
	}
	// }}}
	// {{{
	// install event handlers
	this.on({
		contextmenu: {scope:this, fn:this.onContextMenu}
		, beforenodedrop: {scope:this, fn:this.onBeforeNodeDrop}
		, dblclick: {scope:this, fn:this.openNode}
		, render: {scope:this, fn:function() {
			this.setReadOnly(this.readOnly);
			this.setRenameDisabled(!this.enableRename);
		}}
	});
	// }}}
	// {{{
	// install keymap
	this.keymap = new Ext.KeyMap(this.getEl(), [

		// {{{
		// open
		{ 
			key: Ext.EventObject.ENTER // F2 key = edit
			, scope: this
			, fn: function(key, e) {
				var sm = this.getSelectionModel();
				var node = sm.getSelectedNode();
				if(node && 0 !== node.getDepth() && node.isLeaf()) {
					this.openNode(node);
				}
		}}
		// }}}
		// {{{
		// edit
		, { 
			key: 113 // F2 key = edit
			, scope: this
			, fn: function(key, e) {
				var sm = this.getSelectionModel();
				var node = sm.getSelectedNode();
				if(node && 0 !== node.getDepth() && this.enableRename && this.readOnly !== true) {
					this.treeEditor.triggerEdit(node);
				}
		}}
		// }}}
		// {{{
		// delete
		, {
			key: 46 // Delete key
			, stopEvent: true
			, scope: this
			, fn: function(key, e) {
				var sm = this.getSelectionModel();
				var node = sm.getSelectedNode();
				if(node && 0 !== node.getDepth() && this.enableDelete && this.readOnly !== true) {
					this.deleteNode(node);
				}
		}}
		// }}}
		// {{{
		// reload
		, {
			key: 69 // Ctrl + E = reload
			, ctrl: true
			, stopEvent: true
			, scope: this
			, fn: function(key, e) {
				var sm = this.getSelectionModel();
				var node = sm.getSelectedNode();
				if(node) {
					node = node.isLeaf() ? node.parentNode : node;
					sm.select(node);
					node.reload();
				}
		}}
		// }}}
		// {{{
		// expand deep
		, {
			key: 39 // Ctrl + Right arrow = expand deep
			, ctrl: true
			, stopEvent: true
			, scope: this
			, fn: function(key, e) {
				var sm = this.getSelectionModel();
				var node = sm.getSelectedNode();
				if(node && !node.isLeaf()) {
					sm.select(node);
					node.expand.defer(1, node, [true]);
				}
		}}
		// }}}
		// {{{
		// collapse deep
		, {
			key: 37 // Ctrl + Left arrow = collapse deep
			, ctrl: true
			, scope: this
			, stopEvent: true
			, fn: function(key, e) {
				var sm = this.getSelectionModel();
				var node = sm.getSelectedNode();
				if(node && !node.isLeaf()) {
					sm.select(node);
					node.collapse.defer(1, node, [true]);
				}
		}}
		// }}}
		// {{{
		// new directory
		, {
			key: 78 // Ctrl + N = New directory
			, ctrl: true
			, scope: this
			, stopEvent: true
			, fn: function(key, e) {
				var sm, node;
				sm = this.getSelectionModel();
				node = sm.getSelectedNode();
				if(node && this.enableNewDir && this.readOnly !== true) {
					node = node.isLeaf() ? node.parentNode : node;
					this.createNewDir(node);
				}
		}}
		// }}}
		// {{{
		// upload (just shows context menu)
		, {
			key: 85 // Ctrl + U = Upload file
			, ctrl: true
			, scope: this
			, fn: function(key, e) {
				var sm, node, fakeEvent;
				e.stopEvent();
				e.stopPropagation();
				sm = this.getSelectionModel();
				node = sm.getSelectedNode();
				if(node && this.enableUpload && this.readOnly !== true) {
					fakeEvent = {
						stopEvent: Ext.emptyFn
						, preventDefault: Ext.emptyFn
					};
					this.onContextMenu(node, fakeEvent);
				}
		}}
		// }}}

	]);
	// }}}
	// {{{
	// add events
	this.addEvents({
		render: true
	});
	// }}}

}; // end of Ext.ux.FileTreePanel constructor

// extend
Ext.extend(Ext.ux.FileTreePanel, Ext.tree.TreePanel, {

	// {{{
	// defaults
	renameText: _('rename')
	, deleteText: _('delete')
	, uploadText: _('upload')
	, reloadText: _('reload_hk')
	, expandText: _('expand_all')
	, collapseText: _('collapse_all')
	, openText: _('open')
	, openPopupText: _('open_in_popup')
	, openSelfText: _('open_in_self')
	, openBlankText: _('open_in_new')
	, errorText: _('error')
	, existsText: _('file_exists_hk')
	, overwriteText: _('confirm_overwrite')
	, confirmText: _('confirm')
	, uploadFileText: _('upload_file_hk')
	, reallyWantText: _('confirm_really_want')
	, newDirText: _('new_folder')
	, method: 'post'
	, fileCls: 'file'
	, enterKeyName: _('key_enter')
	, rarrowKeyName: _('key_right_arrow')
	, larrowKeyName: _('key_left_arrow')
	, deleteKeyName: _('key_delete')
	, openMode: 'popup' // or _self or _blank
	, hrefPrefix: '/'
	, hrefSuffix: ''
	, popupFeatures: 'width=640,height=480,dependent=1,scrollbars=1,resizable=1,toolbar=1'
	, focusPopup: false
	, readOnly: false
	, enableUpload: true
	, enableRename: true
	, enableNewDir: true
	, enableDelete: true
	, enableDD: true
	// }}}
	// {{{
	/**
		* context menu event handler
		* @param {TreeNode} node
		* @param {Event} e 
		*/
	, onContextMenu: function(node, e) {

		if(this.readOnly) {
			return;
		}

		e.stopEvent();
		e.preventDefault();

		// {{{
		// lazy create upload form
		var uploadFormCt, fname;
		if(!this.uploadForm) {
			
			// create container for upload form
			uploadFormCt = Ext.DomHelper.append(document.body, {
				tag: 'div', id: 'uf-ct-' + this.id, style: 'margin-left:30px;margin-bottom:4px;width:154px'
				, children: [
					{tag:'div', html:this.uploadFileText + ' (Ctrl+U)'}
					, {tag:'br'}
				]
			}, true);

			this.uploadForm = new Ext.ux.UploadForm(uploadFormCt, {
				url: this.uploadUrl || this.dataUrl
				, autoCreate: true
				, baseParams: {cmd: 'upload'}
				, maxFileSize: this.maxFileSize
				, iconPath: this.iconPath
				, pgCfg: this.pgCfg
			});
			// install event handlers on the form
			this.uploadForm.on({
				actioncomplete: {scope:this, fn:this.onUploadSuccess}
			});
		}
		// }}}
		// {{{
		// lazy create context menu
		if(!this.contextMenu) {
			this.contextMenu = new Ext.menu.Menu({
				items: [
						// node name we're working with placeholder
					  { id:'nodename', disabled:true, cls:'x-filetree-nodename'}
					, {
						id: 'open'
						, text: this.openText + ' ('+this.enterKeyName+')'
						, icon: this.openIcon
						, scope: this
						, handler: this.onContextMenuItem
						, menu: {
							items: [
							  { id: 'open-self'
								, text: this.openSelfText
								, icon: this.openSelfIcon
								, scope: this
								, handler: this.onContextMenuItem
							}
							,	{ id: 'open-popup'
								, text: this.openPopupText
								, icon: this.openPopupIcon
								, scope: this
								, handler: this.onContextMenuItem
								}
							, { id: 'open-blank'
								, text: this.openBlankText
								, icon: this.openBlankIcon
								, scope: this
								, handler: this.onContextMenuItem
							}
							]
						}
					}

					, new Ext.menu.Separator({id:'sep-open'})
					, {	id:'reload'
						, text:this.reloadText + ' (Ctrl+E)'
						, icon:this.reloadIcon
						, scope:this
						, handler:this.onContextMenuItem
					}
					, {	id:'expand'
						, text:this.expandText + ' (Ctrl+' + this.rarrowKeyName + ')'
						, icon:this.expandIcon
						, scope:this
						, handler:this.onContextMenuItem
					}
					, {	id:'collapse'
						, text:this.collapseText + ' (Ctrl+' + this.larrowKeyName + ')'
						, icon:this.collapseIcon
						, scope:this
						, handler:this.onContextMenuItem
					}
					, new Ext.menu.Separator({id:'sep-collapse'})
					, {	id:'rename'
						, text:this.renameText + ' (F2)'
						, icon:this.renameIcon
						, scope:this
						, handler:this.onContextMenuItem
					}
					, {	id:'delete'
						, text:this.deleteText + ' (' + this.deleteKeyName + ')'
						, icon:this.deleteIcon
						, scope:this
						, handler:this.onContextMenuItem
					}
//					, new Ext.menu.Separator()
					, { id:'newdir'
						, text:this.newDirText + '... (Ctrl+N)'
						, icon:this.newDirIcon
						, scope:this
						, handler:this.onContextMenuItem
					}
					, new Ext.menu.Separator({id:'sep-upload'})
				]
			});

			// add upload form at the end of context menu
			this.contextMenu.addElement(uploadFormCt).hideOnClick = false;

			// handle shadow on file add/remove to/from UploadForm
			var showShadow = this.contextMenu.getEl().shadow.show.createDelegate(this.contextMenu.getEl().shadow, [this.contextMenu.getEl()]);
			this.uploadForm.on({
				fileadded:{fn:showShadow}
				, fileremoved:{fn:showShadow}
				, allremoved:{fn:showShadow}
			});

		}
		// }}}

		// setup path for upload
		this.uploadForm.baseParams.path = 
			node.isLeaf()
			? node.parentNode.getPath('text').substr(1)
			: node.getPath('text').substr(1)
		;

		// save node and node to reload after upload
		this.uploadForm.reloadNode = this.root === node ? this.root : node.isLeaf() ? node.parentNode : node;
		this.uploadForm.node = node;

		// save current node to context menu and open submenu
		var menu = this.contextMenu;
		menu.node = node;
		menu.items.get('open').menu.node = node;

		// set menu item text to node text
		var itemNodename = menu.items.get('nodename');
		itemNodename.setText(Ext.util.Format.ellipsis(node.text, 25));

		// disable delete and rename for root node
		var itemDelete = menu.items.get('delete');
		itemDelete.setDisabled(node === this.root || node.disabled);

		var itemRename = menu.items.get('rename');
		itemRename.setDisabled(node === this.root || node.disabled);

		var itemNewDir = menu.items.get('newdir');
		itemNewDir.setDisabled(node.isLeaf() ? node.parentNode.disabled : node.disabled);

		menu.items.get('reload').setDisabled(node.isLeaf());
		menu.items.get('expand').setDisabled(node.isLeaf());
		menu.items.get('collapse').setDisabled(node.isLeaf());
		menu.items.get('open').setDisabled(!node.isLeaf());
		this.uploadForm.setDisabled(node.isLeaf() ? node.parentNode.disabled : node.disabled, true);

		// hide/show logic
		// delete
		if(false === this.enableDelete) {
			itemDelete.hide();
		}

		// newdir
		if(false === this.enableNewDir) {
			itemNewDir.hide();
		}

		// rename
		if(false === this.enableRename) {
			itemRename.hide();
		}
		//this.dragZone.locked = this.enableRename === false;

		// separator
		if(this.enableDelete === false && this.enableRename === false & this.enableNewDir === false) {
			menu.items.get('sep-collapse').hide();
		}

		// upload
		if(false === this.enableUpload) {
			menu.items.get('uf-ct-' + this.id).hide();
			menu.items.get('sep-upload').hide();
		}

		node.select();

		// show context menu at right position
		menu.showAt(menu.getEl().getAlignToXY(node.getUI().getEl(), 'tl-tl?', [0, 18]));
		itemNodename.container.setStyle('opacity', 1);

	}
	// }}}
	// {{{
	/**
		* context menu item click handler
		* @param {MenuItem} item
		* @param {Event} e event
		*/
	, onContextMenuItem: function(item, e) {

		// setup variables
		var node = item.parentMenu.node;
		var appendNode, newNode;
		var options = {};
		var treeEditor = this.treeEditor;

		// menu item switch
		switch(item.id) {

			// {{{
			// rename file
			case 'rename':
				treeEditor.triggerEdit(node);
			break;
			// }}}
			// {{{
			// delete file/directory
			case 'delete':
				this.deleteNode(node);
			break;
			// }}}
			// {{{
			// new directory
			case 'newdir':
				this.createNewDir(node);
			break;
			// }}}
			// {{{
			case 'reload':
				// just reload the node if it's not leaf
				if(!node.isLeaf()) {
					node.reload();
				}
			break;
			// }}}
			// {{{
			case 'expand':
				node.expand(true);
			break;
			// }}}
			// {{{
			case 'collapse':
				node.collapse(true);
			break;
			// }}}

			case 'open':
				this.openNode(node);
			break;

			case 'open-popup':
				this.openNode(node, null, 'popup');
			break;

			case 'open-self':
				this.openNode(node, null, '_self');
			break;

			case 'open-blank':
				this.openNode(node, null, '_blank');
			break;

		} // end of switch(item.id)
	}
	// }}}
	// {{{
	/**
		* Create new directory (node)
		* private
		* @param {Ext.tree.Node} node
		*/
	, createNewDir: function(node) {
		var treeEditor = this.treeEditor;
		var newNode;

		// get node to append new directory to
		var appendNode = node.isLeaf() ? node.parentNode : node;

		// create new folder after the appendNode is expanded
		appendNode.expand(null, false, function(n) {

			// create new node
			newNode = n.appendChild(new Ext.tree.AsyncTreeNode({
				text: this.newDirText
				, cls: 'folder'
			}));

			// setup one-shot event handler for editing completed
			treeEditor.on({
				complete:{
					scope: this
					, single: true
					, fn: this.onNewDir
			}});

			// creating new directory flag
			treeEditor.creatingNewDir = true;

			// start editing after short delay
			(function(){treeEditor.triggerEdit(newNode);}.defer(10));

		// expand callback needs to run in this context
		}.createDelegate(this));

	}
	// }}}
	// {{{
	/**
		* deletes the passed node
		* private
		* @param {Ext.tree.Node} node
		*/
	, deleteNode: function(node) {
		// display confirmation message
		Ext.Msg.confirm(this.deleteText
			, this.reallyWantText + ' ' + this.deleteText.toLowerCase() + ' <strong>' + node.text + '</strong>?'  
			, function(response) {

				var conn;
				// do nothing if answer is not yes
				if('yes' !== response) {
					this.getEl().dom.focus();
					return;
				}

				// answer is yes
				else {

					// setup request options
					var options = {
						url: this.deleteUrl || this.dataUrl
						, method: this.method
						, scope: this
						, callback: this.cmdCallback
						, node: node
						, params: {
							cmd: 'delete'
							, file: node.getPath('text').substr(1)
						}
					};

					// send request
					conn = new Ext.data.Connection().request(options);
				}
			}
			, this
		);

		// set focus to no button to avoid accidental deletions
		var msgdlg = Ext.Msg.getDialog();
		msgdlg.setDefaultButton(msgdlg.buttons[2]).focus();
	}
	// }}}
	// {{{
	/**
		* runs when editing of a node (rename) is completed
		* private
		* @param {Ext.Editor} editor
		* @param {String} newName
		* @param {String} oldName
		*/
	, onEditComplete: function(editor, newName, oldName) {

		var node = editor.editNode;

		if(newName === oldName || editor.creatingNewDir) {
			editor.creatingNewDir = false;
			return;
		}
		var path = node.parentNode.getPath('text').substr(1);
		var options = {
			url: this.renameUrl || this.dataUrl
			, method: this.method
			, scope: this
			, callback: this.cmdCallback
			, node: node
			, oldName: oldName
			, params: {
				cmd: 'rename'
				, oldname: path + '/' + oldName
				, newname: path + '/' + newName
			}
		};
		var conn = new Ext.data.Connection().request(options);
	}
	// }}}
	// {{{
	/**
		* Create new directory handler
		* runs after editing of new directory name is completed
		* private
		* @param {Ext.Editor} editor
		*/
	, onNewDir: function(editor) {
		var path = editor.editNode.getPath('text').substr(1);
		var options = {
			url: this.newdirUrl || this.dataUrl
			, method: this.method
			, scope: this
			, node: editor.editNode
			, callback: this.cmdCallback
			, params: {
				cmd: 'newdir'
				, dir: path
			}
		};
		var conn = new Ext.data.Connection().request(options);
	}
	// }}}
	// {{{
	/**
		* runs on upload file success
		* private
		* @param {Ext.form.Form} form
		* @param {Ext.form.Action} action
		*/
	, onUploadSuccess: function(form, action) {
//		this.contextMenu.hide();
		form.reloadNode.reload();
	}
	// }}}
	// {{{
	/**
		* runs on upload file failure
		* private
		* @param {Ext.form.Form} form
		* @param {Ext.form.Action} action
		*/
	, onUploadFailure: function(form, action) {
		// no action as messages are displayed in quick tip next to upload field
	}
	// }}}
	// {{{
	/**
		* Opens node
		* @param {Ext.tree.AsyncTreeNode} node
		* @param {String} mode Can be "_self", "_blank", or "popup". Defaults to (this.openMode)
		*/
	, openNode: function(node, e, mode) {
		var url;
		mode = mode || this.openMode;
		if(node.isLeaf()) {
			url = this.hrefPrefix + node.getPath('text').substr(1) + this.hrefSuffix;
			switch(mode) {
				case 'popup':
					if(!this.popup || this.popup.closed) {
						this.popup = window.open(url, this.hrefTarget, this.popupFeatures);
					}
					this.popup.location = url;
					if(this.focusPopup) {
						this.popup.focus();
					}
				break;

				case '_self':
					window.location = url;
				break;

				case '_blank':
					window.open(url);
				break;
			}
		}
	}
	// }}}
	// {{{
	/**
		* run before node is dropped
		* private
		* @param {Object} e dropEvent object
		*/
	, onBeforeNodeDrop: function(e) {

		// source node, node being dragged
		var s = e.dropNode;

		// destination node (dropping on this node)
		var d = e.target.leaf ? e.target.parentNode : e.target;

		// node has been dropped within the same parent
		if(s.parentNode === d) {
			return false;
		}

		// check if same name exists in the destination
		// this works only if destination node is loaded
		if(this.hasChild(d, s.text) && !e.confirmed) {
			this.confirmOverwrite(s.text, function() {
				e.confirmed = true;
				this.onBeforeNodeDrop(e);
			});
			return false;
		}
		e.confirmed = false;

		var oldName = s.getPath('text').substr(1);
		var newName = d.getPath('text').substr(1) + '/' + s.text;

		var options = {
			url: this.renameUrl || this.dataUrl
			, method: this.method
			, scope: this
			, callback: this.cmdCallback
			, node: s
			, oldParent: s.parentNode
			, params: {
				cmd: 'rename'
				, oldname: oldName
				, newname: newName
			}
		};
		var conn = new Ext.data.Connection().request(options);
		return true;
	}
	// }}}
	// {{{
	/**
		* runs after an ajax requested command is completed/failed
		* @param {Object} options Options used for the request
		* @param {Boolean} bSuccess true if ajax call was successful (cmd may have failed)
		* @param {Object} response ajax call response object
		*/
	, cmdCallback: function(options, bSuccess, response) {
		var i, o, node;
		
		if(true === bSuccess) {
			o = Ext.decode(response.responseText);
			
			// {{{
			// handle success
			if(true === o.success) {

				switch(options.params.cmd) {
					case 'delete':
						options.node.parentNode.removeChild(options.node);
					break;

					case 'newdir':
						// no cmd on purpose
					break;

					case 'rename':
						this.updateCls(options.node, options.params.oldname);
					break;
				}
			} // end of handle success
			// }}}
			// {{{
			// handle failure
			else {
				Ext.Msg.alert(this.errorText,o.message);
				switch(options.params.cmd) {

					case 'rename':
						// handle drag & drop rename error
						if(options.oldParent) {
							options.oldParent.appendChild(options.node);
						}
						// handle simple rename error
						else {
							options.node.setText(options.oldName);
						}
					break;

					case 'newdir':
						options.node.parentNode.removeChild(options.node);
					break;

					case 'delete':
						// empty action on purpose
					break;

					default:
						this.root.reload();
					break;
				}
			} // end of handle failure
			// }}}

		}
	}
	// }}}
	// {{{
	/**
		* returns true if node has child with the specified name (text)
		* private
		* @param {Ext.data.Node} node
		* @param {String} childName
		*/
	, hasChild: function(node, childName) {
		return (node.isLeaf() ? node.parentNode : node).findChild('text', childName) !== null;
	}
	// }}}
	// {{{
	/**
		* Displays confirmation msg box if same name already exists
		* private
		* @param {Ext.form.Form} form
		* @param {Ext.data.Action} action
		*/
	, onBeforeUpload: function(form, action) {
		return true;

		if(form.confirmed) {
			form.confirmed = false;
			return true;
		}
		var upfield = form.findField(this.id + '-fname');
		var filename = upfield.getValue();
		var exists;
		if(filename) {
			filename = filename.split(/[\/\\]/).pop();
			if(this.hasChild(form.node, filename)) {
				this.confirmOverwrite(filename, function() {
					form.confirmed = true;
					form.submit({url:this.uploadUrl || this.dataUrl});
				});
			}
			else {
				return true;
			}
		}
		return false;
	}
	// }}}
	// {{{
	/**
		* displays overwrite confirm msg box and runs callback if response is yes
		* @param {String} filename File to overwrite
		* @param {Function} callback Function to call on yes response
		* @param {Object} scope Scope for callback (defaults to this)
		*/
	, confirmOverwrite: function(filename, callback, scope) {
		Ext.Msg.confirm(this.confirmText
		, String.format(this.existsText, filename) 
			+ '. ' + this.overwriteText
		, function(response) {
			if('yes' === response) {
				callback.call(scope || this);
			}	
		}
		, this);
		var msgdlg = Ext.Msg.getDialog();
		msgdlg.setDefaultButton(msgdlg.buttons[2]).focus();
		msgdlg.setZIndex(16000);
	}
	// }}}
	// {{{
	/**
		* update class of leaf after rename
		* private
		* @param {Ext.tree.TreeNode} node Node to update class of
		* @param {String} oldName Name the node had before
		*/
	, updateCls: function(node, oldName) {
		if(node.isLeaf()) {
			node.getUI().removeClass(this.getFileCls(oldName));
			node.getUI().addClass(this.getFileCls(node.text));
		}
	}
	// }}}
	// {{{
	/**
		* returns file class based on name extension
		* private
		* @param {String} name File name to get class of
		*/
	, getFileCls: function(name) {
		var atmp = name.split('.');
		return 1 === atmp.length ? this.fileCls : this.fileCls + '-' + atmp.pop();
	}
	// }}}
	// {{{
	/**
		* Switch readOnly mode on/off at runtime
		*
		* @param {Boolean} true = read only, anyting = else read write 
		*/
	, setReadOnly: function(readOnly) {
		readOnly = readOnly === true;
		this.readOnly = readOnly;
		this.setRenameDisabled(readOnly);
	}
	// }}}
	// {{{
	/**
		* Set file renames disabled
		* @param {Boolean} true = disable, anything else = enable
		*/
	, setRenameDisabled: function(disable) {
		this.enableRename = disable !== true;
		//this.dragZone.locked = !this.enableRename;
	}
	// }}}
	// {{{
	/**
		* Set file deletes disabled
		* @param {Boolean} true = disable, anything else = enable
		*/
	, setDeleteDisabled: function(disable) {
		this.enableDelete = disable !== true;
	}
	// }}}
	// {{{
	/**
		* Set new directory disabled
		* @param {Boolean} true = disable, anything else = enable
		*/
	, setNewDirDisabled: function(disable) {
		this.enableNewDir = disable !== true;
	}
	// }}}
	// {{{
	/**
		* Set file uploads disabled
		* @param {Boolean} true = disable, anything else = enable
		*/
	, setUploadDisabled: function(disable) {
		this.enableUpload = disable !== true;
	}
	// }}}
	// {{{
	/**
		* Tree render function (calls parent and fires render event afterwards)
		* private
		*/
	, render: function() {
		Ext.tree.TreePanel.prototype.render.call(this);
		this.fireEvent('render', this);
	}
	// }}}

});

/**
  * Ext.ux.FileTreeEditor Extension Class
  *
  * @author  Ing. Jozef Sakalos
  * @version $Id: Ext.ux.FileTreePanel.js 58 2007-07-23 14:37:18Z jozo $
  *
  * @class Ext.ux.FileTreeEditor
  * @extends Ext.tree.TreeEditor
  * @constructor
  * Creates new Ext.ux.FileTreeEditor
  */
Ext.ux.FileTreeEditor = function(tree, config) {

	// call parent constructor
	Ext.ux.FileTreeEditor.superclass.constructor.call(this, tree, config);

};

// extend
Ext.extend(Ext.ux.FileTreeEditor, Ext.tree.TreeEditor, {
	beforeNodeClick: function(node, e) {
		return true;
	}
});

// end of file