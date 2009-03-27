/**
 * Loads a grid of Messages.
 * 
 * @class MODx.grid.Message
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-message
 */
MODx.grid.Message = function(config) {
    config = config || {};
    
    var exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<span style="float: right;">'
            ,'<i>'+_('sent_by')+': {sender_name} <br />'+_('sent_on')+': {postdate}</i><br /><br />'
            ,'</span>'    
            ,'<h3>{subject}</h3>'
            ,'<p>{message}</p>'
        )
    });
    exp.on('expand',this.read,this);
    Ext.applyIf(config,{
        title: _('messages')
        ,id: 'grid-message'
        ,url: MODx.config.connectors_url+'security/message.php'
        ,fields: ['id','type','subject','message','sender','recipient','private'
            ,'date_sent'
            ,'read','sender_name','menu']
        ,width: 800
        ,autosave: true
        ,paging: true
        ,plugins: exp
        ,columns: [exp,{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 60
        },{
            header: _('sender')
            ,dataIndex: 'sender'
            ,width: 120
            ,editor: { xtype: 'combo-user' ,renderer: true }
            ,editable: false
        },{
            header: _('subject')
            ,dataIndex: 'subject'
            ,width: 200
        },{
            header: _('date_sent')
            ,dataIndex: 'date_sent'
            ,width: 150
        },{
            header: _('read')
            ,dataIndex: 'read'
            ,width: 100
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
            ,editable: false
        }]
        ,tbar: [{
            text: _('message_new')
            ,scope: this
            ,handler: { xtype: 'window-message-create' ,blankValues: true }
        }]
    });
    MODx.grid.Message.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Message,MODx.grid.Grid,{
    read: function(exp,rec,body,ri) {
        var r = rec.data;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'read'
                ,id: r.id
            }
            ,listeners: {
            	'success': {fn:function(r) {
            		var r2 = this.getStore().getAt(ri);
            		r2.set('read',true);
            		r2.commit();
            	},scope:this}
            }
        });
    }
    ,markUnread: function(btn,e) {
        var rec = this.getSelectionModel().getSelected();
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'unread'
                ,id: rec.data.id
            }
            ,listeners: {
            	'success': {fn:function(r) {
            		rec.set('read',false);
            		rec.commit();
            	},scope:this}
            }
        });
    }
});
Ext.reg('grid-message',MODx.grid.Message);

/**
 * Generates the new message window.
 *  
 * @class MODx.window.CreateMessage
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-message-create
 */
MODx.window.CreateMessage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('message_create')
        ,url: MODx.config.connectors_url+'security/message.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'combo'
            ,fieldLabel: _('recipient_type')
            ,name: 'type'
            ,hiddenName: 'type'
            ,store: new Ext.data.SimpleStore({
                fields: ['type','disp']
                ,data: [['user',_('user')]
                       ,['usergroup',_('usergroup')]
                       ,['role',_('role')]
                       ,['all',_('all')]]
            })
            ,mode: 'local'
            ,triggerAction: 'all'
            ,displayField: 'disp'
            ,valueField: 'type'
            ,editable: false
            ,value: 'user'
            ,listeners: {
                'select': {fn:this.showRecipient,scope:this}
            }
        },{
            xtype: 'combo-user'
            ,id: 'mc-recipient-user'
            ,fieldLabel: _('user')
            ,allowBlank: true
        },{
            xtype: 'combo-usergroup'
            ,id: 'mc-recipient-usergroup'
            ,fieldLabel: _('usergroup')
            ,allowBlank: true
        },{
            xtype: 'combo-role'
            ,id: 'mc-recipient-role'
            ,fieldLabel: _('role')
            ,allowBlank: true
        },{
            xtype: 'hidden'
            ,id: 'mc-recipient-all'
            ,name: 'all'
            ,fieldLabel: _('all')
            ,value: 'all'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('subject')
            ,name: 'subject'
            ,maxLength: 255
            ,width: 300
        },{
            xtype: 'textarea'
            ,fieldLabel: _('message')
            ,name: 'message'
            ,width: 300
            ,grow: true
        }]
        ,listeners: {
            'show': {fn: this.initRecipient, scope: this}
        }
    });
    MODx.window.CreateMessage.superclass.constructor.call(this,config);    
};
Ext.extend(MODx.window.CreateMessage,MODx.Window,{
    tps: ['user','usergroup','role','all']
    
    ,initRecipient: function() {
        for (var i=1;i<this.tps.length;i++) {
            var f = this.fp.getForm().findField('mc-recipient-'+this.tps[i]);
            if (f) { this.hideField(f); }
        }
    }
    
    ,showRecipient: function(cb,rec,i) {
        for (var x=0;x<this.tps.length;x++) {
            var f = this.fp.getForm().findField('mc-recipient-'+this.tps[x]);
            if (f) { this.hideField(f); }
        }
        var type = rec.data.type;
        var fd = this.fp.getForm().findField('mc-recipient-'+type);
        if (fd) { this.showField(fd); }
    }
});
Ext.reg('window-message-create',MODx.window.CreateMessage);