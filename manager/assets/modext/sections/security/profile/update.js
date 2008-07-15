Ext.namespace('MODx','MODx.profile');

/**
 * Loads the profile page
 * 
 * @class MODx.Profile
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-profile
 */
MODx.Profile = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        tabs: [
            {contentEl: 'tab_information', title: _('information')}
            ,{contentEl: 'tab_stats', title: _('statistics')}
        ]
        ,components: [{
            xtype: 'panel-profile-update'
            ,renderTo: 'info_panel'
            ,user: config.user
        },{
            xtype: 'panel-password-change'
            ,renderTo: 'password_change_panel'
            ,user: config.user
        },{
            xtype: 'grid-user-recent-resource'
            ,renderTo: 'grid-recent-resource'
            ,user: config.user
        }]
	});
	MODx.Profile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Profile,MODx.Component);
Ext.reg('modx-profile',MODx.Profile);

/**
 * The information panel for the profile
 * 
 * @class MODx.profile.Update
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype: panel-profile-update
 */
MODx.profile.Update = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('general_information')
        ,url: MODx.config.connectors_url+'security/profile.php'
        ,baseParams: {
            action: 'update'
            ,id: config.user
        }
        ,frame: true
        ,items: [{
            xtype: 'textfield'
            ,fieldLabel: _('user_full_name')
            ,name: 'fullname'
            ,width: 250
            ,maxLength: 255
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('email')
            ,name: 'email'
            ,vtype: 'email'
            ,width: 250
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_phone')
            ,name: 'phone'
            ,width: 150
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_mobile')
            ,name: 'mobilephone'
            ,width: 150
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_fax')
            ,name: 'fax'
            ,width: 150
        },{
            xtype: 'datefield'
            ,fieldLabel: _('user_dob')
            ,name: 'dob'
            ,width: 150
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_state')
            ,name: 'state'
            ,maxLength: 50
            ,width: 80
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_zip')
            ,name: 'zip'
            ,maxLength: 20
            ,width: 80
        }]
        ,buttons: [{
            text: _('save')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.profile.Update.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.profile.Update,MODx.FormPanel,{
    setup: function() {
        Ext.Ajax.request({
            url: MODx.config.connectors_url+'security/user.php'
            ,params: {
                action: 'get'
                ,id: this.config.user
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    this.getForm().setValues(r.object);
                } else FormHandler.errorJSON(r);
            }
        })
    }
});
Ext.reg('panel-profile-update',MODx.profile.Update);

/**
 * A panel for changing the user password
 * 
 * @class MODx.profile.ChangePassword
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-password-change
 */
MODx.profile.ChangePassword = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('reset_password')
        ,url: MODx.config.connectors_url+'security/profile.php'
        ,baseParams: {
            action: 'changepassword'
            ,id: config.user
        }
        ,frame: true
        ,items: [{
            xtype: 'checkbox'
            ,fieldLabel: _('reset_password')
            ,name: 'password_reset'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('password_old')
            ,name: 'password_old'
            ,inputType: 'password'
            ,maxLength: 255
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('password')
            ,name: 'password_new'
            ,inputType: 'password'
            ,maxLength: 255
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('password_confirm')
            ,name: 'password_confirm'
            ,inputType: 'password'
            ,maxLength: 255
            ,width: 300
        }]
        ,buttons: [{
            text: _('save')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.profile.ChangePassword.superclass.constructor.call(this,config);
};
Ext.extend(MODx.profile.ChangePassword,MODx.FormPanel);
Ext.reg('panel-password-change',MODx.profile.ChangePassword);