Ext.onReady(function() {
    MODx.load({ xtype: 'page-import-html' });
});

/**
 * @class MODx.page.ImportHTML
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype import-html
 */
MODx.page.ImportHTML = function(config) {
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
            ,url: MODx.config.connectors_url+'resource/document.php'
            ,el: 'modx_doctree'
            ,id: 'ih_doctree'
            ,tb_id: 'modx_doctree_tb'
            ,enableDrop: false
            ,rootVisible: false
        }]
    });
    MODx.page.ImportHTML.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.page.ImportHTML,MODx.Component,{
    setup: function() {
        Ext.Ajax.timeout = 0;
        var t = Ext.getCmp('ih_doctree');
        t.getSelectionModel().on('selectionchange',this.handleClick,t);
    }
    
    ,handleClick: function() {
        var iPar = 0;
        var iCxt = 'web';
        var s = this.getSelectionModel().getSelectedNode();
        if (s) {
            var spl = s.attributes.id.split('_');
            if (spl) {
                iCxt = spl[0];
                iPar = spl[1];
            }
        }
        Ext.getCmp('import_parent').setValue(iPar);
        Ext.getCmp('import_context').setValue(iCxt);
    }
});
Ext.reg('page-import-html',MODx.page.ImportHTML);