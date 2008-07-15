Ext.namespace('MODx','MODx.combo');
/**
 * Displays a dropdown list of class keys
 * 
 * @class MODx.combo.Provider
 * @extends MODx.combo.ComboBox
 * @constructor
 * @param {Object} config An object of options.
 * @xtype combo-provider
 */
MODx.combo.ClassKey = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'classKey'
        ,hiddenName: 'classKey'
        ,url: MODx.config.connectors_url+'workspace/builder/index.php'
        ,baseParams: { 
            action: 'getClassKeys'
        }
        ,displayField: 'key'
        ,valueField: 'key'
        ,fields: ['key']
        ,editable: false
    });
    MODx.combo.ClassKey.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ClassKey,MODx.combo.ComboBox);
Ext.reg('combo-class-key',MODx.combo.ClassKey);


/**
 * Displays a dropdown list of various objects, dynamically chosen
 * by a class key
 * 
 * @class MODx.combo.Object
 * @extends MODx.combo.ComboBox
 * @constructor
 * @param {Object} config An object of options.
 * @xtype combo-provider
 */
MODx.combo.Object = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'object'
        ,hiddenName: 'object'
        ,url: MODx.config.connectors_url+'workspace/builder/index.php'
        ,baseParams: { 
            action: 'getAssocObject'
            ,class_key: 'modResource'
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,pageSize: 10
        ,editable: false
    });
    MODx.combo.Object.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Object,MODx.combo.ComboBox);
Ext.reg('combo-object',MODx.combo.Object);