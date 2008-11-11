/**
 * @class MODx.grid.ElementProperties
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype grid-element-properties
 */
MODx.grid.ElementProperties = function(config) {
    config = config || {};
    var exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p style="padding: .7em 1em .3em;"><i>{description}</i></p>'
        )
    });
    Ext.applyIf(config,{
        title: _('properties')
        ,id: 'grid-element-properties'
        ,maxHeight: 300
        ,fields: ['name','description','xtype','options','value']
        ,autoExpandColumn: 'value'
        ,sortBy: 'name'
        ,width: '100%'
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:false})
        ,plugins: [exp]
        ,columns: [exp,{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 250
            ,sortable: true
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
            text: _('property_create')
            ,handler: this.create
            ,scope: this
        }]
    });
    MODx.grid.ElementProperties.superclass.constructor.call(this,config);
    this.on('afteredit', this.propertyChanged, this);
    this.on('afterRemoveRow', this.propertyChanged, this);
    this.on('celldblclick',this.onDirty,this);
};
Ext.extend(MODx.grid.ElementProperties,MODx.grid.LocalProperty,{
    onDirty: function() {
        Ext.getCmp(this.config.panel).fireEvent('fieldChange');
    }
    
    ,_renderType: function(v,md,rec,ri) {
        switch (v) {
            case 'combo-boolean': return _('yesno'); break;
            case 'datefield': return _('date'); break;
            case 'numberfield': return _('integer'); break;
        }
        return _(v);
    }
    
    ,create: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'window-element-property-create'
            ,listeners: {
                'success': {fn:function(r) {
                    var rec = new this.propRecord({
                        name: r.name
                        ,description: r.description
                        ,xtype: r.xtype
                        ,options: r.options
                        ,value: r.value
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
            xtype: 'window-element-property-update'
            ,record: this.menu.record
            ,listeners: {
                'success': {fn:function(r) {
                    var s = this.getStore();
                    var rec = s.getAt(this.menu.recordIndex);
                    rec.set('name',r.name);
                    rec.set('description',r.description);
                    rec.set('xtype',r.xtype);
                    rec.set('options',r.options);
                    rec.set('value',r.value);
                    rec.commit();
                    this.onDirty();
                },scope:this}
            }
        });
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
    
    ,getMenu: function() {
        return [{
            text: _('property_update')
            ,scope: this
            ,handler: this.update
        },{
            text: _('property_remove')
            ,scope: this
            ,handler: this.remove.createDelegate(this,[{
                title: _('warning')
                ,text: _('property_remove_confirm')
            }])
        }];
    }

    ,propertyChanged: function() {
        var ep = Ext.getCmp(this.config.panel);
        var hf = this.config.hiddenPropField || 'props';
        ep.getForm().findField(hf).setValue('1');
        ep.fireEvent('fieldChange',{
            field: hf
            ,form: ep.getForm()
        });
        return true;
    }
});
Ext.reg('grid-element-properties',MODx.grid.ElementProperties);


MODx.grid.ElementPropertyOption = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_options')
        ,id: 'grid-element-property-option'
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
            xtype: 'window-element-property-option-create'
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
Ext.reg('grid-element-property-options',MODx.grid.ElementPropertyOption);

/**
 * @class MODx.window.CreateElementProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype window-element-property-create
 */
MODx.window.CreateElementProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_create')
        ,height: 250
        ,width: 450
        ,saveBtnText: _('done')
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,xtype: 'textarea'
            ,width: 150
        },{
            fieldLabel: _('type')
            ,name: 'xtype'
            ,xtype: 'combo-xtype'
            ,id: 'cep-xtype'
            ,width: 150
            ,listeners: {
                'select': {fn:function(cb,r,i) {
                    var g = Ext.getCmp('grid-element-property-options');
                    if (cb.getValue() == 'list') {
                       g.show();
                    } else {
                       g.hide();
                    }
                    this.syncSize();
                },scope:this}
            }
        },{
            xtype: 'element-value-field'
            ,xtypeField: 'cep-xtype'
        },{
            id: 'grid-element-property-options'
            ,xtype: 'grid-element-property-options'
        }]
    });
    MODx.window.CreateElementProperty.superclass.constructor.call(this,config);
    this.on('show',this.onShow,this);
};
Ext.extend(MODx.window.CreateElementProperty,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();
        
        var g = Ext.getCmp('grid-element-property-options');
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
        var g = Ext.getCmp('grid-element-property-options');
        g.getStore().removeAll();
        g.hide();
        this.syncSize();
        this.center();
    }
});
Ext.reg('window-element-property-create',MODx.window.CreateElementProperty);



/**
 * @class MODx.window.UpdateElementProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype window-element-property-update
 */
MODx.window.UpdateElementProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_update')
        ,height: 250
        ,width: 450
        ,saveBtnText: _('done')
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,xtype: 'textarea'
            ,width: 150
        },{
            fieldLabel: _('type')
            ,name: 'xtype'
            ,xtype: 'combo-xtype'
            ,id: 'uep-xtype'
            ,width: 150
            ,listeners: {
                'select': {fn:function(cb,r,i) {
                    var g = Ext.getCmp('uep-grid-element-property-options');
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
            xtype: 'element-value-field'
            ,xtypeField: 'uep-xtype'
            ,name: 'value'
        },{
            id: 'uep-grid-element-property-options'
            ,xtype: 'grid-element-property-options'
        }]
    });
    MODx.window.UpdateElementProperty.superclass.constructor.call(this,config);
    this.on('show',this.onShow,this);
};
Ext.extend(MODx.window.UpdateElementProperty,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();
        
        var g = Ext.getCmp('uep-grid-element-property-options');
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
        var g = Ext.getCmp('uep-grid-element-property-options');
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
Ext.reg('window-element-property-update',MODx.window.UpdateElementProperty);

/**
 * @class MODx.window.CreateElementPropertyOption
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype window-element-property-option-create
 */
MODx.window.CreateElementPropertyOption = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_option_create')
        ,height: 250
        ,width: 450
        ,saveBtnText: _('done')
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
        },{
            fieldLabel: _('value')
            ,name: 'value'
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
Ext.reg('window-element-property-option-create',MODx.window.CreateElementPropertyOption);



/**
 * Displays a xtype combobox
 * 
 * @class MODx.combo.xType
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype combo-xtype
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
Ext.reg('combo-xtype',MODx.combo.xType);




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
Ext.reg('element-value-field',MODx.form.ElementValueField);