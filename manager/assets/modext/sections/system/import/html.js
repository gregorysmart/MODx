Ext.namespace('MODx','MODx.Import');
Ext.onReady(function() {
    new MODx.Import.HTML();
});


MODx.Import.HTML = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        form: 'import_site'
        ,fields: {
            import_element: {
                xtype: 'textfield'
                ,id: 'import_element'
                ,width: 200
                ,applyTo: 'import_element'
                ,value: 'body'
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
            process: 'import', text: _('import_site'), method: 'remote'
            ,onComplete: function(o, itm, res) {
                Ext.get('import_results').update(res.message);
            }
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,components: [{
            xtype: 'tree-document-simple'
            ,title: _('documents')
            ,connector: MODx.config.connectors_url+'resource/document.php'
            ,el: 'modx_doctree'
            ,id: 'ih_doctree'
            ,tb_id: 'modx_doctree_tb'
            ,enableDrop: false
            ,rootVisible: false
        }]
    });
    MODx.Import.HTML.superclass.constructor.call(this,config);
    var sm = Ext.getCmp('ih_doctree').getSelectionModel();
    sm.on('selectionchange', function() {
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
Ext.extend(MODx.Import.HTML,MODx.Component);
Ext.reg('comp-import-html',MODx.Import.HTML);