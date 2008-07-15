Ext.namespace('MODx');
/**
 * Loads the update module page
 * 
 * @class MODx.UpdateModule
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype module-update
 */
MODx.UpdateModule = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	   form: 'mutate_module'
       ,fileUpload: true
	   ,fields: {
            category: {
                xtype: 'combo-category'
                ,transform: 'category'
                ,value: config.category
            }
            ,name: {
                xtype: 'textfield'
                ,width: 300
                ,maxLength: 100
                ,allowBlank: false
                ,applyTo: 'name'
            }
            ,description: {
                xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                ,applyTo: 'description'
            }
            ,icon: {
                xtype: 'textfield'
                ,width: 300
                ,applyTo: 'icon'
            }
            ,enable_resource: {
                xtype: 'checkbox'
                ,boxLabel: _('enable_resource')
                ,applyTo: 'enable_resource'
            }
            ,sourcefile: {
                xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                ,applyTo: 'sourcefile'
            }
            ,disabled: {
                xtype: 'checkbox'
                ,boxLabel: _('disabled')
                ,applyTo: 'disabled'
            }
            ,locked: {
                xtype: 'checkbox'
                ,boxLabel: _('module_lock')
                ,applyTo: 'locked'
            }
            ,wrap: {
                xtype: 'checkbox'
                ,boxLabel: _('wrap_lines')
                ,applyTo: 'wrap'
            }
            ,post: {
                xtype: 'textarea'
                ,width: '95%'
                ,applyTo: 'post'
            }
            ,guid: {
                xtype: 'textfield'
                ,width: 300
                ,maxLength: 32
                ,applyTo: 'guid'
            }
            ,enable_sharedparams: {
                xtype: 'checkbox'
                ,boxLabel: _('module_sharedparams_enable_msg')
                ,applyTo: 'enable_sharedparams'
            }
            ,properties: {
                xtype: 'textfield'
                ,width: 300
                ,maxLength: 65535
                ,applyTo: 'properties'
                ,listeners: {
                    'change': {fn:showParameters.createDelegate(this,[])}
                }
            }
	   }
	   ,actions: {
           'new': MODx.action['element/module/create']
           ,edit: MODx.action['element/module/update']
            ,cancel: MODx.action['element/module']
       }
       ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['element/module']}
        }]
       ,loadStay: true
       ,tabs: [
            {contentEl: 'tab_content', title: _('general')}
            ,{contentEl: 'tab_configuration', title: _('configuration')}
            ,{contentEl: 'tab_usergroup', title: _('access_permissions')}
            ,{contentEl: 'tab_depend', title: _('module_dependencies')}
        ]
	});
	MODx.UpdateModule.superclass.constructor.call(this,config);
};
Ext.extend(MODx.UpdateModule,MODx.Component);
Ext.reg('module-update',MODx.UpdateModule);