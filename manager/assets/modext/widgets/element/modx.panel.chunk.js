/**
 * @class MODx.panel.Chunk
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-chunk
 */
MODx.panel.Chunk = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/chunk.php'
        ,baseParams: {}
        ,id: 'panel-chunk'
        ,class_key: 'modChunk'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: {
            xtype: 'modx-tabs'
            ,resizeTabs: false
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,labelWidth: 150
            }
            ,items: [{
                title: _('chunk_title')
                ,bodyStyle: 'padding: 1.5em;'
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                        html: '<h2>'+_('chunk')+': '+config.name+'</h2>'
                        ,border: false
                        ,id: 'chunk-header'
                    },{
                        html: '<p>'+_('chunk_msg')+'</p>'
                        ,border: false
                    },{
                        xtype: 'hidden'
                        ,name: 'id'
                        ,id: 'chunk-id'
                        ,value: config.chunk
                    },{
                        xtype: 'hidden'
                        ,name: 'props'
                        ,value: null
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('name')
                        ,name: 'name'
                        ,id: 'chunk-name'
                        ,width: 300
                        ,maxLength: 255
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                Ext.getCmp('chunk-header').getEl().update('<h2>'+_('chunk')+': '+f.getValue()+'</h2>');
                            }}
                        }
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('description')
                        ,name: 'description'
                        ,id: 'chunk-description'
                        ,width: 300
                        ,maxLength: 255
                    },{
                        xtype: 'combo-category'
                        ,fieldLabel: _('category')
                        ,name: 'category'
                        ,id: 'chunk-category'
                        ,width: 250
                        ,value: config.category || null
                    },{
                        xtype: 'checkbox'
                        ,fieldLabel: _('chunk_lock')
                        ,description: _('chunk_lock_msg')
                        ,name: 'locked'
                        ,id: 'chunk-locked'
                        ,inputValue: true
                    },{
                        html: onChunkFormRender
                        ,border: false
                    },{
                        html: '<br />'+_('chunk_code')
                        ,border: false
                    },{
                        xtype: 'textarea'
                        ,hideLabel: true
                        ,name: 'snippet'
                        ,id: 'chunk-snippet'
                        ,width: '95%'
                        ,height: 400
                        ,value: ""
                        
                    },{
                        xtype: 'combo-rte'
                        ,fieldLabel: _('which_editor_title')
                        ,id: 'which_editor'
                        ,editable: false
                        ,listWidth: 300
                        ,triggerAction: 'all'
                        ,allowBlank: true
                        ,listeners: {
                            'select': {fn:function() {
                                var w = Ext.getCmp('which_editor').getValue();
                                this.form.submit();
                                var u = '?a='+MODx.action['element/chunk/create']+'&which_editor='+w+'&category='+this.config.category;
                                location.href = u;
                            },scope:this}
                        }
                    }]
            },{
                xtype: 'panel-element-properties'
                ,elementPanel: 'panel-chunk'
                ,elementId: config.chunk
                ,elementType: 'modChunk'
            }]
        }
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Chunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Chunk,MODx.FormPanel,{
    setup: function() {
        if (this.config.chunk === '' || this.config.chunk === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.chunk
            }
            ,listeners: {
                'success': {fn:function(r) {
                    if (r.object.category == '0') { r.object.category = null; }
                    if (r.object.snippet == 'NULL') { r.object.snippet = ''; }
                    this.getForm().setValues(r.object);
                    Ext.getCmp('chunk-header').getEl().update('<h2>'+_('chunk')+': '+r.object.name+'</h2>');
                    this.fireEvent('ready',r.object);
                    
                    var d = Ext.decode(r.object.data);
                    var g = Ext.getCmp('grid-element-properties');
                    g.defaultProperties = d;
                    g.getStore().loadData(d);
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        return true;
    }
    ,success: function(r) {
        Ext.getCmp('grid-element-properties').save();
        var c = Ext.getCmp('chunk-category').getValue();
        var n = c !== '' && c !== null ? 'n_chunk_category_'+c : 'n_type_chunk';
        var t = parent.Ext.getCmp('modx_element_tree');
        if (t) {
            t.refreshNode(n,true);
        }
    }
});
Ext.reg('panel-chunk',MODx.panel.Chunk);