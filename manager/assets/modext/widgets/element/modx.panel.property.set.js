/**
 * @class MODx.panel.PropertySet
 * @extends MODx.Panel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-property-sets
 */
MODx.panel.PropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-property-sets'
        ,items: [{
            html: '<h2>'+_('propertysets')+'</h2>'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 1.5em;'
            ,id: 'modx-property-set-form'
            ,items: [{
                html: '<p>'+_('propertysets_desc')+'</p>'
                ,id: 'modx-property-set-msg'
                ,border: false
            },{
                layout: 'column'
                ,border: false
                ,items: [{
                    columnWidth: .3
                    ,style:'padding:10px;'
                    ,border: false
                    ,items: [{
                        xtype: 'modx-tree-property-sets'
                        ,preventRender: true
                    }]
                },{
                    columnWidth: .7
                    ,style:'padding:10px;'
                    ,layout: 'form'
                    ,border: false
                    ,items: [{
                        xtype: 'modx-grid-property-set-properties'
                        ,preventRender: true
                    }]
                }]
            }]
        }]
    });
    MODx.panel.PropertySet.superclass.constructor.call(this,config);
    Ext.getCmp('modx-element-tree-panel').expand();
};
Ext.extend(MODx.panel.PropertySet,MODx.FormPanel);
Ext.reg('modx-panel-property-sets',MODx.panel.PropertySet);

/**
 * @class MODx.grid.PropertySetProperties
 * @extends MODx.grid.ElementProperties
 * @param {Object} config An object of config properties
 * @xtype modx-grid-property-set-properties
 */
MODx.grid.PropertySetProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        autoHeight: true
        ,lockProperties: false
        ,tbar: [{
            xtype: 'modx-combo-property-set'
            ,id: 'modx-combo-property-set'
            ,baseParams: {
                action: 'getList'
            }
            ,listeners: {
                'select': {fn:function(cb) { Ext.getCmp('modx-grid-element-properties').changePropertySet(cb); },scope:this}
            }
            ,value: ''
        },{
            text: _('property_create')
            ,handler: function(btn,e) { Ext.getCmp('modx-grid-element-properties').create(btn,e); }
            ,scope: this
        },'->',{
            text: _('propertyset_save')
            ,handler: function() { Ext.getCmp('modx-grid-element-properties').save(); }
            ,scope: this
        }]
    });
    MODx.grid.PropertySetProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.PropertySetProperties,MODx.grid.ElementProperties,{
    _renderName: function(v,md,rec,ri) {
        return '<span>'+v+'</span>';
    }
});
Ext.reg('modx-grid-property-set-properties',MODx.grid.PropertySetProperties);

/**
 * @class MODx.tree.PropertySets
 * @extends MODx.tree.Tree
 * @param {Object} config An object of config properties
 * @xtype modx-tree-property-sets
 */
MODx.tree.PropertySets = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        rootVisible: false
        ,enableDrag: false
        ,enableDrop: false
        ,title: ''
        ,url: MODx.config.connectors_url+'element/propertyset.php'
        ,baseParams: {
            action: 'getNodes'
        }
        ,tbar: [{
            text: _('propertyset_new')
            ,handler: this.createSet
            ,scope: this
        }]
        ,useDefaultToolbar: true
    });
    MODx.tree.PropertySets.superclass.constructor.call(this,config);
    this.on('click',this.loadGrid,this);
};
Ext.extend(MODx.tree.PropertySets,MODx.tree.Tree,{
    loadGrid: function(n,e) {
        var ar = n.id.split('_');
        if (ar[0] == 'ps') {
            MODx.Ajax.request({
                url: MODx.config.connectors_url+'element/propertyset.php'
                ,params: {
                    action: 'getProperties'
                    ,id: ar[1]
                }
                ,listeners: {
                    'success': {fn:function(r) {
                        var d = r.object;
                        var g = Ext.getCmp('modx-grid-element-properties');
                        var s = g.getStore();
                        g.defaultProperties = d;
                        s.removeAll();
                        s.loadData(d);
                        
                        Ext.getCmp('modx-combo-property-set').setValue(ar[1]);
                    },scope:this}
                }
            });
        }
    }
    
    ,createSet: function(btn,e) {        
        if (!this.winCreateSet) {
            this.winCreateSet = MODx.load({
                xtype: 'modx-window-property-set-create'
                ,listeners: {
                    'success':{fn:function() { 
                        this.refresh();
                        Ext.getCmp('modx-combo-property-set').store.reload();
                    },scope:this}
                }
            });
        }
        this.winCreateSet.show(e.target);
    }
    
    ,duplicateSet: function(btn,e) {
        var id = this.cm.activeNode.id.split('_');
        var r = this.cm.activeNode.attributes.data;
        r.id = id[1];
        r.new_name = _('duplicate_of',{name:r.name});
        if (!this.winDupeSet) {
            this.winDupeSet = MODx.load({
                xtype: 'modx-window-property-set-duplicate'
                ,record: r
                ,listeners: {
                    'success':{fn:function() { 
                        this.refresh();
                        Ext.getCmp('modx-combo-property-set').store.reload();
                    },scope:this}
                }
            });
        }
        this.winDupeSet.setValues(r);
        this.winDupeSet.show(e.target);
    }
    ,updateSet: function(btn,e) {
        var id = this.cm.activeNode.id.split('_');
        var r = this.cm.activeNode.attributes.data;
        r.id = id[1];
        if (!this.winUpdateSet) {
            this.winUpdateSet = MODx.load({
                xtype: 'modx-window-property-set-update'
                ,record: r
                ,listeners: {
                    'success':{fn:function() { 
                        this.refresh();
                        Ext.getCmp('modx-combo-property-set').store.reload();
                    },scope:this}
                }
            });
        }
        this.winUpdateSet.setValues(r);
        this.winUpdateSet.show(e.target);
    }
    ,removeSet: function(btn,e) {
        var id = this.cm.activeNode.id.split('_');
        id = id[1];
        MODx.msg.confirm({
            text: _('propertyset_remove_confirm') 
            ,url: MODx.config.connectors_url+'element/propertyset.php'
            ,params: {
                action: 'remove'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() { 
                    this.refreshNode(this.cm.activeNode.id);
                    var g = Ext.getCmp('modx-grid-element-properties');
                    g.getStore().removeAll();
                    g.defaultProperties = [];
                    Ext.getCmp('modx-combo-property-set').setValue('');
                },scope:this}
            }
        });
    }
    ,addElement: function(btn,e) {
        var id = this.cm.activeNode.id.split('_'); id = id[1];
        var t = this.cm.activeNode.text;
        
        if (!this.winPSEA) {
            this.winPSEA = MODx.load({
                xtype: 'modx-window-propertyset-element-add'
                ,record: {
                    propertysetName: this.cm.activeNode.text
                    ,propertyset: id
                }
                ,listeners: {
                    'success':{fn:function() { this.refreshNode(this.cm.activeNode.id,true); },scope:this}
                }
            });
        }
        this.winPSEA.show(e.target);
    }
    ,removeElement: function(btn,e) {
        var d = this.cm.activeNode.attributes;
        MODx.msg.confirm({
            text: _('propertyset_element_remove_confirm') 
            ,url: MODx.config.connectors_url+'element/propertyset.php'
            ,params: {
                action: 'removeElement'
                ,element: d.pk
                ,element_class: d.element_class
                ,propertyset: d.propertyset
            }
            ,listeners: {
                'success': {fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
            }
        });
    }
});
Ext.reg('modx-tree-property-sets',MODx.tree.PropertySets);

/**
 * @class MODx.window.AddElementToPropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-propertyset-element-add
 */
MODx.window.AddElementToPropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_element_add')
        ,url: MODx.config.connectors_url+'element/propertyset.php'
        ,baseParams: {
            action: 'addElement'
        }
        ,width: 400
        ,fields: [{
            xtype: 'hidden'
            ,name: 'propertyset'
        },{
            xtype: 'statictextfield'
            ,fieldLabel: _('propertyset')
            ,name: 'propertysetName'
            ,width: 250
        },{
            xtype: 'modx-combo-element-class'
            ,fieldLabel: _('class_name')
            ,name: 'element_class'
            ,id: 'modx-combo-element-class'
            ,listeners: {
                'select': {fn:this.onClassSelect,scope:this}
            }
        },{
            xtype: 'modx-combo-elements'
            ,fieldLabel: _('element')
            ,name: 'element'
            ,id: 'modx-combo-elements'
        }]
    });
    MODx.window.AddElementToPropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddElementToPropertySet,MODx.Window,{
    onClassSelect: function(cb) {
        var s = Ext.getCmp('modx-combo-elements').store;
        s.baseParams.element_class = cb.getValue();
        s.load();
    }
});
Ext.reg('modx-window-propertyset-element-add',MODx.window.AddElementToPropertySet);


/**
 * @class MODx.combo.ElementClass
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-element-class
 */
MODx.combo.ElementClass = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'element_class'
        ,hiddenName: 'element_class'
        ,displayField: 'name'
        ,valueField: 'name'
        ,fields: ['name']
        ,listWidth: 300
        ,editable: false
        ,url: MODx.config.connectors_url+'element/index.php'
        ,baseParams: {
            action: 'getClasses'
        }
    });
    MODx.combo.ElementClass.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ElementClass,MODx.combo.ComboBox);
Ext.reg('modx-combo-element-class',MODx.combo.ElementClass);

/**
 * @class MODx.combo.Elements
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-elements
 */
MODx.combo.Elements = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'element'
        ,hiddenName: 'element'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,listWidth: 300
        ,editable: false
        ,url: MODx.config.connectors_url+'element/index.php'
        ,baseParams: {
            action: 'getListByClass'
            ,element_class: 'modSnippet'
        }
    });
    MODx.combo.Elements.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Elements,MODx.combo.ComboBox);
Ext.reg('modx-combo-elements',MODx.combo.Elements);

/**
 * @class MODx.window.CreatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-property-set-create
 */
MODx.window.CreatePropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_create')
        ,url: MODx.config.connectors_url+'element/propertyset.php'
        ,baseParams: {
            action: 'create'
        }
        ,width: 550
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-cpropset-name'
            ,width: 200
            ,allowBlank: false
        },{
            xtype: 'modx-combo-category'
            ,fieldLabel: _('category')
            ,name: 'category'
            ,id: 'modx-cpropset-category'
            ,width: 200
            ,allowBlank: true
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-cpropset-description'
            ,width: 200
            ,grow: true
        }]
    });
    MODx.window.CreatePropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreatePropertySet,MODx.Window);
Ext.reg('modx-window-property-set-create',MODx.window.CreatePropertySet);


/**
 * @class MODx.window.UpdatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-property-set-update
 */
MODx.window.UpdatePropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_update')
        ,url: MODx.config.connectors_url+'element/propertyset.php'
        ,baseParams: {
            action: 'update'
        }
        ,width: 550
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-upropset-id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-upropset-name'
            ,width: 200
            ,allowBlank: false
        },{
            xtype: 'modx-combo-category'
            ,fieldLabel: _('category')
            ,name: 'category'
            ,id: 'modx-upropset-category'
            ,width: 200
            ,allowBlank: true
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-upropset-description'
            ,width: 200
            ,grow: true
        }]
    });
    MODx.window.UpdatePropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdatePropertySet,MODx.Window);
Ext.reg('modx-window-property-set-update',MODx.window.UpdatePropertySet);



/**
 * @class MODx.window.DuplicatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-property-set-duplicate
 */
MODx.window.DuplicatePropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_duplicate')
        ,url: MODx.config.connectors_url+'element/propertyset.php'
        ,baseParams: {
            action: 'duplicate'
        }
        ,width: 550
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-dpropset-id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('new_name')
            ,name: 'new_name'
            ,width: 200
            ,value: _('duplicate_of',{name:config.record.name})
        },{
            xtype: 'checkbox'
            ,boxLabel: _('propertyset_duplicate_copyels')
            ,labelSeparator: ''
            ,name: 'copyels'
            ,id: 'modx-dpropset-copyels'
            ,checked: true
        }]
    });
    MODx.window.DuplicatePropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicatePropertySet,MODx.Window);
Ext.reg('modx-window-property-set-duplicate',MODx.window.DuplicatePropertySet);
