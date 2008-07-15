Ext.namespace('MODx','MODx.Import');
Ext.onReady(function() {
    new MODx.Import.Resource();
});

MODx.Import.Resource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        form: 'import_site'
        ,fields: {
            import_element: {
                xtype: 'textfield'
                ,width: 200
                ,applyTo: 'import_element'
                ,value: 'body'
            }
            ,import_base_path: {
                xtype: 'textfield'
                ,width: 200
                ,applyTo: 'import_base_path'
                ,value: ''
            }
            ,import_resource_class: {
                xtype: 'textfield'
                ,width: 200
                ,applyTo: 'import_resource_class'
                ,value: 'modStaticResource'
            }
            ,import_allowed_extensions: {
                xtype: 'textfield'
                ,width: 200
                ,applyTo: 'import_allowed_extensions'
                ,value: ''
            }
            ,import_context: {
                xtype: 'hidden'
                ,id: 'import_context'
                ,applyTo: 'import_context'
                ,value: 'web'
            }
            ,import_parent: {
                xtype: 'hidden'
                ,id: 'import_parent'
                ,applyTo: 'import_parent'
                ,value: '0'
            }
        }
        ,buttons: [{
            process: 'import', text: _('import_resources'), method: 'remote'
            ,onComplete: function(o,i,r) {
                Ext.get('import_results').update(r.message);
            }
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,components: [{
            xtype: 'tree-document-simple'
            ,title: _('documents')
            ,id: 'import_doctree'
            ,el: 'import_doctree'
            ,connector: MODx.config.connectors_url+'resource/document.php'
            ,enableDrop: false
            ,rootVisible: false
        }]
    });
    MODx.Import.Resource.superclass.constructor.call(this,config);
    Ext.Ajax.timeout = 0;
    
    var sm = Ext.getCmp('import_doctree').getSelectionModel();
    sm.on('selectionchange',function() {
        var iPar = 0;
        var iCxt = 'web';
        var s = sm.getSelectedNode();
        if (s) {
            var spl = s.attributes.id.split('_');
            if (spl) {
                iCxt = spl[0];
                iPar = spl[1];
            }
        }
        Ext.getCmp('import_parent').setValue(iPar);
        Ext.getCmp('import_context').setValue(iCxt);
    });
};
Ext.extend(MODx.Import.Resource,MODx.Component);
Ext.reg('import-resource',MODx.Import.Resource);