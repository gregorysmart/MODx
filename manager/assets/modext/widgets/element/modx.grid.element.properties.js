/**
 * @class MODx.grid.ElementProperties
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype grid-element-properties
 */
MODx.grid.ElementProperties = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p style="padding: .7em 1em .3em;"><i>{description}</i></p>'
        )
    });
    Ext.applyIf(config,{
        title: _('properties')
        ,id: 'modx-grid-element-properties'
        ,maxHeight: 300
        ,fields: ['name','description','xtype','options','value','overridden']
        ,autoExpandColumn: 'value'
        ,sortBy: 'name'
        ,width: '100%'
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:false})
        ,plugins: [this.exp]
        ,columns: [this.exp,{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
            ,renderer: this._renderName
        },{
            header: _('type')
            ,dataIndex: 'xtype'
            ,width: 100
            ,renderer: this._renderType
            ,sortable: true
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,id: 'value'
            ,width: 250
            ,renderer: this.renderDynField.createDelegate(this,[this],true)
            ,sortable: true
        }]
        ,tbar: [{
            xtype: 'modx-combo-property-set'
            ,id: 'modx-combo-property-set'
            ,baseParams: {
                action: 'getList'
                ,showAssociated: true
                ,elementId: config.elementId
                ,elementType: config.elementType
            }
            ,value: _('default')
            ,listeners: {
                'select': {fn:this.changePropertySet,scope:this}
            }
        },{
            text: _('property_create')
            ,handler: this.create
            ,scope: this
        },'-',{
            text: _('property_revert_all')
            ,handler: this.revertAll
            ,scope:this
        },'-',{
            text: _('properties_default_locked')
            ,id: 'modx-btn-propset-lock'
            ,handler: this.togglePropertiesLock
            ,enableToggle: true
            ,pressed: true
            ,disabled: MODx.perm.unlock_element_properties ? false : true
            ,scope: this
        },'->',{
            text: _('propertyset_add')
            ,handler: this.addPropertySet
            ,scope: this
        },'-',{
            text: _('propertyset_save')
            ,handler: this.save
            ,scope: this
        }]
        ,collapseFirst: false
        ,tools: [{
            id: 'plus'
            ,qtip: _('expand_all')
            ,handler: this.expandAll
            ,scope: this
        },{
            id: 'minus'
            ,hidden: true
            ,qtip: _('collapse_all')
            ,handler: this.collapseAll
            ,scope: this
        }]
    });
    MODx.grid.ElementProperties.superclass.constructor.call(this,config);
    this.on('afteredit', this.propertyChanged, this);
    this.on('afterRemoveRow', this.propertyChanged, this);
    this.on('celldblclick',this.onDirty,this);
    
    this.lockMask = MODx.load({
        xtype: 'modx-lockmask'
        ,el: this.getGridEl()
        ,msg: _('properties_default_locked')
    });
    this.lockMask.toggle();
};
Ext.extend(MODx.grid.ElementProperties,MODx.grid.LocalProperty,{
    defaultProperties: []
    
    ,onDirty: function() {
        if (this.config.panel) {
            Ext.getCmp(this.config.panel).fireEvent('fieldChange');
        }
    }
    
    ,_renderType: function(v,md,rec,ri) {
        switch (v) {
            case 'combo-boolean': return _('yesno'); break;
            case 'datefield': return _('date'); break;
            case 'numberfield': return _('integer'); break;
        }
        return _(v);
    }
    ,_renderName: function(v,md,rec,ri) {
        switch (rec.data.overridden) {
            case 1:
                return '<span style="color: green;">'+v+'</span>'; break;
            case 2:
                return '<span style="color: purple;">'+v+'</span>';
            default:
                return '<span>'+v+'</span>';
        }
    }
    
    ,save: function() {
        var d = this.encode();
        var cb = Ext.getCmp('modx-combo-property-set');
        if (!cb) {
            this.getStore().commitChanges();
            this.onDirty();
            return true;
        }
        var p = {
            action: 'updatefromelement'
            ,id: cb.getValue()
            ,data: d
        };
        if (this.config.elementId) {
            Ext.apply(p,{
                elementId: this.config.elementId
                ,elementType: this.config.elementType
            });
        }
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'element/propertyset.php'
            ,params: p
            ,listeners: {
                'success': {fn:function(r) {
                    this.getStore().commitChanges();
                    this.changePropertySet(cb);
                    this.onDirty();
                },scope:this}
            }
        });
    }
    
    ,addPropertySet: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-element-property-set-add'
            ,record: {
                elementId: this.config.elementId != 0 ? this.config.elementId : ''
                ,elementType: this.config.elementType
            }
            ,listeners: {
                'success': {fn:function(o) {
                    var cb = Ext.getCmp('modx-combo-property-set');
                    cb.store.reload({
                        callback: function() {
                            cb.setValue(o.a.result.object.id);
                            this.changePropertySet(cb);     
                        }
                        ,scope: this
                    });
                    this.onDirty();
                },scope:this}
            }
        });
    }    
    
    ,togglePropertiesLock: function() { 
        var ps = Ext.getCmp('modx-combo-property-set').getValue();
        if (ps == 0 || ps == _('default')) {
            Ext.getCmp('modx-btn-propset-lock').setText(this.lockMask.locked ? _('properties_default_unlocked') : _('properties_default_locked'));
            this.lockMask.toggle();
        }
    }
    
    ,changePropertySet: function(cb) {
        var ps = cb.getValue();
        if (ps == 0 || ps == _('default')) {
            if (MODx.perm.unlock_element_properties) {
                Ext.getCmp('modx-btn-propset-lock').setDisabled(false);
            }
            if (this.lockMask.locked) {
                this.lockMask.show();
            }
        } else {
            Ext.getCmp('modx-btn-propset-lock').setDisabled(true);
            this.lockMask.hide();
        }
        
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'element/propertyset.php'
            ,params: {
                action: 'get'
                ,id: ps
                ,elementId: this.config.elementId
                ,elementType: this.config.elementType
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var s = this.getStore();
                    var data = Ext.decode(r.object.data);
                    s.removeAll();
                    s.loadData(data);
                },scope:this}
            }
        });
    }
    
    ,create: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-element-property-create'
            ,blankValues: true
            ,listeners: {
                'success': {fn:function(r) {
                    
                    var rec = new this.propRecord({
                        name: r.name
                        ,description: r.description
                        ,xtype: r.xtype
                        ,options: r.options
                        ,value: r.value
                        ,overridden: this.isDefaultPropSet() ? 0 : 2
                    });
                    this.getStore().add(rec);
                    this.propertyChanged();
                    this.onDirty();
                },scope:this}
            }
        });
    }
    
    ,update: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-element-property-update'
            ,record: this.menu.record
            ,listeners: {
                'success': {fn:function(r) {
                    var def = this.isDefaultPropSet();
                    var s = this.getStore();
                    var rec = s.getAt(this.menu.recordIndex);
                    rec.set('name',r.name);
                    rec.set('description',r.description);
                    rec.set('xtype',r.xtype);
                    rec.set('options',r.options);
                    rec.set('value',r.value);
                    rec.set('overridden',r.overridden == 2 ? 2 : (!def ? 1 : 0));
                    this.onDirty();
                },scope:this}
            }
        });
    }
    
    ,revert: function(btn,e) {
        Ext.Msg.confirm(_('warning'),_('property_revert_confirm'),function(e) {
            if (e == 'yes') {                    
                var ri = this.menu.recordIndex;
                var d = this.defaultProperties[ri];
                var rec = this.getStore().getAt(ri);
                rec.set('name',d[0]);
                rec.set('description',d[1]);
                rec.set('xtype',d[2]);
                rec.set('options',d[3]);
                rec.set('value',d[4]);
                rec.set('overridden',0);
                rec.commit();
            }
        },this);
    }
    
    ,revertAll: function(btn,e) {
        Ext.Msg.confirm(_('warning'),_('property_revert_all_confirm'),function(e) {
            if (e == 'yes') {
                this.getStore().loadData(this.defaultProperties);
            }
        },this);
    }
    
    ,removeMultiple: function(btn,e) {
        var rows = this.getSelectionModel().getSelections();
        var rids = [];
        for (var i=0;i<rows.length;i=i+1) {
            rids.push(rows[i].data.id);
        }
        Ext.Msg.confirm(_('warning'),_('properties_remove_confirm'),function(e) {
            if (e == 'yes') {
                for (var f=0;f<rows.length;f=f+1) {
                    this.store.remove(rows[f]);
                }
            }
        },this);
    }
    
    ,_showMenu: function(g,ri,e) {
        var sm = this.getSelectionModel();
        if (sm.getSelections().length > 1) {
            e.stopEvent();
            e.preventDefault();
            this.menu.removeAll();
            this.addContextMenuItem([{
                text: _('properties_remove')
                ,handler: this.removeMultiple
                ,scope: this
            }]);
            this.menu.show(e.target);
        } else {
            MODx.grid.ElementProperties.superclass._showMenu.call(this,g,ri,e);
        }
    }
    
    ,isDefaultPropSet: function() {
        var ps = Ext.getCmp('modx-combo-property-set').getValue();
        return (ps == 0 || ps == _('default'));
    }
    
    ,getMenu: function() {
        var def = this.isDefaultPropSet();
        
        var r = this.menu.record;
        var m = []
        m.push({
            text: _('property_update')
            ,scope: this
            ,handler: this.update
        });
        
        if (r.overridden) {
            m.push({
                text: _('property_revert')
                ,scope: this
                ,handler: this.revert
            });
        }
        
        if (r.overridden != 1 && def) {
            m.push({
                text: _('property_remove')
                ,scope: this
                ,handler: this.remove.createDelegate(this,[{
                    title: _('warning')
                    ,text: _('property_remove_confirm')
                }])
            });
        }
        return m;
    }

    ,propertyChanged: function() {
        var ep = Ext.getCmp(this.config.panel);
        if (!ep) return false;
        var hf = this.config.hiddenPropField || 'props';
        ep.getForm().findField(hf).setValue('1');
        ep.fireEvent('fieldChange',{
            field: hf
            ,form: ep.getForm()
        });
        return true;
    }
});
Ext.reg('modx-grid-element-properties',MODx.grid.ElementProperties);


MODx.grid.ElementPropertyOption = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_options')
        ,id: 'modx-grid-element-property-options'
        ,autoHeight: true
        ,maxHeight: 300
        ,width: '100%'
        ,fields: ['name','value']
        ,data: []
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,id: 'value'
            ,width: 250
            ,editor: { xtype: 'textfield' ,allowBlank: true }
        }]
        ,tbar: [{
            text: _('property_option_create')
            ,handler: this.create
            ,scope: this
        }]
    });
    MODx.grid.ElementPropertyOption.superclass.constructor.call(this,config);
    this.optRecord = Ext.data.Record.create([{name: 'name'},{name: 'value'}]);
};
Ext.extend(MODx.grid.ElementPropertyOption,MODx.grid.LocalGrid,{
    create: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-element-property-option-create'
            ,listeners: {
                'success': {fn:function(r) {
                    var rec = new this.optRecord({
                        name: r.name
                        ,value: r.value
                    });
                    this.getStore().add(rec);
                },scope:this}
            }
        });
    }

    ,getMenu: function() {
        return [{
            text: _('property_option_remove')
            ,scope: this
            ,handler: this.remove.createDelegate(this,[{
                title: _('warning')
                ,text: _('property_option_remove_confirm')
            }])
        }];
    }
});
Ext.reg('modx-grid-element-property-options',MODx.grid.ElementPropertyOption);

/**
 * @class MODx.window.CreateElementProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-create
 */
MODx.window.CreateElementProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_create')
        ,id: 'modx-window-element-property-create'
        ,height: 250
        ,width: 450
        ,saveBtnText: _('done')
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-cep-name'
            ,xtype: 'textfield'            
            ,width: 150
            ,allowBlank: false
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-cep-description'
            ,xtype: 'textarea'
            ,width: 150
        },{
            fieldLabel: _('type')
            ,name: 'xtype'
            ,id: 'modx-cep-xtype'
            ,xtype: 'modx-combo-xtype'
            ,width: 150
            ,listeners: {
                'select': {fn:function(cb,r,i) {
                    var g = Ext.getCmp('modx-cep-grid-element-property-options');
                    if (!g) return;
                    if (cb.getValue() == 'list') {
                       g.show();
                    } else {
                       g.hide();
                    }
                    this.syncSize();
                },scope:this}
            }
        },{
            xtype: 'modx-element-value-field'
            ,xtypeField: 'modx-cep-xtype'
            ,id: 'modx-cep-value'
        },{
            xtype: 'modx-grid-element-property-options'
            ,id: 'modx-cep-grid-element-property-options'
        }]
    });
    MODx.window.CreateElementProperty.superclass.constructor.call(this,config);
    this.on('show',this.onShow,this);
};
Ext.extend(MODx.window.CreateElementProperty,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();
        
        var g = Ext.getCmp('modx-cep-grid-element-property-options');
        var opt = eval(g.encode());
        Ext.apply(v,{
            options: opt
        });
        
        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',v)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
    ,onShow: function() {
        var g = Ext.getCmp('modx-cep-grid-element-property-options');
        g.getStore().removeAll();
        g.hide();
        this.syncSize();
        this.center();
    }
});
Ext.reg('modx-window-element-property-create',MODx.window.CreateElementProperty);



/**
 * @class MODx.window.UpdateElementProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-update
 */
MODx.window.UpdateElementProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_update')
        ,id: 'modx-window-element-property-update'
        ,height: 250
        ,width: 450
        ,saveBtnText: _('done')
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-uep-name'
            ,xtype: 'textfield'
            ,width: 150
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-uep-description'
            ,xtype: 'textarea'
            ,width: 150
        },{
            fieldLabel: _('type')
            ,name: 'xtype'
            ,xtype: 'modx-combo-xtype'
            ,id: 'modx-uep-xtype'
            ,width: 150
            ,listeners: {
                'select': {fn:function(cb,r,i) {
                    var g = Ext.getCmp('modx-uep-grid-element-property-options');
                    if (!g) return;
                    var v = cb.getValue();
                    if (v == 'list') {
                        g.show();
                    } else {
                        g.hide();
                    }
                    this.syncSize();         
                },scope:this}
            }
        },{
            xtype: 'hidden'
            ,name: 'overridden'
            ,id: 'modx-uep-overridden'
        },{
            xtype: 'modx-element-value-field'
            ,xtypeField: 'modx-uep-xtype'
            ,name: 'value'
            ,id: 'modx-uep-value'
        },{
            id: 'modx-uep-grid-element-property-options'
            ,xtype: 'modx-grid-element-property-options'
        }]
    });
    MODx.window.UpdateElementProperty.superclass.constructor.call(this,config);
    this.on('show',this.onShow,this);
};
Ext.extend(MODx.window.UpdateElementProperty,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();
        
        var g = Ext.getCmp('modx-uep-grid-element-property-options');
        var opt = eval(g.encode());
        Ext.apply(v,{
            options: opt
        });
        
        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',v)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
    ,onShow: function() {
        var g = Ext.getCmp('modx-uep-grid-element-property-options');
        if (!g) return;
        g.getStore().removeAll();
        var opt = this.config.record.options;
        var opts = [];
        for (var x in opt) {
          if (opt.hasOwnProperty(x)) {
            opts.push([opt[x].name,opt[x].value]);
          }
        }
        g.getStore().loadData(opts);
        if (this.config.record.xtype == 'list') {
            g.show();
        } else {
            g.hide();
        }
        this.syncSize();
        this.center();
    }
});
Ext.reg('modx-window-element-property-update',MODx.window.UpdateElementProperty);

/**
 * @class MODx.window.CreateElementPropertyOption
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-option-create
 */
MODx.window.CreateElementPropertyOption = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_option_create')
        ,id: 'modx-window-element-property-option-create'
        ,height: 250
        ,width: 450
        ,saveBtnText: _('done')
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-cepo-name'
            ,xtype: 'textfield'
            ,width: 150
        },{
            fieldLabel: _('value')
            ,name: 'value'
            ,id: 'modx-cepo-value'
            ,xtype: 'textfield'
            ,width: 150
        }]
    });
    MODx.window.CreateElementPropertyOption.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateElementPropertyOption,MODx.Window,{
    submit: function() {
        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',this.fp.getForm().getValues())) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
});
Ext.reg('modx-window-element-property-option-create',MODx.window.CreateElementPropertyOption);



/**
 * Displays a xtype combobox
 * 
 * @class MODx.combo.xType
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-xtype
 */
MODx.combo.xType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [
                [_('textfield'),'textfield']
                ,[_('textarea'),'textarea']
                ,[_('yesno'),'combo-boolean']
                ,[_('date'),'datefield']
                ,[_('list'),'list']
                ,[_('integer'),'numberfield']
            ]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,name: 'xtype'
        ,hiddenName: 'xtype'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,value: 'textfield'
    });
    MODx.combo.xType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.xType,Ext.form.ComboBox);
Ext.reg('modx-combo-xtype',MODx.combo.xType);




MODx.form.ElementValueField = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        fieldLabel: _('value')
        ,name: 'value'
        ,xtype: 'textfield'
        ,width: 150
    });
    MODx.form.ElementValueField.superclass.constructor.call(this,config);
    this.config = config;
    this.on('change',this.checkValue,this);
};
Ext.extend(MODx.form.ElementValueField,Ext.form.TextField,{
    checkValue: function(fld,nv,ov) {
        var t = Ext.getCmp(this.config.xtypeField).getValue();
        var v = fld.getValue();
        if (t == 'combo-boolean') {
            v = (v == '1' || v == 'true' || v == 1 || v == true || v == _('yes') || v == 'yes') ? 1 : 0;
            fld.setValue(v);
        }
    }
});
Ext.reg('modx-element-value-field',MODx.form.ElementValueField);


MODx.combo.PropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'propertyset'
        ,hiddenName: 'propertyset'
        ,url: MODx.config.connectors_url+'element/propertyset.php'
        ,baseParams: {
            action: 'getList'
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name','description','properties']
        ,editable: false
        ,value: 0
    });
    MODx.combo.PropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.PropertySet,MODx.combo.ComboBox);
Ext.reg('modx-combo-property-set',MODx.combo.PropertySet);

/**
 * @class MODx.window.AddPropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-set-add
 */
MODx.window.AddPropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_add')
        ,id: 'modx-window-element-property-set-add'
        ,url: MODx.config.connectors_url+'element/propertyset.php'
        ,action: 'associate'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'elementId'
            ,id: 'modx-aps-elementId'
        },{
            xtype: 'hidden'
            ,name: 'elementType'
            ,id: 'modx-aps-elementType'
        },{
            html: _('propertyset_panel_desc')
        },MODx.PanelSpacer,{
            xtype: 'modx-combo-property-set'
            ,fieldLabel: _('propertyset')
            ,name: 'propertyset'
            ,id: 'modx-aps-propertyset'
            ,baseParams: {
                action: 'getList'
                ,showNotAssociated: true
                ,elementId: config.record.elementId
                ,elementType: config.record.elementType
            }
        },{
            xtype: 'hidden'
            ,name: 'propertyset_new'
            ,id: 'modx-aps-propertyset-new'
            ,value: false
        },{
            xtype: 'fieldset'
            ,title: _('propertyset_create_new')
            ,autoHeight: true
            ,checkboxToggle: true
            ,collapsed: true
            ,id: 'modx-aps-propertyset-new-fs'
            ,listeners: {
                'expand': {fn:function(p) {
                    Ext.getCmp('modx-aps-propertyset-new').setValue(true);
                },scope:this}
                ,'collapse': {fn:function(p) {
                    Ext.getCmp('modx-aps-propertyset-new').setValue(false);
                },scope:this}
            }
            ,items: [{
                xtype: 'textfield'
                ,fieldLabel: _('name')
                ,name: 'name'
                ,id: 'modx-aps-name'
            },{
                xtype: 'textarea'
                ,fieldLabel: _('description')
                ,name: 'description'
                ,id: 'modx-aps-description'
                ,width: '80%'
                ,grow: true
            }]
        }]
    });
    MODx.window.AddPropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddPropertySet,MODx.Window);
Ext.reg('modx-window-element-property-set-add',MODx.window.AddPropertySet);