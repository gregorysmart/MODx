/**
 * Displays a dropdown list of available Lexicon Focus. Requires a namespace.
 * 
 * @class MODx.combo.LexiconFocus
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-lexicon-focus
 */
MODx.combo.LexiconFocus = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'focus'
        ,hiddenName: 'focus'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'workspace/lexicon/focus.php'
        ,fields: ['name']
        ,displayField: 'name'
        ,valueField: 'name'
        ,baseParams: { 
            action: 'getList'
            ,namespace: 'core'
        }
    });
    MODx.combo.LexiconFocus.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.LexiconFocus,MODx.combo.ComboBox);
Ext.reg('combo-lexicon-focus',MODx.combo.LexiconFocus);