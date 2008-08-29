Ext.onReady(function() {
	MODx.load({ xtype: 'page-user-create' });
});

/**
 * Loads the create user page 
 * 
 * @class MODx.page.CreateUser
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-user-create
 */
MODx.page.CreateUser = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	   form: 'uf'
	   ,fields: {
            new_user_name: {
                xtype: 'textfield'
                ,width: 150
                ,maxLength: 100
                ,allowBlank: false
                ,applyTo: 'new_user_name'
            }
            ,password: {
                inputType: 'password'
                ,xtype: 'textfield'
                ,width: 175
                ,minLength: 6
                ,applyTo: 'password'
            }
            ,password_confirm: {
                inputType: 'password'
                ,xtype: 'textfield'
                ,width: 175
                ,minLength: 6
                ,applyTo: 'password_confirm'
            }
            ,fullname: {
                xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                ,applyTo: 'fullname'
            }
            ,email: {
                xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                ,allowBlank: false
                ,applyTo: 'email'
            }
            ,role: {
                xtype: 'combo-role'
                ,value: config.role || _('none')
                ,renderTo: 'role' 
            }
            ,phone: {
                xtype: 'textfield'
                ,width: 200
                ,maxLength: 255
                ,applyTo: 'phone'
            }
            ,mobilephone: {
                xtype: 'textfield'
                ,width: 200
                ,maxLength: 255
                ,applyTo: 'mobilephone'
            }
            ,fax: {
                xtype: 'textfield'
                ,width: 200
                ,maxLength: 255
                ,applyTo: 'fax'
            }
            ,state: {
                xtype: 'textfield'
                ,width: 100
                ,maxLength: 100
                ,applyTo: 'state'
            }
            ,zip: {
                xtype: 'textfield'
                ,width: 100
                ,maxLength: 25
                ,applyTo: 'zip'
            }
            ,country: {
                xtype: 'combo'
                ,listWidth: 200
                ,typeAhead: false
                ,forceSelection: false
                ,triggerAction: 'all'
                ,mode: 'local'
                ,transform: 'country'
            }
            ,dob: {
                xtype: 'datefield'
                ,width: 150
                ,allowBlank: true
                ,format: 'm-d-Y'
                ,applyTo: 'dob'
            }
            ,gender: {
                xtype: 'combo'
                ,listWidth: 200
                ,typeAhead: false
                ,triggerAction: 'all'
                ,mode: 'local'
                ,transform: 'gender'
            }
            ,comment: {
                xtype: 'textarea'
                ,width: 300
                ,grow: true
                ,applyTo: 'comment'
            }
            ,blocked: {
                xtype: 'checkbox'
                ,applyTo: 'blocked'
            }
            ,blockeduntil: {
                xtype: 'datefield'
                ,width: 150
                ,allowBlank: true
                ,format: 'm-d-Y'
                ,applyTo: 'blockeduntil'
            }
            ,blockedafter: {
                xtype: 'datefield'
                ,width: 150
                ,allowBlank: true
                ,format: 'm-d-Y'
                ,applyTo: 'blockedafter'
            }
	   }
	   ,actions: {
            'new': MODx.action['security/user/create']
            ,edit: MODx.action['security/user/update']
            ,cancel: MODx.action['security/user']
       }
       ,onComplete: function(o,itm,res) {
            if (Ext.get('password_notify_method_s').dom.checked == true) {
                var self = this;
                Ext.Msg.hide();
                Ext.Msg.show({
                    title: _('password_notification')
                    ,msg: res.message
                    ,buttons: Ext.Msg.OK
                    ,fn: function(btn) {
                        if (btn == 'ok') self.redirectStay(o,itm,res);
                        return false;
                    }
                });
            } else {
                this.redirectStay(o,itm,res);
            }
        }
        ,buttons: [{
            process: 'create', text: _('save'), method: 'remote'
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['security/user']}
        }]
        ,loadStay: true
        ,tabs: [
            {contentEl: 'tab_general', title: _('settings_general')}
            ,{contentEl: 'tab_access', title: _('access_permissions')}
        ]
	});
	MODx.page.CreateUser.superclass.constructor.call(this,config);
	
    Ext.get('specpassword').dom.style.display = 'none';
};
Ext.extend(MODx.page.CreateUser,MODx.Component);
Ext.reg('page-user-create',MODx.page.CreateUser);

function toggleNewPassword() {
	Ext.get('specpassword').dom.style.display = '';
	Ext.get('passwordgenmethod_g').dom.checked = true;
}