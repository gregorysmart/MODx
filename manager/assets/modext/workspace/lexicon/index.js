Ext.namespace('MODx.panel');
Ext.onReady(function() {
    MODx.load({ xtype: 'modx-lexicon-management' });
});

MODx.LexiconManagement = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
        	xtype: 'tabpanel'
        	,renderTo: 'tabs_div'
        	,activeTab: 0
            ,deferredRender: false
            ,border: false
            ,defaults: {
                autoHeight: true
                ,bodyStyle: 'padding: 1.5em;'
                ,labelWidth: 150
            }
            ,items: [{
                xtype: 'panel-lexicon'
            },{
                xtype: 'panel-lexicon-foci'
            },{
                xtype: 'panel-language'
            }]
        }]
    });
    MODx.LexiconManagement.superclass.constructor.call(this,config);
};
Ext.extend(MODx.LexiconManagement,MODx.Component);
Ext.reg('modx-lexicon-management',MODx.LexiconManagement);


MODx.panel.Lexicon = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	   title: _('lexicon_management')
       ,items: [{
            html: '<h2>'+_('lexicon_management')+'</h2>'
            ,border: false
        },{
            html: '<p>'+_('lexicon_management_desc')+'</p>'
            ,border: false
        },{
            xtype: 'grid-lexicon'
            ,id: 'grid-lexicon'
            ,renderTo: 'grid-lexicon'
        }]
	});
    MODx.panel.Lexicon.superclass.constructor.call(this,config);	
};
Ext.extend(MODx.panel.Lexicon,MODx.Panel);
Ext.reg('panel-lexicon',MODx.panel.Lexicon);


MODx.panel.LexiconFoci = function(config) {
    config = config || {};
    Ext.applyIf(config,{
       title: _('lexicon_foci')
       ,items: [{
            html: '<h2>'+_('lexicon_foci')+'</h2>'
            ,border: false
       },{
            html: '<p>'+_('lexicon_foci_desc')+'</h2>'
            ,border: false
       },{
            xtype: 'grid-lexicon-foci'
            ,id: 'grid-lexicon-foci'
            ,renderTo: 'grid-lexicon-foci'
       }]
    });
    MODx.panel.LexiconFoci.superclass.constructor.call(this,config);    
};
Ext.extend(MODx.panel.LexiconFoci,MODx.Panel);
Ext.reg('panel-lexicon-foci',MODx.panel.LexiconFoci);


MODx.panel.Language = function(config) {
    config = config || {};
    Ext.applyIf(config,{
       title: _('languages')
       ,items: [{
            html: '<h2>'+_('languages')+'</h2>'
            ,border: false
       },{
            html: '<p>'+_('languages_desc')+'</h2>'
            ,border: false
       },{
            xtype: 'grid-language'
            ,id: 'grid-language'
            ,renderTo: 'grid-language'
       }]
    });
    MODx.panel.Language.superclass.constructor.call(this,config);    
};
Ext.extend(MODx.panel.Language,MODx.Panel);
Ext.reg('panel-language',MODx.panel.Language);