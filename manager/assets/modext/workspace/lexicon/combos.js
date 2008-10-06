/**
 * Displays a dropdown list of available Lexicon Topics. Requires a namespace.
 * 
 * @class MODx.combo.LexiconTopic
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of config properties
 * @xtype combo-lexicon-topic
 */
MODx.combo.LexiconTopic = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'topic'
        ,hiddenName: 'topic'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'workspace/lexicon/topic.php'
        ,fields: ['id','name','namespace']
        ,displayField: 'name'
        ,valueField: 'id'
        ,baseParams: { 
            action: 'getList'
            ,namespace: 'core'
        }
    });
    MODx.combo.LexiconTopic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.LexiconTopic,MODx.combo.ComboBox);
Ext.reg('combo-lexicon-topic',MODx.combo.LexiconTopic);