
/**
MODExt Revolution 1.0
Copyright (c) 2007-2008, Shaun McCormick
All rights reserved
MODx-specific JS extension for ExtJS 2.2

-------------

The MODExt JS extension is distributed under the terms of the GNU GPLv3 license.
It extends ExtJS, distributed under the Open Source GPL 3.0 license.

http://www.gnu.org/licenses/gpl.html

-------------

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
General Public License for more details.
*/
/**
 * Generates the Action Buttons in Ext
 * 
 * @class MODx.toolbar.ActionButtons
 * @extends Ext.Toolbar
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-actionbuttons
 */
MODx.toolbar.ActionButtons = function(config) {
    config = config || {};
	MODx.toolbar.ActionButtons.superclass.constructor.call(this,config);
	this.id = id;
	Ext.applyIf(config,{
		actions: { 'close': MODx.action['welcome'] }
        ,params: {}
	});
	if (config.loadStay == true) {
		if (!config.items) config.items = [];
		config.items.push(this.getStayMenu());
	}
	this.config = config; // assign global options
	this.render('modAB');
};
Ext.extend(MODx.toolbar.ActionButtons,Ext.Toolbar,{
	/**
	 * @var {String} The ID of the toolbar.
	 */
	id: ''
	/**
	 * @var {Array} The array of buttons added.
	 */
	,buttons: []
	/**
	 * @var {Object} The options for the toolbar. The default close action goes to the home page.
	 */
	,options: { a_close: 'welcome' }
	/**
	 * @var {string} The stay action, default is to continue editing.
	 */
	,stay: 'stay' 
	
	/**
	 * Add in an action button. Takes multiple button configs as arguments.
	 */
	,create: function() {
		var a = arguments, l = a.length;
        for(var i = 0; i < l; i++) {
			var options = a[i];
			// if - sent, create a toolbar delimiter
			if (options == '-') {
				this.add(this,'-');
				continue;
			}
            Ext.applyIf(options,{
                xtype: 'button'
            });
			if (options.icon) {
				options.cls = 'x-btn-icon bmenu';
			}
            if (options.button) {
                this.add(this,options);
                continue;
            }
			Ext.applyIf(options,{
				cls: 'x-btn-text bmenu' 
				,scope: this // reference self for inline functions to have accurate scope
			});
			// if handler is specified for a button, execute that instead
			// this can be used for doing document-specific actions
			// such as using a Ext.menu.DateMenu in the action buttons
			// or some other item that opens up more options...you get the idea
			if (options.handler == null && options.menu == null) {
				options.handler = this.checkConfirm;
			} else if (options.handler) {
				if (options.confirm) {
					var f = options.handler;
					var c = options.confirm;
					var s = options.scope || this;
					options.handler = function() {
						Ext.Msg.confirm(_('warning'),c,function(e) {
						  if (e == 'yes') {
						      Ext.callback(f,this);
						  }
						},s);
					};
				}
			}
            			
			// create the button	
			var b = new Ext.Toolbar.Button(options);
            			
			// if javascript is specified, run it when button is click, before this.checkConfirm is run
			if (options.javascript) {
    			b.addListener('click',function(itm,e) {
    				if (!eval(itm.javascript)) {
    					e.stopEvent();
    					e.preventDefault();
    				}
    			},this);
			}
			
			// add button to toolbar
			this.add(this,b);
            
            
            if (options.keys) {
                var map = new Ext.KeyMap(Ext.get(document));
                var c = options.keys.length;
                for (var i=0;i<c;i++) {
                    var k = options.keys[i];
                    Ext.applyIf(k,{
                        scope: this
                        ,stopEvent: true
                        ,fn: function(e) { this.checkConfirm(b,e); }
                    });
                    map.addBinding(k);
                }
            }
		}
		return false;
	}
	
	/**
	 * If any confirm dialogs are specified, show them, else just redirect to the action.
	 * @param {Ext.Toolbar.Button} itm The action button pressed.
	 * @param {Ext.EventObject} e The event object.
	 */
	,checkConfirm: function(itm,e) {
		if (itm.confirm != null) {
			this.confirm(itm,function() {
				this.handleClick(itm,e);
			},this);
		} else this.handleClick(itm,e);
		return false;
	}
	
	/**
	 * Handle confirm dialogs.
	 * You can abstract this so that you can choose whether or not to have confirm 
	 * dialogs, and you can also pass in functions if you dont want to redirect.
	 * If you pass a function, the only argument will be the action button.
	 * @param {Ext.Toolbar.Button} itm The action button pressed
	 * @param {Object} callback An optional function to call after the confirm.
	 * @param {Object} scope The scope to execute the function in.
	 */
	,confirm: function(itm,callback,scope) {
		// if no message go ahead and redirect...we dont like blank questions
		if (itm.confirm == null) return true;
		
		Ext.Msg.confirm('',itm.confirm,function(e) {
			// if the user is okay with the action
			if (e == 'yes') {
				if (callback == null) return true;
				if (typeof(callback) == 'function') { // if callback is a function, run it, and pass Button
					Ext.callback(callback,scope || this,[itm]);
				} else location.href = callback;
			}
		},this);
	}
	
	/**
	 * Handle any onComplete events.
	 * 
	 * @param {Object} o The options for the action buttons
	 * @param {Object} itm The action button clicked
	 * @param {Object} res XHR responseText
	 */
	,checkOnComplete: function(o,itm,res) {
		// fire onComplete if it has been defined
        if (itm.hasListener('success') && res.success) {
            itm.fireEvent('success',{r:res});
        }
		if (itm.onComplete) {
			// need to redirect first, since there's a JS bug
			// that prevents the tree from refreshing fully whenever 
			// a location.href is called in the iframe
			Ext.callback(itm.onComplete,itm.onCompleteScope || this,[o,itm,res]);
			if (itm.reload) {
				Ext.callback(this.reloadPage,this,[],1000);
			}
			Ext.Msg.hide();
			Ext.callback(this.redirectStay,this,[o,itm,res],1000);
			return false;
		} else {
			this.redirectStay(o,itm,res);
		}
	}
	
	/**
	 * Reloads the page. Encapsulated in a function to provide delay.
	 */
	,reloadPage: function() {
		location.href = location.href;
	}
	
	/**
	 * Handle any clicks on action buttons.
	 * @param {Object} itm The button
	 * @param {Ext.EventObject} e The event object 
	 */
	,handleClick: function(itm,e) {
        var o = this.config;
		// action buttons handlers, abstracted to all get-out
		switch (itm.method) {
			case 'remote': // if using connectors
				MODx.util.Progress.reset(); // reset the Progress Bar
				Ext.Msg.show({
					title: _('please_wait')
					,msg: _('saving')
					,width: 240
					,progress: true // make it a progress bar
					,closable: false
				});
				
                // if using formpanel
                if (o.formpanel != undefined && o.formpanel != '' && o.formpanel != null) {
                    o.form = Ext.getCmp(o.formpanel).getForm();
                }
                
				// if using Ext.form
                if (o.form != undefined) {
					if (o.form.isValid()) { // client-side validation with modHExt
                        Ext.applyIf(o.params,{
                            action: itm.process
                           ,'modx-ab-stay': MODx.config.stay
                        });
						o.form.submit({
							params: o.params
							,reset: false
							,scope: this
							,failure: function(f,a) {
								MODx.form.Handler.errorExt(a.result,f);				
							}
							,success: function(f,a) {
								// update the progress bar
								MODx.util.Progress.time(5,MODx.util.Progress.id,_('refreshing_tree'));
								
								// allow for success messages
								if (a.result.message != '' && !itm.onComplete) {
									Ext.Msg.alert(_('success'),a.result.message,function() {
										if (this.checkOnComplete(o,itm,a.result)) {
										  o.refreshTree ? o.refreshTree.refresh() : parent.Ext.get('modx_document_tree').refresh();
										}
									 },this);
								} else {
									// refresh the tree, then pass the handling onto the checkOnComplete func									
									if (this.checkOnComplete(o,itm,a.result)) {
									   o.refreshTree ? o.refreshTree.refresh() : parent.Ext.get('modx_document_tree').refresh();
									}
								}
							}
						});
					} else {
						Ext.Msg.alert(_('error'),_('correct_errors'));	
					}
				} else {
					// now send form data through MODx.form.Handler to the connector
					MODx.form.Handler.send(o.form_id,itm.process,function(opt,s,r) {
						r = Ext.decode(r.responseText);
						
						if (r.success) {
							// update the progress bar
							MODx.util.Progress.time(5,MODx.util.Progress.id,_('refreshing_tree'));
							
							// refresh the tree, then pass the handling onto the checkOnComplete func
							o.refreshTree ? o.refreshTree.refresh() : parent.Ext.get('modx_document_tree').refresh();
							this.checkOnComplete(o,itm,r);
						} else {
							// if error, pass handling to MODx.form.Handler.js
							MODx.form.Handler.errorJSON(r);
						}
					},this);
					
				}
				break;
				
			default: // this is any other action besides remote
				var id = o['id'] || 0; // append the ID of the element if specified
				//var loc = 'index.php?a='+o.actions[itm.type]+'&id='+id;
				Ext.applyIf(itm.params || {},o.baseParams || {});
				var loc = 'index.php?id='+id+'&'+Ext.urlEncode(itm.params);
				location.href = loc;
				break;
		}
		return false;
	}
	
	/**
	 * Select the stay option.
	 * @param {Ext.menu.Item} itm The menu item checked.
	 * @param {Ext.EventObject} e The event object
	 */
	,checkStay: function(itm,e) {
		this.stay = itm.value;
	}
	
	/**
	 * Redirect the user to the correct stay value.
	 * @param {Object} o The options for the request.
	 * @param {Ext.Toolbar.Button} itm The action button pressed.
	 * @param {Object} res The XHR responseText.
	 */			
	,redirectStay: function(o,itm,res) {
		o = this.config;
		Ext.applyIf(itm.params || {},o.baseParams);
		var a = Ext.urlEncode(itm.params);
		switch (MODx.config.stay) {
			case 'new': // if user selected 'new', then always redirect
				location.href = 'index.php?a='+o.actions['new']+'&'+a;
				break;
			case 'stay':
				// if Continue Editing, then don't reload the page - just hide the Progress bar
				// unless the user is on a 'Create' page...if so, then redirect
				// to the proper Edit page
				if ((itm.process == 'create' || itm.process == 'duplicate' || itm.reload) && res.object.id != null) {
					location.href = 'index.php?a='+o.actions['edit']+'&id='+res.object.id+'&'+a;
				} else if (itm.process == 'delete') {
					location.href = 'index.php?a='+o.actions['cancel']+'&'+a;
				} else Ext.Msg.hide();
				break;
			case 'close': // redirect to the cancel action
				location.href = 'index.php?a='+o.actions['cancel']+'&'+a;
				break;
		}
	}
	
	/**
	 * Adds the stay menu to the toolbar.
	 */
	,loadStay: function() {
		this.add('-',this.getStayMenu(),' ',' ',' ');
	}
	
	/**
	 * Returns the stay menu.
	 */
	,getStayMenu: function() {
		return {
            xtype:'switch'
            ,id: 'stayMenu'
            ,activeItem: MODx.config.stay == 'new' ? 0 : 1 
            ,items: [{
                tooltip: _('stay_new')
                ,value: 'new'
                ,menuIndex: 0
                ,iconCls:'icon-list-new'
            },{
            	tooltip: _('stay')
                ,value: 'stay'
                ,menuIndex: 1
                ,iconCls:'icon-mark-active'
            },{
                tooltip: _('close')
                ,value: 'close'
                ,menuIndex: 2
                ,iconCls:'icon-mark-complete'
            }]
            ,listeners: {
                change: function(btn,itm){
                    MODx.config.stay = itm.value;
                }
                ,scope: this
                ,delay: 10 // delay gives user instant click feedback before filtering tasks
            }
        };
	}
	
	/**
	 * Refreshes specified tree's node
	 * @param {MODx.tree.Tree} tree The tree to refresh
	 * @param {String} node The ID of the node to refresh
	 * @param {Boolean} self If true, will refresh the node itself instead of parent. Defaults to false. 
	 */
	,refreshTreeNode: function(tree,node,self) {
		var t = parent.Ext.getCmp(tree);
        t.refreshNode(node,self || false);
        return false;
	}
});
Ext.reg('modx-actionbuttons',MODx.toolbar.ActionButtons);/**
 * Renders pages and abstracts component creation to xtypes.
 * 
 * @class MODx.Component
 * @extends Ext.Component
 * @constructor
 * @param {Object} config A configuration object.
 */
MODx.Component = function(config) {
    config = config || {};
    MODx.Component.superclass.constructor.call(this,config);
    this.config = config;
    
    this._loadForm();
    this._loadActionButtons();
    if (this.config.tabs) {
        this._loadTabs();
    }
    this._loadComponents();
};
Ext.extend(MODx.Component,Ext.Component,{
	/**
     * @var {Object} The form fields for this component.
     * @access protected
     */
    fields: {}
    
    /**
     * Loads the form for the component.
     * 
     * @access protected
     */
	,_loadForm: function() {
		if (!this.config.form) return false;
        this.form = new Ext.form.BasicForm(Ext.get(this.config.form),{ errorReader : MODx.util.JSONReader });
        
        if (this.config.fields) {
        	for (var i in this.config.fields) {
        	   var f = this.config.fields[i];
               if (f.xtype) {
                f = Ext.ComponentMgr.create(f);
               }
        	   this.fields[i] = f;
        	   this.form.add(f);
        	}
        }
        this.form.render();
    }
    
    /**
     * Loads ActionButtons for the component.
     * 
     * @access protected
     */
	,_loadActionButtons: function() {
		if (!this.config.buttons) return false;
		this.ab = new MODx.toolbar.ActionButtons({
            form: this.form || null
            ,formpanel: this.config.formpanel || null
            ,actions: this.config.actions || null
            ,id: this.config.id || null 
        });
        
        if (!this.config.buttons) this.config.buttons = [];        
        var l = this.config.buttons.length;
        for (var i=0; i<l; i++) {
        	var b = this.config.buttons[i];
        	if (b.refresh) {
        		b.onComplete = this.ab.refreshTreeNode.createDelegate(this,[b.refresh.tree,b.refresh.node,b.refresh.self || false])
        	}
        	this.ab.create(b);
        }
        
        if (this.config.loadStay) this.ab.loadStay();
	}
	
    /**
     * Loads MODx.Tabs
     * 
     * @access protected
     */
	,_loadTabs: function() {
		if (!this.config.tabs) return false;
        MODx.load({
            xtype: 'modx-tabs'
            ,renderTo: this.config.tabs_div || 'tabs_div'
            ,items: this.config.tabs
        });
	}
    
    /**
     * Loads all components by their xtype.
     * 
     * @access protected
     */
    ,_loadComponents: function() {
        if (!this.config.components) return false;
        var l = this.config.components.length;
        for (var i=0;i<l;i++) {
            Ext.ComponentMgr.create(this.config.components[i]);
        }
    }	
});
Ext.reg('modx-component',MODx.Component);/**
 * Automatically sends forms through AJAX calls, returns the result
 * (and parses any JS script within response), and if not TRUE, then
 * outputs that response to an 'errormsg' div. Also allows you to
 * specify the ?action= parameter in _GET, which utilitizes
 * PHP connectors to access their respective processor files.
 *  
 * @class MODx.form.Handler
 * @extends Ext.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-form-handler
 */
MODx.form.Handler = function(config) {
    config = config || {};
    MODx.form.Handler.superclass.constructor.call(this,config);
};
Ext.extend(MODx.form.Handler,Ext.Component,{
    fields: []
    /**
     * Sends the request to the connector. Use Ext.Ajax instead from now on.
     * @param {String,Object} fid The form ID
     * @param {String} a The action for the connector.
     * @param {Function} h An optional callback function.
     * @param {Object} The scope to execute the handler in
     * @deprecated
     */ 
    ,send: function(fid,a,h,scope) {
        var Frm = Ext.get(fid);
        this.unhighlightFields();
        
        Ext.Ajax.request({
            url: Frm.dom.action+'?action='+a
            ,params: Ext.Ajax.serializeForm(fid)
            ,method: 'post'
            ,scope: scope || this
            ,callback: h == null ? this.handle : h
        });
        return false;
    }
    
    /**
     * Default handler for Ajax responses.
     * @param {Object} o The options for the Ajax request.
     * @param {Object} s Whether or not the Ajax request succeeded.
     * @param {Object} r The xhr response.
     */
    ,handle: function(o,s,r) {
        r = Ext.decode(r.responseText);
        if (!r.success) {
            this.showError(r.message);
            return false;
        }
        return true;
    }
    
    ,highlightField: function(f) {
        if (f.id != 'undefined' && f.id != 'forEach' && f.id != '') {
            Ext.get(f.id).dom.style.border = '1px solid red';
            var ef = Ext.get(f.id+'_error');
            if (ef) ef.innerHTML = f.msg;
            this.fields.push(f.id);
        }
    }
    
    ,unhighlightFields: function() {
        for (var i=0;i<this.fields.length;i++) {
            Ext.get(this.fields[i]).dom.style.border = '';
            var ef = Ext.get(this.fields[i]+'_error');
            if (ef) ef.innerHTML = '';
        }
        this.fields = [];
    }
    
    ,errorJSON: function(e) {
        if (e == '') return this.showError(e);
        if (e.data != null) {
            for (var p=0;p<e.data.length;p++) {
                this.highlightField(e.data[p]);
            }
        }

        this.showError(e.message);
        return false;
    }
    
    ,errorExt: function(r,frm) {
        this.unhighlightFields();
        if (r.errors != null && frm) {
            frm.markInvalid(r.errors);
        }
        if (r.message != undefined && r.message != '') { 
            this.showError(r.message);
        } else {
            MODx.msg.hide();    
        }
        return false;
    }
    
    ,unescapeJson: function(obj) {
        for (var prop in obj) {
            if ($type(obj[prop]) == 'object')
                for (var p in obj[prop]) obj[prop][p] = unescape(obj[prop][p]);
            else if ($type(obj[prop]) == 'string')
                obj[prop] = unescape(obj[prop]);
            else if ($type(obj[prop]) == 'array')
                for (var i = 0; i < obj[prop].length; i++)
                    for (var p in obj[prop][i]) obj[prop][i] = this.unescapeJson(obj[prop][i]);
        }
        return obj;
    }
    
    ,showError: function(e) {
        e == ''
            ? MODx.msg.hide()
            : MODx.msg.alert(_('error'),e,function() { });
    }
    
    ,closeError: function() {
        MODx.msg.hide();
    }
});
Ext.reg('modx-form-handler',MODx.form.Handler);/**
 * Abstract class for Ext.DataView creation in MODx
 * 
 * @class MODx.DataView
 * @extends Ext.DataView
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-dataview
 */
MODx.DataView = function(config) {
    config = config || {};
    this._loadStore(config);
    
    Ext.applyIf(config.listeners || {},{
        'loadexception': {fn:this.onLoadException, scope: this}
        ,'beforeselect': {fn:function(view){ return view.store.getRange().length > 0;}}
        ,'contextmenu': {fn:this._showContextMenu, scope: this}
    });
    Ext.applyIf(config,{
        store: this.store
        ,singleSelect: true
        ,overClass: 'x-view-over'
        ,itemSelector: 'div.thumb-wrap'
        ,emptyText: '<div style="padding:10px;">'+_('file_err_filter')+'</div>'
    });
    MODx.DataView.superclass.constructor.call(this,config);
    this.config = config;
    this.cm = new Ext.menu.Menu(Ext.id());
};
Ext.extend(MODx.DataView,Ext.DataView,{
    lookup: {}
    
    ,onLoadException: function(){
        this.getEl().update('<div style="padding:10px;">'+_('data_err_load')+'</div>'); 
    }
    
    /**
     * Add context menu items to the dataview.
     * @param {Object, Array} items Either an Object config or array of Object configs.  
     */
    ,_addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            var options = a[i];
            
            if (options == '-') {
                this.cm.add('-');
                continue;
            };
            if (options.handler) {
                var h = eval(options.handler);
            } else {
                var h = function(itm,e) {
                    var o = itm.options;
                    var id = this.cm.activeNode.id.split('_'); id = id[1];
                    var w = Ext.get('modx_content');
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e == 'yes') {
                                var a = Ext.urlEncode(o.params || {action: o.action});
                                var s = 'index.php?id='+id+'&'+a;
                                if (w == null) {
                                    location.href = s;
                                } else w.dom.src = s
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params);
                        var s = 'index.php?id='+id+'&'+a;
                        if (w == null) {
                            location.href = s;
                        } else w.dom.src = s;
                    }
                };
            };
            this.cm.add({
                id: options.id
                ,text: options.text
                ,scope: this
                ,options: options
                ,handler: h
                //,cls: (options.header ? 'x-menu-item-active' : '')
            });
        }
    }
    
    
    ,_loadStore: function(config) {
        this.store = new Ext.data.JsonStore({
            url: config.url
            ,baseParams: config.baseParams || { action: 'getList' }
            ,root: config.root || 'results'
            ,fields: config.fields
            ,listeners: {
                'load': {fn:function(){ this.select(0); }, scope:this, single:true}
            }
        });
        this.store.load();
    }
    
    ,_showContextMenu: function(v,i,n,e) {
        e.preventDefault();
        var data = this.lookup[n.id];
        var m = this.cm;
        m.removeAll();
        if (data.menu) {
            this._addContextMenuItem(data.menu);
            m.show(n,'t?');
        }
        m.activeNode = n;
    }
});
Ext.reg('modx-dataview',MODx.DataView);
Ext.EventManager = function(){
    var docReadyEvent, docReadyProcId, docReadyState = false;
    var resizeEvent, resizeTask, textEvent, textSize;
    var E = Ext.lib.Event;
    var D = Ext.lib.Dom;
    // fix parser confusion
    var xname = 'Ex' + 't';

    var elHash = {};

    var addListener = function(el, ename, fn, wrap, scope){
        var id = Ext.id(el);
        if(!elHash[id]){
            elHash[id] = {};
        }
        var es = elHash[id];
        if(!es[ename]){
            es[ename] = [];
        }
        var ls = es[ename];
        ls.push({
            id: id,
            ename: ename,
            fn: fn,
            wrap: wrap,
            scope: scope
        });

         E.on(el, ename, wrap);

        if(ename == "mousewheel" && el.addEventListener){ // workaround for jQuery
            el.addEventListener("DOMMouseScroll", wrap, false);
            E.on(window, 'unload', function(){
                el.removeEventListener("DOMMouseScroll", wrap, false);
            });
        }
        if(ename == "mousedown" && el == document){ // fix stopped mousedowns on the document
            Ext.EventManager.stoppedMouseDownEvent.addListener(wrap);
        }
    }

    var removeListener = function(el, ename, fn, scope){
        el = Ext.getDom(el);
        var id = Ext.id(el), es = elHash[id], wrap;
        if(es){
            var ls = es[ename], l;
            if(ls){
                for(var i = 0, len = ls.length; i < len; i++){
                    l = ls[i];
                    if(l.fn == fn && (!scope || l.scope == scope)){
                        wrap = l.wrap;
                        E.un(el, ename, wrap);
                        ls.splice(i, 1);
                        break;
                    }
                }
            }
        }
        if(ename == "mousewheel" && el.addEventListener && wrap){
            el.removeEventListener("DOMMouseScroll", wrap, false);
        }
        if(ename == "mousedown" && el == document && wrap){ // fix stopped mousedowns on the document
            Ext.EventManager.stoppedMouseDownEvent.removeListener(wrap);
        }
    }

    var removeAll = function(el){
        el = Ext.getDom(el);
        var id = Ext.id(el), es = elHash[id], ls;
        if(es){
            for(var ename in es){
                if(es.hasOwnProperty(ename)){
                    ls = es[ename];
                    for(var i = 0, len = ls.length; i < len; i++){
                        E.un(el, ename, ls[i].wrap);
                        ls[i] = null;
                    }
                }
                es[ename] = null;
            }
            delete elHash[id];
        }
    }

    var fireDocReady = function(){
        if(!docReadyState){
            docReadyState = true;
            Ext.isReady = true;
            if(docReadyProcId){
                clearInterval(docReadyProcId);
            }
            if(Ext.isGecko || Ext.isOpera) {
                document.removeEventListener("DOMContentLoaded", fireDocReady, false);
            }
            if(Ext.isIE){
                var defer = document.getElementById("ie-deferred-loader");
                if(defer){
                    defer.onreadystatechange = null;
                    defer.parentNode.removeChild(defer);
                }
            }
            if(docReadyEvent){
                docReadyEvent.fire();
                docReadyEvent.clearListeners();
            }
        }
    };

    var initDocReady = function(){
        docReadyEvent = new Ext.util.Event();
        if(Ext.isGecko || Ext.isOpera) {
            document.addEventListener("DOMContentLoaded", fireDocReady, false);
        }else if(Ext.isIE){
            document.write("<s"+'cript id="ie-deferred-loader" defer="defer" src="/'+'/:"></s'+"cript>");
            var defer = document.getElementById("ie-deferred-loader");
            defer.onreadystatechange = function(){
                if(this.readyState == "complete"){
                    fireDocReady();
                }
            };
        }else if(Ext.isSafari){
            docReadyProcId = setInterval(function(){
                var rs = document.readyState;
                if(rs == "complete") {
                    fireDocReady();
                 }
            }, 10);
        }
        
        E.on(window, "load", fireDocReady);
    };

    var createBuffered = function(h, o){
        var task = new Ext.util.DelayedTask(h);
        return function(e){
            
            e = new Ext.EventObjectImpl(e);
            task.delay(o.buffer, h, null, [e]);
        };
    };

    var createSingle = function(h, el, ename, fn){
        return function(e){
            Ext.EventManager.removeListener(el, ename, fn);
            h(e);
        };
    };

    var createDelayed = function(h, o){
        return function(e){
            
            e = new Ext.EventObjectImpl(e);
            setTimeout(function(){
                h(e);
            }, o.delay || 10);
        };
    };

    var listen = function(element, ename, opt, fn, scope){
        var o = (!opt || typeof opt == "boolean") ? {} : opt;
        fn = fn || o.fn; scope = scope || o.scope;
        var el = Ext.getDom(element);
        if(!el){
            throw "Error listening for \"" + ename + '\". Element "' + element + '" doesn\'t exist.';
        }
        var h = function(e){
            e = Ext.EventObject.setEvent(e);
            var t;
            if(o.delegate){
                t = e.getTarget(o.delegate, el);
                if(!t){
                    return;
                }
            }else{
                t = e.target;
            }
            if(o.stopEvent === true){
                e.stopEvent();
            }
            if(o.preventDefault === true){
               e.preventDefault();
            }
            if(o.stopPropagation === true){
                e.stopPropagation();
            }

            if(o.normalized === false){
                e = e.browserEvent;
            }

            fn.call(scope || el, e, t, o);
        };
        if(o.delay){
            h = createDelayed(h, o);
        }
        if(o.single){
            h = createSingle(h, el, ename, fn);
        }
        if(o.buffer){
            h = createBuffered(h, o);
        }
        fn._handlers = fn._handlers || [];
        fn._handlers.push([Ext.id(el), ename, h]);

        E.on(el, ename, h);
        if(ename == "mousewheel" && el.addEventListener){ 
            el.addEventListener("DOMMouseScroll", h, false);
            E.on(window, 'unload', function(){
                el.removeEventListener("DOMMouseScroll", h, false);
            });
        }
        if(ename == "mousedown" && el == document){ 
            Ext.EventManager.stoppedMouseDownEvent.addListener(h);
        }
        return h;
    };

    var stopListening = function(el, ename, fn){
        var id = Ext.id(el), hds = fn._handlers, hd = fn;
        if(hds){
            for(var i = 0, len = hds.length; i < len; i++){
                var h = hds[i];
                if(h[0] == id && h[1] == ename){
                    hd = h[2];
                    hds.splice(i, 1);
                    break;
                }
            }
        }
        E.un(el, ename, hd);
        el = Ext.getDom(el);
        if(ename == "mousewheel" && el.addEventListener){
            el.removeEventListener("DOMMouseScroll", hd, false);
        }
        if(ename == "mousedown" && el == document){ 
            Ext.EventManager.stoppedMouseDownEvent.removeListener(hd);
        }
    };

    var propRe = /^(?:scope|delay|buffer|single|stopEvent|preventDefault|stopPropagation|normalized|args|delegate)$/;
    var pub = {

    
        addListener : function(element, eventName, fn, scope, options){
            if(typeof eventName == "object"){
                var o = eventName;
                for(var e in o){
                    if(propRe.test(e)){
                        continue;
                    }
                    if(typeof o[e] == "function"){
                        
                        listen(element, e, o, o[e], o.scope);
                    }else{
                        
                        listen(element, e, o[e]);
                    }
                }
                return;
            }
            return listen(element, eventName, options, fn, scope);
        },

        
        removeListener : function(element, eventName, fn){
            return stopListening(element, eventName, fn);
        },

        removeAll : function(element){
            return removeAll(element);
        },
        
        onDocumentReady : function(fn, scope, options){
            if(docReadyState){ 
                docReadyEvent.addListener(fn, scope, options);
                docReadyEvent.fire();
                docReadyEvent.clearListeners();
                return;
            }
            if(!docReadyEvent){
                initDocReady();
            }
            options = options || {};
            if(!options.delay) {
				options.delay = 1;
			}
            docReadyEvent.addListener(fn, scope, options);
        },

        
        onWindowResize : function(fn, scope, options){
            if(!resizeEvent){
                resizeEvent = new Ext.util.Event();
                resizeTask = new Ext.util.DelayedTask(function(){
                    resizeEvent.fire(D.getViewWidth(), D.getViewHeight());
                });
                E.on(window, "resize", this.fireWindowResize, this);
            }
            resizeEvent.addListener(fn, scope, options);
        },

        
        fireWindowResize : function(){
            if(resizeEvent){
                if((Ext.isIE||Ext.isAir) && resizeTask){
                    resizeTask.delay(50);
                }else{
                    resizeEvent.fire(D.getViewWidth(), D.getViewHeight());
                }
            }
        },

        
        onTextResize : function(fn, scope, options){
            if(!textEvent){
                textEvent = new Ext.util.Event();
                var textEl = new Ext.Element(document.createElement('div'));
                textEl.dom.className = 'x-text-resize';
                textEl.dom.innerHTML = 'X';
                textEl.appendTo(document.body);
                textSize = textEl.dom.offsetHeight;
                setInterval(function(){
                    if(textEl.dom.offsetHeight != textSize){
                        textEvent.fire(textSize, textSize = textEl.dom.offsetHeight);
                    }
                }, this.textResizeInterval);
            }
            textEvent.addListener(fn, scope, options);
        },

        
        removeResizeListener : function(fn, scope){
            if(resizeEvent){
                resizeEvent.removeListener(fn, scope);
            }
        },

        
        fireResize : function(){
            if(resizeEvent){
                resizeEvent.fire(D.getViewWidth(), D.getViewHeight());
            }
        },
        
        ieDeferSrc : false,
        
        textResizeInterval : 50
    };
     
    pub.on = pub.addListener;
    
    pub.un = pub.removeListener;

    pub.stoppedMouseDownEvent = new Ext.util.Event();
    return pub;
}();

Ext.onReady = Ext.EventManager.onDocumentReady;

Ext.onReady(function(){
    var bd = Ext.getBody();
    if(!bd){ return; }

    var cls = [
            Ext.isIE ? "ext-ie " + (Ext.isIE6 ? 'ext-ie6' : 'ext-ie7')
            : Ext.isGecko ? "ext-gecko"
            : Ext.isOpera ? "ext-opera"
            : Ext.isSafari ? "ext-safari" : ""];

    if(Ext.isMac){
        cls.push("ext-mac");
    }
    if(Ext.isLinux){
        cls.push("ext-linux");
    }
    if(Ext.isBorderBox){
        cls.push('ext-border-box');
    }
    if(Ext.isStrict){ 
        var p = bd.dom.parentNode;
        if(p){
            p.className += ' ext-strict';
        }
    }
    bd.addClass(cls.join(' '));
});Ext.namespace('MODx.form');
/**
 * Automatically sends forms through AJAX calls, returns the result
 * (and parses any JS script within response), and if not TRUE, then
 * outputs that response to an 'errormsg' div. Also allows you to
 * specify the ?action= parameter in _GET, which utilitizes
 * PHP connectors to access their respective processor files.
 *  
 * @class MODx.form.Handler
 * @extends Ext.Component
 * @constructor
 * @xtype modx-form-handler
 */
MODx.form.Handler = function(config) {
	config = config || {};
    MODx.form.Handler.superclass.constructor.call(this,config);
};
Ext.extend(MODx.form.Handler,Ext.Component,{
	fields: []
	/**
	 * Sends the request to the connector. Use Ext.Ajax instead from now on.
	 * @param {String,Object} fid The form ID
	 * @param {String} a The action for the connector.
	 * @param {Function} h An optional callback function.
	 * @param {Object} The scope to execute the handler in
	 * @deprecated
	 */	
	,send: function(fid,a,h,scope) {
		var Frm = Ext.get(fid);
		this.unhighlightFields();
		
		Ext.Ajax.request({
			url: Frm.dom.action+'?action='+a
			,params: Ext.Ajax.serializeForm(fid)
			,method: 'post'
			,scope: scope || this
			,callback: h == null ? this.handle : h
		});
		return false;
	}
	
	/**
	 * Default handler for Ajax responses.
	 * @param {Object} o The options for the Ajax request.
	 * @param {Object} s Whether or not the Ajax request succeeded.
	 * @param {Object} r The xhr response.
	 */
	,handle: function(o,s,r) {
		r = Ext.decode(r.responseText);
		if (!r.success) {
			this.showError(r.message);
			return false;
		}
		return true;
	}
	
	,highlightField: function(f) {
		if (f.id != 'undefined' && f.id != 'forEach' && f.id != '') {
			Ext.get(f.id).dom.style.border = '1px solid red';
			var ef = Ext.get(f.id+'_error');
			if (ef) ef.innerHTML = f.msg;
			this.fields.push(f.id);
		}
	}
	
	,unhighlightFields: function() {
		for (var i=0;i<this.fields.length;i++) {
			Ext.get(this.fields[i]).dom.style.border = '';
			var ef = Ext.get(this.fields[i]+'_error');
			if (ef) ef.innerHTML = '';
		}
		this.fields = [];
	}
	
	,errorJSON: function(e) {
		if (e == '') return this.showError(e);
		if (e.data != null) {
			for (var p=0;p<e.data.length;p++) {
				this.highlightField(e.data[p]);
			}
		}

		this.showError(e.message);
		return false;
	}
	
	,errorExt: function(r,frm) {
		this.unhighlightFields();
		if (r.errors != null && frm) {
			frm.markInvalid(r.errors);
		}
		if (r.message != undefined && r.message != '') { 
			this.showError(r.message);
		} else {
			MODx.msg.hide();	
		}
		return false;
	}
	
	,unescapeJson: function(obj) {
		for (var prop in obj) {
			if ($type(obj[prop]) == 'object')
				for (var p in obj[prop]) obj[prop][p] = unescape(obj[prop][p]);
			else if ($type(obj[prop]) == 'string')
				obj[prop] = unescape(obj[prop]);
			else if ($type(obj[prop]) == 'array')
				for (var i = 0; i < obj[prop].length; i++)
					for (var p in obj[prop][i]) obj[prop][i] = this.unescapeJson(obj[prop][i]);
		}
		return obj;
	}
	
	,showError: function(e) {
		e == ''
			? MODx.msg.hide()
			: MODx.msg.alert(_('error'),e,function() { });
	}
	
	,closeError: function() {
		MODx.msg.hide();
	}
});
Ext.reg('modx-form-handler',MODx.form.Handler);/**
 * modHExt extensions
 * 
 * Generates Ext-styled forms through HTML namespaced attributes
 */
var _hourfields,_minfields,_ampmfields,_datefields,_textfields,_comboboxes,_textareas,_radios,_checkboxes,_hiddens;
Ext.onReady(function() {
	// auto-render form elements to Ext
	var dh = Ext.DomHelper;	
	
	// hourfields
	_hourfields = {};
	var hourStore = new Ext.data.SimpleStore({
		fields: ['hour']
		,data: [[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12]]
	});
	var els = Ext.get(Ext.query('select.hourfield'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var d = new Ext.form.ComboBox({
			el: el.dom
			,store: hourStore
			,displayField: 'hour'
			,mode: 'local'
			,triggerAction: 'all'
			,value: el.dom.value || 1
			,forceSelection: true
			,selectOnFocus: true
			,editable: false
			,hiddenName: el.dom.name
			,typeAhead: false
			,width: 50
			,validateOnBlur: false
			,transform: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc != undefined) d.on('change',oc);
		
		d.render(span);
		_hourfields[el.dom.id] = d;
	});	
	
	// minutefields
	_minfields = {};
	var minStore = new Ext.data.SimpleStore({
		fields: ['min']
		,data: [['00'],['15'],['30'],['45']]
	});
	var els = Ext.get(Ext.query('select.minutefield'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var d = new Ext.form.ComboBox({
			el: el.dom
			,store: minStore
			,displayField: 'min'
			,mode: 'local'
			,triggerAction: 'all'
			,rowHeight: false
			,value: el.dom.value || '00'
			,forceSelection: true
			,editable: false
			,transform: el.dom.id
			,hiddenName: el.dom.name
			,typeAhead: false
			,width: 50
		});
		var oc = el.dom.onchange;
		if (oc && oc != undefined) d.on('change',oc);
		
		d.render(span);
		_minfields[el.dom.id] = d;
	});	
	
	// ampmfields
	_ampmfields = {};
	var ampmStore = new Ext.data.SimpleStore({
		fields: ['min']
		,data: [['am'],['pm']]
	});
	var els = Ext.get(Ext.query('select.ampmfield'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var d = new Ext.form.ComboBox({
			el: el.dom
			,store: ampmStore
			,displayField: 'min'
			,mode: 'local'
			,triggerAction: 'all'
			,rowHeight: false
			,value: el.dom.value || 'am'
			,forceSelection: true
			,editable: false
			,transform: el.dom.id
			,hiddenName: el.dom.name
			,typeAhead: false
			,width: 50
		});
		var oc = el.dom.onchange;
		if (oc && oc != undefined) d.on('change',oc);
		
		d.render(span);
		_ampmfields[el.dom.id] = d;
	});	
	
	// textfields
	_textfields = {};
	var els = Ext.get(Ext.query('input.textfield'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var allb = el.getAttributeNS('modx','allowblank');
		var d = new Ext.form.TextField({
			width: el.getAttributeNS('modx','width') || 300
			,maxLength: el.getAttributeNS('modx','maxlength')
			,inputType: el.getAttributeNS('modx','inputtype') || 'text'
			,allowBlank: allb && allb == false ? false : true
			,applyTo: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc != undefined) d.on('change',oc);
		
		_textfields[el.dom.id] = d;
	});
	
	// comboboxes
	_comboboxes = {};
	var els = Ext.get(Ext.query('select.combobox'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var ed = el.getAttributeNS('modx','editable');
		var fs = el.getAttributeNS('modx','forceselection');
		var d = new Ext.form.ComboBox({
			el: el.dom
			,value: el.dom.value
			,forceSelection: fs && fs == false ? false : true
			,typeAhead: el.getAttributeNS('modx','typeahead') ? true : false
			,editable: ed && ed == false ? false : true
			,triggerAction: 'all'
			,transform: el.dom.id
			,hiddenName: el.dom.name
			,width: el.getAttributeNS('modx','width')
            ,listWidth: el.getAttributeNS('modx','listwidth') || 300
		});
		var oc = el.dom.onchange;
		if (oc && oc != undefined) {
			d.on('blur',oc);
		}
		
		d.render(span);
		_comboboxes[el.dom.id] = d;
	});	
	
	// textareas
	_textareas = {};
	var els = Ext.get(Ext.query('textarea.textarea'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var d = new Ext.form.TextArea({
			grow: el.getAttributeNS('modx','grow') ? true : false
			,width: el.getAttributeNS('modx','width')
			,applyTo: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc != undefined) d.on('change',oc);
		
		_textareas[el.dom.id] = d;
	});
	
	// datefields
	_datefields = {};
	var els = Ext.get(Ext.query('input.datefield'));
	els.each(function(el){
		var span = dh.insertBefore(el, {tag:'span'});
		var f = el.getAttributeNS('modx','format');
		var d = new Ext.form.DateField({
			el: el.dom
			,value: el.dom.value
			,allowBlank: el.getAttributeNS('modx','allowblank') ? true : false
			,format: f && f != undefined ? f : 'd-m-Y H:i:s'
		});
		var oc = el.dom.onchange;
		if (oc && oc != undefined) d.on('change',oc);
		
		d.render(span);
		_datefields[el.dom.id] = d;
	});
	
	
	// radios
	_radios = {};
	var els = Ext.get(Ext.query('input.radio'));
	els.each(function(el){
		var d = new Ext.form.Radio({
			name: el.dom.name
			,value: el.dom.value
			,boxLabel: el.dom.title
			,checked: el.dom.checked
			,disabled: el.dom.disabled ? true : false
			,inputType: 'radio'
			,applyTo: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc != undefined) d.on('change',oc);
		
		_radios[el.dom.id] = d;
	});
	
	// checkboxes
	_checkboxes = {};
	var els = Ext.get(Ext.query('input.checkbox'));
	els.each(function(el){
		var d = new Ext.form.Radio({
			name: el.dom.name
			,value: el.dom.value
			,boxLabel: el.dom.title
			,checked: el.dom.checked
			,disabled: el.dom.disabled ? true : false
			,inputType: 'checkbox'
			,applyTo: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc != undefined) d.on('change',oc);
		
		_checkboxes[el.dom.id] = d;
	});
	
	// hiddens
	_hiddens = {};
	var els = Ext.get(Ext.query('input.hidden'));
	els.each(function(el){
		var d = new Ext.form.Field({
			name: el.dom.name
			,value: el.dom.value
			,inputType: 'hidden'
			,width: 0
			,labelSeparator: ''
			,applyTo: el.dom.id
		});
		_hiddens[el.dom.id] = d;
	});					 
});/*
 * Ext JS Library 2.1
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.Spotlight = function(config){
    Ext.apply(this, config);
}
Ext.Spotlight.prototype = {
    active : false,
    animate : true,
    animated : false,
    duration: .25,
    easing:'easeNone',

    createElements : function(){
        var bd = Ext.getBody();

        this.right = bd.createChild({cls:'x-spotlight'});
        this.left = bd.createChild({cls:'x-spotlight'});
        this.top = bd.createChild({cls:'x-spotlight'});
        this.bottom = bd.createChild({cls:'x-spotlight'});

        this.all = new Ext.CompositeElement([this.right, this.left, this.top, this.bottom]);
    },

    show : function(el, callback, scope){
        if(this.animated){
            this.show.defer(50, this, [el, callback, scope]);
            return;
        }
        this.el = Ext.get(el);
        if(!this.right){
            this.createElements();
        }
        if(!this.active){
            this.all.setDisplayed('');
            this.applyBounds(true, false);
            this.active = true;
            Ext.EventManager.onWindowResize(this.syncSize, this);
            this.applyBounds(false, this.animate, false, callback, scope);
        }else{
            this.applyBounds(false, false, false, callback, scope); // all these booleans look hideous
        }
    },

    hide : function(callback, scope){
        if(this.animated){
            this.hide.defer(50, this, [callback, scope]);
            return;
        }
        Ext.EventManager.removeResizeListener(this.syncSize, this);
        this.applyBounds(true, this.animate, true, callback, scope);
    },

    doHide : function(){
        this.active = false;
        this.all.setDisplayed(false);
    },

    syncSize : function(){
        this.applyBounds(false, false);
    },

    applyBounds : function(basePts, anim, doHide, callback, scope){

        var rg = this.el.getRegion();

        var dw = Ext.lib.Dom.getViewWidth(true);
        var dh = Ext.lib.Dom.getViewHeight(true);

        var c = 0, cb = false;
        if(anim){
            cb = {
                callback: function(){
                    c++;
                    if(c == 4){
                        this.animated = false;
                        if(doHide){
                            this.doHide();
                        }
                        Ext.callback(callback, scope, [this]);
                    }
                },
                scope: this,
                duration: this.duration,
                easing: this.easing
            };
            this.animated = true;
        }

        this.right.setBounds(
                rg.right,
                basePts ? dh : rg.top,
                dw - rg.right,
                basePts ? 0 : (dh - rg.top),
                cb);

        this.left.setBounds(
                0,
                0,
                rg.left,
                basePts ? 0 : rg.bottom,
                cb);

        this.top.setBounds(
                basePts ? dw : rg.left,
                0,
                basePts ? 0 : dw - rg.left,
                rg.top,
                cb);

        this.bottom.setBounds(
                0,
                rg.bottom,
                basePts ? 0 : rg.right,
                dh - rg.bottom,
                cb);

        if(!anim){
            if(doHide){
                this.doHide();
            }
            if(callback){
                Ext.callback(callback, scope, [this]);
            }
        }
    },

    destroy : function(){
        this.doHide();
        Ext.destroy(
                this.right,
                this.left,
                this.top,
                this.bottom);
        delete this.el;
        delete this.all;
    }
};/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.SwitchButton = Ext.extend(Ext.Component, {
	initComponent : function(){
		Ext.SwitchButton.superclass.initComponent.call(this);
		
		var mc = new Ext.util.MixedCollection();
		mc.addAll(this.items);
		this.items = mc;
		
		this.addEvents('change');
		
		if(this.handler){
			this.on('change', this.handler, this.scope || this);
		}
	},
	
	onRender : function(ct, position){
		
		var el = document.createElement('table');
		el.cellSpacing = 0;
		el.className = 'x-rbtn';
		el.id = this.id;
		
		var row = document.createElement('tr');
		el.appendChild(row);
		
		var count = this.items.length;
		var last = count - 1;
		this.activeItem = this.items.get(this.activeItem);
		
		for(var i = 0; i < count; i++){
			var item = this.items.itemAt(i);
			
			var cell = row.appendChild(document.createElement('td'));
			cell.id = this.id + '-rbi-' + i;
			
			var cls = i == 0 ? 'x-rbtn-first' : (i == last ? 'x-rbtn-last' : 'x-rbtn-item');
			item.baseCls = cls;
			
			if(this.activeItem == item){
				cls += '-active';
			}
			cell.className = cls;
			
			var button = document.createElement('button');
			button.innerHTML = '&#160;';
			button.className = item.iconCls;
			button.qtip = item.tooltip;
			
			cell.appendChild(button);
			
			item.cell = cell;
		}
		
		this.el = Ext.get(ct.dom.appendChild(el));
		
		this.el.on('click', this.onClick, this);
	},
	
	getActiveItem : function(){
		return this.activeItem;
	},
	
	setActiveItem : function(item){
		if(typeof item != 'object' && item !== null){
			item = this.items.get(item);
		}
		var current = this.getActiveItem();
		if(item != current){
			if(current){
				Ext.fly(current.cell).removeClass(current.baseCls + '-active');
			}
			if(item) {
				Ext.fly(item.cell).addClass(item.baseCls + '-active');
			}
			this.activeItem = item;
			this.fireEvent('change', this, item);
		}
		return item;
	},
	
	onClick : function(e){
		var target = e.getTarget('td', 2);
		if(!this.disabled && target){
			this.setActiveItem(parseInt(target.id.split('-rbi-')[1], 10));
		}
	}
});

Ext.reg('switch', Ext.SwitchButton);
Ext.namespace('MODx.util.Progress');
/**
 * Shows a Loading display when ajax calls are happening
 * 
 * @class MODx.util.LoadingBox
 * @extends Ext.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-loading-box
 */
MODx.util.LoadingBox = function(config) {
	config = config || {};
		
    Ext.Ajax.on('beforerequest',this.show,this);
    Ext.Ajax.on('requestcomplete',this.hide,this);
    Ext.Ajax.on('requestexception',this.hide,this);
};
Ext.override(MODx.util.LoadingBox,{
    enabled: true
    ,hide: function() {
        if (this.enabled) Ext.Msg.hide();
    }
    ,show: function() {
        if (this.enabled) {
            Ext.Msg.show({
                title: _('please_wait')
                ,msg: _('loading')
                ,width:240
                ,progress:true
                ,closable:false
            });
        }
    }
    ,disable: function() { this.enabled = false; }
    ,enable: function() { this.enabled = true; }
});
Ext.reg('modx-loading-box',MODx.util.LoadingBox);

/**
 * A JSON Reader specific to MODExt
 * 
 * @class MODx.util.JSONReader
 * @extends Ext.util.JSONReader
 * @param {Object} config An object of configuration properties
 * @xtype modx-json-reader
 */
MODx.util.JSONReader = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        successProperty:'success'
        ,totalProperty: 'total'
        ,root: 'data'
    });
    MODx.util.JSONReader = new Ext.data.JsonReader(config,['id','msg']);
};
Ext.reg('modx-json-reader',MODx.util.JSONReader);

/**
 * @class MODx.util.Progress 
 */
MODx.util.Progress = {
    id: 0
    ,time: function(v,id,msg) {
        msg = msg || _('saving');
        if (MODx.util.Progress.id == id && v < 11) 
            Ext.MessageBox.updateProgress(v/10,msg);
    }
    ,reset: function() {
        MODx.util.Progress.id = MODx.util.Progress.id + 1;
    }
};



/** 
 * Static Textfield
 */
MODx.StaticTextField = Ext.extend(Ext.form.TextField, {
    fieldClass: 'x-static-text-field',

    onRender: function() {
        this.readOnly = true;
        this.disabled = !this.initialConfig.submitValue;
        MODx.StaticTextField.superclass.onRender.apply(this, arguments);
    }
});
Ext.reg('statictextfield',MODx.StaticTextField);

/** 
 * Static Boolean
 */
MODx.StaticBoolean = Ext.extend(Ext.form.TextField, {
    fieldClass: 'x-static-text-field',

    onRender: function(tf) {
        this.readOnly = true;
        this.disabled = !this.initialConfig.submitValue;
        MODx.StaticBoolean.superclass.onRender.apply(this, arguments);
        this.on('change',this.onChange,this);
    }
    
    ,setValue: function(v) {
        if (v == 1) {
            this.addClass('green');
            v = _('yes');
        } else {
            this.addClass('red');
            v = _('no');
        }
        MODx.StaticBoolean.superclass.setValue.apply(this, arguments);
    }
});
Ext.reg('staticboolean',MODx.StaticBoolean);


/****************************************************************************
 *    Ext-specific overrides/extensions                                     *
 ****************************************************************************/

function $(el){
    if (!el) return null;
    var type = Ext.type(el);
    if (type == 'string'){
        el = document.getElementById(el);
        type = (el) ? 'element' : false;
    }
    if (type != 'element') return null;
    return el;
};


Array.prototype.in_array = function(p_val) {
    for(var i = 0, l = this.length; i < l; i++) {
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
};


Ext.form.setCheckboxValues = function(form,id,mask) {
    var f, n=0;
    while ((f = form.findField(id+n)) != null) {
        f.setValue((mask & (1<<n))?'true':'false');
        n++;
    } 
};

Ext.form.getCheckboxMask = function(cbgroup) {
    var mask='';
    if (typeof(cbgroup) != "undefined") {
        if ((typeof(cbgroup)=="string"))
            mask = cbgroup+'';
        else
            for(var i = 0, len = cbgroup.length; i < len; i++)
                mask += (mask != '' ? ',' : '')+(cbgroup[i]-0);
    }
    return mask;
};


Ext.form.BasicForm.prototype.append = function() {
  // Create a new layout object
  var layout = new Ext.form.Layout();
  // Keep track of added fields that are form fields (isFormField)
  var fields = [];
  // Add all the fields on to the layout stack
  layout.stack.push.apply(layout.stack, arguments);

  // Add only those fields that are form fields to the 'fields' array
  for(var i = 0; i < arguments.length; i++) {
    if(arguments[i].isFormField) {
      fields.push(arguments[i]);
    }
  }

  // Render the layout
  layout.render(this.el);

  // If we found form fields add them to the form's items collection and render the
  // fields into their containers created by the layout
  if(fields.length > 0) {
    this.items.addAll(fields);

    // Render each field
    for(var i = 0; i < fields.length; i++) {
      fields[i].render('x-form-el-' + fields[i].id);
    }
  }

  return this;
};


Ext.form.AMPMField = function(id,v) {
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['ampm'],
            data: [['am'],['pm']]
        }),
        displayField: 'ampm',
        hiddenName: id,
        mode: 'local',
        editable: false,
        forceSelection: true,
        triggerAction: 'all',
        width: 60,
        value: v || 'am'
    });
};

Ext.form.HourField = function(id,name,v){
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['hour'],
            data: [[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12]]
        }),
        displayField: 'hour',
        mode: 'local',
        triggerAction: 'all',
        width: 60,
        forceSelection: true,
        rowHeight: false,
        editable: false,
        value: v || 1,
        transform: id
    }); 
};


Ext.override(Ext.tree.TreeNodeUI,{
    hasClass : function(className){
        var el = Ext.fly(this.elNode);
        return className && (' '+el.dom.className+' ').indexOf(' '+className+' ') != -1;
    }
});


// allows for messages in JSON responses
Ext.override(Ext.form.Action.Submit,{         
    handleResponse : function(response){        
        var m = Ext.decode(response.responseText); // shaun 7/11/07
        if(this.form.errorReader){
            var rs = this.form.errorReader.read(response);
            var errors = [];
            if(rs.records){
                for(var i = 0, len = rs.records.length; i < len; i++) {
                    var r = rs.records[i];
                    errors[i] = r.data;
                }
            }
            if(errors.length < 1){
                errors = null;
            }
            return {
                success : rs.success,
                message : m.message, // shaun 7/11/07
                object : m.object, // shaun 7/18/07
                errors : errors
            };
        }
        return Ext.decode(response.responseText);
    }
});





/**
 * @class Ext.form.ColorField
 * @extends Ext.form.TriggerField
 * Provides a very simple color form field with a ColorMenu dropdown.
 * Values are stored as a six-character hex value without the '#'.
 * I.e. 'ffffff'
 * @constructor
 * Create a new ColorField
 * <br />Example:
 * <pre><code>
var cf = new Ext.form.ColorField({
    fieldLabel: 'Color',
    hiddenName:'pref_sales',
    showHexValue:true
});
</code></pre>
 * @param {Object} config
 */
Ext.form.ColorField = function(config){
    Ext.form.ColorField.superclass.constructor.call(this, config);
    this.on('render', this.handleRender);
};

Ext.extend(Ext.form.ColorField, Ext.form.TriggerField,  {
    /**
     * @cfg {Boolean} showHexValue
     * True to display the HTML Hexidecimal Color Value in the field
     * so it is manually editable.
     */
    showHexValue : true,
    
    /**
     * @cfg {String} triggerClass
     * An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-color-trigger'
     * which displays a calendar icon).
     */
    triggerClass : 'x-form-color-trigger',
    
    /**
     * @cfg {String/Object} autoCreate
     * A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "text", size: "10", autocomplete: "off"})
     */
    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "10",
                         autocomplete: "off", maxlength:"6"},
    
    /**
     * @cfg {String} lengthText
     * A string to be displayed when the length of the input field is
     * not 3 or 6, i.e. 'fff' or 'ffccff'.
     */
    lengthText: "Color hex values must be either 3 or 6 characters.",
    
    //text to use if blank and allowBlank is false
    blankText: "Must have a hexidecimal value in the format ABCDEF.",
    
    /**
     * @cfg {String} color
     * A string hex value to be used as the default color.  Defaults
     * to 'FFFFFF' (white).
     */
    defaultColor: '',
    
    maskRe: /[a-f0-9]/i,
    // These regexes limit input and validation to hex values
    regex: /[a-f0-9]/i,

    //private
    curColor: '',
    
    // private
    validateValue : function(value){
        if(!this.showHexValue) {
            return true;
        }
        if(value.length<1) {
            this.el.setStyle({
                'background-color':'#'+this.defaultColor
            });
            if(!this.allowBlank) {
                this.markInvalid(String.format(this.blankText, value));
                return false
            }
            return true;
        }
        this.setColor(value);
        return true;
    },

    // private
    validateBlur : function(){
        return !this.menu || !this.menu.isVisible();
    },
    
    // Manually apply the invalid line image since the background
    // was previously cleared so the color would show through.
    markInvalid : function( msg ) {
        Ext.form.ColorField.superclass.markInvalid.call(this, msg);
        this.el.setStyle({
            'background-image': 'url(../lib/resources/images/default/grid/invalid_line.gif)'
        });
    },

    /**
     * Returns the current color value of the color field
     * @return {String} value The hexidecimal color value
     */
    getValue : function(){
        return this.curValue || this.defaultValue || "FFFFFF";
    },

    /**
     * Sets the value of the color field.  Format as hex value 'FFFFFF'
     * without the '#'.
     * @param {String} hex The color value
     */
    setValue : function(hex){
        Ext.form.ColorField.superclass.setValue.call(this, hex);
        this.setColor(hex);
    },
    
    /**
     * Sets the current color and changes the background.
     * Does *not* change the value of the field.
     * @param {String} hex The color value.
     */
    setColor : function(hex) {
        this.curColor = hex;
        h = hex.substr(0,1) != '#' ? '#'+hex : hex;
        
        this.el.setStyle( {
            'background-color': h,
            'background-image': 'none'
        });
        if(!this.showHexValue) {
            /*this.el.setStyle({
                'text-indent': '-100px'
            });
            if(Ext.isIE) {
                this.el.setStyle({
                    'margin-left': '100px'
                });
            }*/
        }
    },
    
    handleRender: function() {
        this.setDefaultColor();
    },
    
    setDefaultColor : function() {
        this.setValue(this.defaultColor);
    },

    // private
    menuListeners : {
        select: function(m, d){
            this.setValue(d);
        },
        show : function(){ // retain focus styling
            this.onFocus();
        },
        hide : function(){
            this.focus();
            var ml = this.menuListeners;
            this.menu.un("select", ml.select,  this);
            this.menu.un("show", ml.show,  this);
            this.menu.un("hide", ml.hide,  this);
        }
    },
    
    //private
    handleSelect : function(palette, selColor) {
        this.setValue(selColor);
    },

    // private
    // Implements the default empty TriggerField.onTriggerClick function to display the ColorPicker
    onTriggerClick : function(){
        if(this.disabled){
            return;
        }
        if(this.menu == null){
            this.menu = new Ext.menu.ColorMenu();
            this.menu.palette.on('select', this.handleSelect, this );
        }
        this.menu.on(Ext.apply({}, this.menuListeners, {
            scope:this
        }));
        this.menu.show(this.el, "tl-bl?");
    }
});




/**
 * QTips to form fields
 */
Ext.form.Field.prototype.afterRender = Ext.form.Field.prototype.afterRender.createSequence(function() { 
    if (this.description) {
        Ext.QuickTips.register({
            target:  this.getEl()
            ,text: this.description
            ,enabled: true
        });
        var label = Ext.form.Field.findLabel(this);
        if(label){
            Ext.QuickTips.register({
                target:  label
                ,text: this.description
                ,enabled: true
            });
        }
    }
});
Ext.applyIf(Ext.form.Field,{
    findLabel: function(field) {
        var wrapDiv = null;
        var label = null;
        
        //find form-element and label?
        wrapDiv = field.getEl().up('div.x-form-element');
        if(wrapDiv){
            label = wrapDiv.child('label');
        }
        if(label){
            return label;
        }
        //find form-item and label
        wrapDiv = field.getEl().up('div.x-form-item');
        if(wrapDiv) {
            label = wrapDiv.child('label');        
        }
        if(label){
            return label;
        }
    }
});




Ext.onReady(function() {
    MODx.util.LoadingBox = MODx.load({ xtype: 'modx-loading-box' });
    MODx.util.JSONReader = MODx.load({ xtype: 'modx-json-reader' });
    MODx.form.Handler = MODx.load({ xtype: 'modx-form-handler' });
    MODx.msg = MODx.load({ xtype: 'modx-msg' });
});