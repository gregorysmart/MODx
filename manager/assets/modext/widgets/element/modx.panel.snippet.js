/**
 * @class MODx.panel.Snippet
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-snippet
 */
MODx.panel.Snippet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/snippet.php'
        ,baseParams: {}
        ,id: 'panel-snippet'
        ,class_key: 'modSnippet'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: {
            xtype: 'tabpanel'
            ,activeTab: 0
            ,deferredRender: false
            ,border: false
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,bodyStyle: 'padding: 1.5em;'
                ,labelWidth: 150
            }
            ,items: [{
                title: _('snippet_title')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    html: '<h2>'+_('snippet')+': '+config.name+'</h2>'
                    ,id: 'snippet-name'
                },{
                    html: '<p>'+_('snippet_msg')+'</p>'
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,value: config.snippet
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('snippet_name')
                    ,name: 'name'
                    ,width: 300
                    ,maxLength: 255
                    ,enableKeyEvents: true
                    ,allowBlank: false
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('snippet-name').getEl().update('<h2>'+_('snippet')+': '+f.getValue()+'</h2>');
                        }}
                    }
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('snippet_desc')
                    ,name: 'description'
                    ,width: 300
                    ,maxLength: 255
                },{
                    xtype: 'combo-category'
                    ,fieldLabel: _('category')
                    ,name: 'category'
                    ,width: 250
                    ,value: config.category || null
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('snippet_execonsave')
                    ,name: 'runsnippet'
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('snippet_lock')
                    ,description: _('snippet_lock_msg')
                    ,name: 'locked'
                },{
                    html: '<br />'+_('snippet_code')
                },{
                    xtype: 'textarea'
                    ,hideLabel: true
                    ,name: 'snippet'
                    ,width: '95%'
                    ,height: 400
                    ,value: "<?php\n\n?>"
                    
                }]
            },{
                title: _('snippet_properties')
                ,contentEl: 'tab_properties'
                
            }]
        }
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.Snippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Snippet,MODx.FormPanel,{
    setup: function() {
        if (this.config.snippet === '' || this.config.snippet === 0) {       
            this.fireEvent('ready');
            return;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.snippet
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    if (r.object.category == '0') { r.object.category = null; }
                    r.object.snippet = "<?php\n"+r.object.snippet+"\n?>";
                    this.getForm().setValues(r.object);
                    Ext.getCmp('snippet-name').getEl().update('<h2>'+_('snippet')+': '+r.object.name+'</h2>');
                    this.clearDirty();
                    this.fireEvent('ready',r.object);
                },scope:this}
            }
        });
    }
});
Ext.reg('panel-snippet',MODx.panel.Snippet);