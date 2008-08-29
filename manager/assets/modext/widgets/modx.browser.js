Ext.namespace('MODx.browser');
/**
 * MODx.Browser
 * Handles file selection and manipulation.
 * 
 * @class MODx.Browser
 * @extends Ext.Component
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-browser
 */
MODx.Browser = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        onSelect: function(data) {}
        ,scope: this
    });
    MODx.Browser.superclass.constructor.call(this,config);
    this.config = config;
    
    this.win = new MODx.browser.Window(config);
    this.win.reset();
    this.win.show(config.el);
};
Ext.extend(MODx.Browser,Ext.Component);
Ext.reg('modx-browser',MODx.Browser);

/**
 * The window layout for the browser
 * 
 * @class MODx.Browser.Window
 * @extends Ext.Window
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-browser-window
 */
MODx.browser.Window = function(config) {
    config = config || {};
    this.view = new MODx.browser.View({
        onSelect: {fn: this.onSelect, scope: this}
    });
    this.tree = new MODx.tree.Directory({
        onUpload: function() { this.view.run(); }
        ,scope: this
    });
    this.tree.on('click',function(node,e) {
        this.load(node.id);
    },this);
    
    Ext.applyIf(config,{
        title: _('modx_browser')
        ,cls: 'browser-win'
        ,layout: 'border'
        ,minWidth: 500
        ,minHeight: 300
        ,width: 750
        ,height: 500
        ,modal: false
        ,closeAction: 'hide'
        ,border: false
        ,items: [{
            id: 'browser-tree'
            ,cls: 'browser-tree'
            ,region: 'west'
            ,width: 200
            ,items: this.tree
        },{
            cls: 'browser-view'
            ,region: 'center'
            ,autoScroll: true
            ,width: 500
            ,items: this.view
            ,tbar: this.getToolbar()
        },{
            id: 'img-detail-panel'
            ,region: 'east'
            ,split: true
            ,width: 150
            ,minWidth: 150
            ,maxWidth: 250
        }]
        ,buttons: [{
            id: 'ok-btn'
            ,text: _('ok')
            ,handler: this.onSelect
            ,scope: this
        },{
            text: _('cancel')
            ,handler: this.hide
            ,scope: this
        }]
        ,keys: {
            key: 27 // Esc key
            ,handler: this.hide
            ,scope: this
        }
    });
    MODx.browser.Window.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.browser.Window,Ext.Window,{
    returnEl: null
    
    ,filter : function(){
        var filter = Ext.getCmp('filter');
        this.view.store.filter('name', filter.getValue());
        this.view.select(0);
    }
    
    ,setReturn: function(el) {
        this.returnEl = el;
    }
    
    ,load: function(dir) {
        dir = dir || '';
        this.view.run({dir: dir});
    }
    
    ,sortImages : function(){
        var v = Ext.getCmp('sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'asc' : 'desc');
        this.view.select(0);
    }
    
    ,reset: function(){
        if(this.rendered){
            Ext.getCmp('filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }
    
    ,getToolbar: function() {
        return [{
            text: _('filter')+':'
        },{
            xtype: 'textfield'
            ,id: 'filter'
            ,selectOnFocus: true
            ,width: 100
            ,listeners: {
                'render': {fn:function(){
                    Ext.getCmp('filter').getEl().on('keyup', function(){
                        this.filter();
                    }, this, {buffer:500});
                }, scope:this}
            }
        }, ' ', '-', {
            text: _('sort_by')+':'
        }, {
            id: 'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,lazyInit: false
            ,value: 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [['name',_('name')],['size',_('file_size')],['lastmod',_('last_modified')]]
            })
            ,listeners: {
                'select': {fn:this.sortImages, scope:this}
            }
        }]        
    }
    
    ,onSelect: function(data) {
        var selNode = this.view.getSelectedNodes()[0];
        var callback = this.config.onSelect || this.onSelectHandler;
        var lookup = this.view.lookup;
        var scope = this.config.scope;
        this.hide(this.config.animEl || null,function(){
            if(selNode && callback){
                var data = lookup[selNode.id];
                Ext.callback(callback,scope || this,[data]);
                if (window.top.opener) {
                    window.top.close();
                    window.top.opener.focus();
                }
            }
        },scope);
    }
    
    ,onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('modx-browser-window',MODx.browser.Window);

/**
 * The DataView for the Browser
 * 
 * @class MODx.browser.View
 * @extends MODx.DataView
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-browser-view 
 */
MODx.browser.View = function(config) {
    config = config || {};
    
    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'browser/directory.php'
        ,fields: [
            'name','cls','url','pathname','ext','disabled'
            ,{name:'size', type: 'float'}
            ,{name:'lastmod', type:'date', dateFormat:'timestamp'}
            ,'menu'
        ]
        ,baseParams: { action: 'getFiles' }
        ,tpl: this.templates.thumb
        ,listeners: {
            'selectionchange': {fn:this.showDetails, scope:this, buffer:100}
            ,'dblclick': {fn: config.onSelect.fn, scope: config.onSelect.scope }
        }
        ,prepareData: this.formatData.createDelegate(this)
    });
    MODx.browser.View.superclass.constructor.call(this,config);
};
Ext.extend(MODx.browser.View,MODx.DataView,{
    templates: {}
    
    ,removeFile: function(item,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('file_remove_confirm')
            ,connector: MODx.config.connectors_url+'browser/file.php'
            ,params: {
                action: 'remove'
                ,file: data.pathname
            }
            ,scope: this
            ,success: this.run
        });
    }
    
    ,run: function(p) {
        p = p || {};
        if (p.dir) this.dir = p.dir;
        Ext.applyIf(p,{
            action: 'getFiles'
            ,dir: this.dir
        });
        this.store.load({
            params: p
        });
    }
    
    ,showDetails : function(){
        var selNode = this.getSelectedNodes();
        var detailEl = Ext.getCmp('img-detail-panel').body;
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            Ext.getCmp('ok-btn').enable();
            var data = this.lookup[selNode.id];
            detailEl.hide();
            this.templates.details.overwrite(detailEl, data);
            detailEl.slideIn('l', {stopFx:true,duration:.2});
        }else{
            Ext.getCmp('ok-btn').disable();
            detailEl.update('');
        }
    }
    ,formatData: function(data) {
        var formatSize = function(data){
            if(data.size < 1024) {
                return data.size + " bytes";
            } else {
                return (Math.round(((data.size*10) / 1024))/10) + " KB";
            }
        };
        data.shortName = data.name;
        data.sizeString = formatSize(data);
        data.dateString = new Date(data.lastmod).format("m/d/Y g:i a");
        this.lookup[data.name] = data;
        return data;
    }
    ,_initTemplates: function() {
        this.templates.thumb = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="thumb-wrap" id="{name}">'
                ,'<div class="thumb"><img src="{url}" title="{name}"></div>'
                ,'<span>{name}</span></div>'
            ,'</tpl>'
        );
        this.templates.thumb.compile();
        
        this.templates.details = new Ext.XTemplate(
            '<div class="details">'
            ,'<tpl for=".">'
                ,'<img src="{url}" alt="" /><div class="details-info">'
                ,'<b>'+_('file_name')+':</b>'
                ,'<span>{name}</span>'
                ,'<b>'+_('file_size')+':</b>'
                ,'<span>{sizeString}</span>'
                ,'<b>'+_('last_modified')+':</b>'
                ,'<span>{dateString}</span></div>'
            ,'</tpl>'
            ,'</div>'
        );
        this.templates.details.compile(); 
    }
});
Ext.reg('modx-browser-view',MODx.browser.View);