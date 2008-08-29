/**
 * Loads a grid of Publish/Unpublish events for a resource.
 * 
 * @class MODx.grid.AccessContext
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-resource-event
 */
MODx.grid.ResourceEvent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('site_schedule')
        ,url: MODx.config.connectors_url+'resource/event.php'
        ,baseParams: {
            action: 'getList'
            ,mode: 'pub_date'
        }
        ,fields: ['id','pagetitle','class_key'
            ,{name: 'pub_date', type:'date',format: 'D M d, Y'}
            ,{name: 'unpub_date', type:'date',format: 'D M d, Y'}
            ,'menu']
        ,paging: true
        ,autosave: true
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 40 }
            ,{ header: _('pagetitle') ,dataIndex: 'pagetitle' ,width: 40 }
            ,{ 
                header: _('publish_date')
                ,dataIndex: 'pub_date'
                ,width: 150
                ,editor: { xtype: 'datefield' ,format: 'D M d, Y' }
            },{ 
                header: _('unpublish_date')
                ,dataIndex: 'unpub_date'
                ,width: 150
                ,editor: { xtype: 'datefield' ,format: 'D M d, Y' }
            }
        ]
        ,tbar: [{
            text: _('showing_pub')
            ,scope: this
            ,handler: this.toggle
            ,enableToggle: true
            ,tooltip: _('click_to_change')
            ,id: 'btn-toggle'
        }]
    });
    MODx.grid.ResourceEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ResourceEvent,MODx.grid.Grid,{
    toggle: function(btn,e) {
        var btn = Ext.getCmp('btn-toggle');
        var s = this.getStore();
        if (btn.pressed) {
            s.baseParams.mode = 'unpub_date';
            btn.setText(_('showing_unpub'));
        } else {
            s.baseParams.mode = 'pub_date';
            btn.setText(_('showing_pub'));
        }
        s.reload();
    }
});
Ext.reg('grid-resource-event',MODx.grid.ResourceEvent);