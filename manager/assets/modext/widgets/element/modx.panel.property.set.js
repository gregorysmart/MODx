/**
 * @class MODx.panel.PropertySet
 * @extends MODx.Panel
 * @param {Object} config An object of config properties
 * @xtype panel-property-sets
 */
MODx.panel.PropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'panel-property-sets'
        ,title: _('propertysets')
        ,bodyStyle: 'padding: 1.5em;'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{        
            html: '<p>'+_('propertysets_desc')+'</p>'
            ,border: false
        },MODx.PanelSpacer,{
            layout: 'column'
            ,border: false
            ,items: [{
                columnWidth: .3
                ,layout: 'form'
                ,border: false
                ,items: [{
                    xtype: 'tree-property-sets'
                }]
            },{
                columnWidth: .7
                ,layout: 'form'
                ,border: false
                ,autoHeight: true
                ,items: [{
                    xtype: 'grid-property-set-properties'
                }]
            }]
        }]
    });
    MODx.panel.PropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PropertySet,MODx.Panel);
Ext.reg('panel-property-sets',MODx.panel.PropertySet);

/**
 * @class MODx.grid.PropertySetProperties
 * @extends MODx.grid.ElementProperties
 * @param {Object} config An object of config properties
 * @xtype grid-property-set-properties
 */
MODx.grid.PropertySetProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        autoHeight: true
        ,tbar: [{
            xtype: 'combo-property-set'
            ,id: 'combo-property-set'
            ,baseParams: {
                action: 'getList'
            }
            ,listeners: {
                'select': {fn:function(cb) { Ext.getCmp('grid-element-properties').changePropertySet(cb); },scope:this}
            }
            ,value: ''
        },{
            text: _('property_create')
            ,handler: function(btn,e) { Ext.getCmp('grid-element-properties').create(btn,e); }
            ,scope: this
        },'->',{
            text: _('propertyset_save')
            ,handler: function() { Ext.getCmp('grid-element-properties').save(); }
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
Ext.reg('grid-property-set-properties',MODx.grid.PropertySetProperties);

/**
 * @class MODx.tree.PropertySets
 * @extends MODx.tree.Tree
 * @param {Object} config An object of config properties
 * @xtype tree-property-sets
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
                        var g = Ext.getCmp('grid-element-properties');
                        var s = g.getStore();
                        g.defaultProperties = d;
                        s.removeAll();
                        s.loadData(d);
                        
                        Ext.getCmp('combo-property-set').setValue(ar[1]);
                    },scope:this}
                }
            });
        }
    }
    
    ,createSet: function(btn,e) {        
        if (!this.winCreateSet) {
            this.winCreateSet = MODx.load({
                xtype: 'window-property-set-create'
                ,listeners: {
                    'success':{fn:function() { 
                        this.refresh();
                        Ext.getCmp('combo-property-set').store.reload();
                    },scope:this}
                }
            });
        }
        this.winCreateSet.show(e.target);
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
                'success': {fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
            }
        });
    }
    ,addElement: function(btn,e) {
        var id = this.cm.activeNode.id.split('_'); id = id[1];
        var t = this.cm.activeNode.text;
        
        if (!this.winPSEA) {
            this.winPSEA = MODx.load({
                xtype: 'window-propertyset-element-add'
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
Ext.reg('tree-property-sets',MODx.tree.PropertySets);

/**
 * @class MODx.window.AddElementToPropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype window-propertyset-element-add
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
            xtype: 'combo-element-class'
            ,fieldLabel: _('class_name')
            ,name: 'element_class'
            ,id: 'combo-element-class'
            ,listeners: {
                'select': {fn:this.onClassSelect,scope:this}
            }
        },{
            xtype: 'combo-elements'
            ,fieldLabel: _('object')
            ,name: 'element'
            ,id: 'combo-elements'
        }]
    });
    MODx.window.AddElementToPropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddElementToPropertySet,MODx.Window,{
    onClassSelect: function(cb) {
        var s = Ext.getCmp('combo-elements').store;
        s.baseParams.element_class = cb.getValue();
        s.load();
    }
});
Ext.reg('window-propertyset-element-add',MODx.window.AddElementToPropertySet);


/**
 * @class MODx.combo.ElementClass
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype combo-element-class
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
Ext.reg('combo-element-class',MODx.combo.ElementClass);

/**
 * @class MODx.combo.Elements
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype combo-elements
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
Ext.reg('combo-elements',MODx.combo.Elements);

/**
 * @class MODx.window.CreatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype window-property-set-create
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
            ,width: 200
            ,allowBlank: false
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,width: 200
            ,grow: true
        }]
    });
    MODx.window.CreatePropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreatePropertySet,MODx.Window);
Ext.reg('window-property-set-create',MODx.window.CreatePropertySet);