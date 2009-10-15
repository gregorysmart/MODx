MODx.TreeDrop = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-treedrop'
        ,ddGroup: 'modx-treedrop-dd'
    })
    MODx.TreeDrop.superclass.constructor.call(this,config);
    this.config = config;
    this.setup();
};
Ext.extend(MODx.TreeDrop,Ext.Component,{    
    setup: function() {
        var ddTarget = this.config.target;
        var ddTargetEl = this.config.targetEl;
        var cfg = this.config;
        
        this.targetEl = new Ext.dd.DropTarget(this.config.targetEl, {
            ddGroup: this.config.ddGroup
            
            ,notifyEnter: function(ddSource, e, data) {
                if (ddTarget.getEl) {
                    var el = ddTarget.getEl();
                    if (el) { el.frame(); }
                }
            }
            ,notifyDrop: function(ddSource, e, data) {
                if (!data.node || !data.node.attributes || !data.node.attributes.type) return false;
                if (data.node.attributes.type != 'modResource' && data.node.attributes.leaf != true) return false;
                var v = '';
                var win = false;
                switch (data.node.attributes.type) {
                    case 'modResource': v = '[[~'+data.node.attributes.pk+']]'; break;
                    case 'snippet': win = true; break;
                    case 'chunk': win = true; break;
                    case 'tv': win = true; break;
                    case 'file': v = data.node.attributes.id; break;
                    default: return false; break;
                }
                if (win) {
                    MODx.loadInsertElement({
                        pk: data.node.attributes.pk
                        ,classKey: data.node.attributes.classKey
                        ,name: data.node.attributes.name
                        ,output: v
                        ,ddTargetEl: ddTargetEl
                        ,iframe: cfg.iframe
                        ,iframeEl: cfg.iframeEl
                        ,onInsert: cfg.onInsert
                    });
                } else {
                    if (cfg.iframe) {
                        MODx.insertForRTE(cfg.iframeEl,v,cfg.onInsert);
                    } else {
                        var el = Ext.get(ddTargetEl);
                        if (el.dom.id == 'modx-static-content') {
                            v = v.substring(1);
                            Ext.getCmp(el.dom.id).setValue('');
                        }
                        if (el.dom.id == 'modx-symlink-content' || el.dom.id == 'modx-weblink-content') {
                            Ext.getCmp(el.dom.id).setValue('');
                            MODx.insertAtCursor(ddTargetEl,data.node.attributes.pk,cfg.onInsert);
                        } else {
                            MODx.insertAtCursor(ddTargetEl,v,cfg.onInsert);
                        }
                    }
                }
                return true;
            }
        });
    }    
});
Ext.reg('modx-treedrop',MODx.TreeDrop);

MODx.loadInsertElement = function(r) {
    var w = MODx.load({
        xtype: 'modx-window-insert-element'
        ,record: r
        ,listeners: {
            'success':{fn: function() {            
            },scope:this}
            ,'hide': {fn:function() { this.destroy(); }}
        }
    });
    w.setValues(r);
    w.show();
};

MODx.insertAtCursor = function(myField, myValue) {
    if (document.selection) { 
        myField.focus(); 
        sel = document.selection.createRange(); 
        sel.text = myValue; 
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart; 
        var endPos = myField.selectionEnd; 
        myField.value = myField.value.substring(0, startPos)+ myValue+ myField.value.substring(endPos, myField.value.length); 
    } else { 
        myField.value += myValue; 
    }
};
MODx.insertForRTE = function(el,v,fn) {
    if (fn) {
        fn(v,el);
    } else {
        var ta = window.frames[0].document.getElementById(el);
        if (ta.value) {
            ta.value = ta.value + v;
        } else {
            ta.innerHTML = ta.innerHTML + v;
        }
    }
};

MODx.window.InsertElement = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('select_el_opts')
        ,id: 'modx-window-insert-element' 
        ,width: 600
        ,url: MODx.config.connectors_url+'element/template.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'pk'
            ,id: 'modx-dise-pk'
        },{
            xtype: 'hidden'
            ,name: 'classKey'
            ,id: 'modx-dise-classkey'
        },{
            xtype: 'checkbox'
            ,fieldLabel: _('cached')
            ,name: 'cached'
            ,id: 'modx-dise-cached'
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'modx-combo-property-set'
            ,fieldLabel: _('property_set')
            ,name: 'propertyset'
            ,id: 'modx-dise-propset'
            ,baseParams: {
                action: 'getList'
                ,showAssociated: true
                ,elementId: config.record.pk
                ,elementType: config.record.classKey
            }
            ,listeners: {
                'select': {fn:this.changePropertySet,scope:this}
            }
        },{
            id: 'modx-dise-proplist'
            ,autoLoad: {
                url: MODx.config.connectors_url+'element/index.php'
                ,params: {
                   'action': 'getInsertProperties'
                   ,classKey: config.record.classKey
                   ,pk: config.record.pk
                   ,propertySet: 0
                }
                ,scripts: true
                ,callback: this.onPropFormLoad
                ,scope: this
            }
            ,style: 'display: none;'
        },{
            xtype: 'fieldset'
            ,title: _('properties')
            ,autoHeight: true
            ,collapsible: true
            ,items: [{
                html: '<div id="modx-iprops-form"></div>'
                ,autoHeight: true
            }]
        }]
    });
    MODx.window.InsertElement.superclass.constructor.call(this,config);
    this.on('show',function() { this.center(); },this);
};
Ext.extend(MODx.window.InsertElement,MODx.Window,{
    changePropertySet: function(cb) {
        var fp = Ext.getCmp('modx-iprops-fp');
        if (fp) fp.destroy();

        var u = Ext.getCmp('modx-dise-proplist').getUpdater();
        u.update({
            url: MODx.config.connectors_url+'element/index.php'
            ,params: {
                'action': 'getInsertProperties'
                ,classKey: this.config.record.classKey
                ,pk: this.config.record.pk
                ,propertySet: cb.getValue()
            }
            ,scripts: true
            ,callback: this.onPropFormLoad
            ,scope: this
        });
        this.modps = [];
    }
    ,createStore: function(data) {
        return new Ext.data.SimpleStore({
            fields: ["v","d"]
            ,data: data
        });
    }
    ,onPropFormLoad: function(el,s,r) {
        var vs = Ext.decode(r.responseText);
        if (!vs || vs.length <= 0) { return false; }
        for (var i=0;i<vs.length;i++) {
            if (vs[i].store) {
                vs[i].store = this.createStore(vs[i].store);
            }
        }
        MODx.load({
            xtype: 'panel'
            ,id: 'modx-iprops-fp'
            ,layout: 'form'
            ,autoHeight: true
            ,labelWidth: 150
            ,border: false
            ,items: vs
            ,renderTo: 'modx-iprops-form'
        })    
    }
    ,submit: function() {
        var v = '[[';
        var n = this.config.record.name;
        var f = this.fp.getForm();
        
        if (f.findField('cached').getValue() != true) {
            v = v+'!';
        }
        switch (this.config.record.classKey) {
            case 'modSnippet': v = v+n; break;
            case 'modChunk': v = v+'$'+n; break;
            case 'modTemplateVar': v = v+'*'+n; break;
        }
        var ps = f.findField('propertyset').getValue();
        if (ps !== 0 && ps !== '') {
            v = v+'@'+f.findField('propertyset').getRawValue();
        }
        v = v+'?';
        
        for (var i=0;i<this.modps.length;i++) {
            var fld = this.modps[i];
            var val = Ext.getCmp('modx-iprop-'+fld).getValue();
            if (val == true) val = 1;
            if (val == false) val = 0;
            v = v+' &'+fld+'=`'+val+'`';
        }
        v = v+']]';
        
        if (this.config.record.iframe) {
            MODx.insertForRTE(this.config.record.iframeEl,v,this.config.record.onInsert);
        } else {
            MODx.insertAtCursor(this.config.record.ddTargetEl,v);
        }
        this.hide();
        return true;
    }
    ,modps: []
    ,changeProp: function(k) {
        if (this.modps.indexOf(k) == -1) {
            this.modps.push(k);
        }
    }
});
Ext.reg('modx-window-insert-element',MODx.window.InsertElement);
