Ext.namespace('MODx');
/**
 * Loads the TV update page
 * 
 * @class MODx.UpdateTV
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype tv-update
 */
MODx.UpdateTV = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		form: 'mutate_tv'
		,fields: { 
            category: { 
                xtype: 'combo-category' ,transform:'category', value: config.category
            }
            ,rank: {
                xtype: 'numberfield'
                ,width: 50
                ,maxLength: 4
                ,allowNegative: false
                ,allowBlank: false
                ,applyTo: 'rank'
            }
            ,locked: {
                xtype: 'checkbox'
                ,boxLabel: _('tv_lock')
                ,applyTo: 'locked'
            }
            ,name: {
                xtype: 'textfield'
                ,width: 300
                ,applyTo: 'name'
            }
            ,caption: {
                xtype: 'textfield'
                ,width: 300
                ,applyTo: 'caption'
            }
            ,description: {
                xtype: 'textfield'
                ,width: 300
                ,applyTo: 'description'
            }
            ,type: {
                xtype: 'combo-tv-input-type'
                ,transform: 'type'
                ,value: config.type
            }
            ,els: {
                xtype: 'textfield'
                ,width: 250
                ,applyTo: 'els'
            }
            ,default_text: {
                xtype: 'textarea'
                ,width: 300
                ,grow: true
                ,applyTo: 'default_text'
            }
            ,widget: {
                xtype: 'combo-tv-widget'
                ,name: 'display'
                ,hiddenName: 'display'
                ,transform: 'display'
                ,listeners: {
                    'select': {fn:showParameters}
                }
            }
        }
		,actions: {
            'new': MODx.action['element/tv/create']
            ,edit: MODx.action['element/tv/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,refresh: {
            	tree: 'modx_element_tree'
            	,node: 'n_type_tv'
            	,self: true
            }
            ,listeners: {
                'click': {fn:function(btn,e) {
                    var g = Ext.getCmp('grid-tv-templates');
                    var rg = Ext.getCmp('grid-tv-security');
                    Ext.apply(this.ab.config.params,{
                        templates: g.encodeModified()
                        ,resource_groups: rg.encodeModified()
                    });
                },scope:this}
                ,'success': function(o,i,r) {
                    Ext.getCmp('grid-tv-templates').getStore().commitChanges();
                    Ext.getCmp('grid-tv-security').getStore().commitChanges();
                }
            }
        },{
            process: 'duplicate'
            ,text: _('duplicate')
            ,method: 'remote'
            ,confirm: _('tv_duplicate_confirm')
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_tv'
                ,self: true
            }
        },{
            process: 'delete'
            ,text: _('delete')
            ,method: 'remote'
            ,confirm: _('tv_delete_confirm')
            ,refresh: {
                tree: 'modx_element_tree'
                ,node: 'n_type_tv'
                ,self: true
            }
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['welcome']}
        }]
        ,loadStay: true
        ,tabs: [
            {contentEl: 'tab_general', title: _('general')}
            ,{ 
                xtype: 'grid-tv-template'
                ,id: 'grid-tv-templates'
                ,tv: config.id
                ,preventRender: true
            },{
                xtype: 'grid-tv-security'
                ,id: 'grid-tv-security'
                ,tv: config.id
                ,preventRender: true
            }
        ]
	});
	MODx.UpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.UpdateTV,MODx.Component);
Ext.reg('tv-update',MODx.UpdateTV);