

MODx.panel.User = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/user.php'
        ,baseParams: {}
        ,id: 'panel-user'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,bodyStyle: ''
        ,items: {
            xtype: 'tabpanel'
            ,activeTab: 0
            ,deferredRender: false
            ,border: false
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,labelWidth: 150
                ,bodyStyle: 'padding: 1.5em;'
            }
            ,items: this.getFields(config)
        }
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.User.superclass.constructor.call(this,config);
    Ext.getCmp('panel-newpassword').getEl().dom.style.display = 'none';
    Ext.getCmp('fld-password-genmethod-s').on('check',this.showNewPassword,this);
};
Ext.extend(MODx.panel.User,MODx.FormPanel,{
    setup: function() {
        if (this.config.user === '' || this.config.user === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.user
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    this.fireEvent('ready',r.object);
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('grid-user-settings');
        if (g) {
            Ext.apply(o.form.baseParams,{
                settings: g.encodeModified()
            });
        }
    }
    
    ,success: function(o) {
        if (Ext.getCmp('fld-passwordnotifymethod-s').getValue() === true && o.result.message != '') {
            Ext.Msg.hide();
            Ext.Msg.show({
                title: _('password_notification')
                ,msg: o.result.message
                ,buttons: Ext.Msg.OK
                ,fn: function(btn) {
                    if (btn == 'ok') { 
                        location.href = '?a='+MODx.action['security/user/update']+'&id='+o.result.object.id; 
                    }
                    return false;
                }
            });
        } else {
            location.href = '?a='+MODx.action['security/user/update']+'&id='+o.result.object.id;
        }
    }
    
    ,showNewPassword: function(cb,v) {
        var el = Ext.getCmp('panel-newpassword').getEl();
        if (v) {
            el.slideIn('t',{useDisplay:true});
        } else {
            el.slideOut('t',{useDisplay:true});
        }
    }
    
    ,getFields: function(config) {
        var f = [{
            title: _('general_information')
            ,defaults: { msgTarget: 'side' ,autoHeight: true }
            ,items: [{
                id: 'fld-id'
                ,name: 'id'
                ,xtype: 'hidden'
                ,value: config.user
            },{
                id: 'fld-newpassword'
                ,name: 'newpassword'
                ,xtype: 'hidden'
                ,value: false
            },{
                id: 'fs-newpassword'
                ,title: 'New Password'
                ,xtype: 'fieldset'
                ,checkboxToggle: true
                ,collapsed: (config.user ? true : false)
                ,listeners: {
                    'expand': {fn:function(p) {
                        Ext.getCmp('fld-newpassword').setValue(true);
                    },scope:this}
                    ,'collapse': {fn:function(p) {
                        Ext.getCmp('fld-newpassword').setValue(false);
                    },scope:this}
                }
                ,items: [{
                    xtype: 'radiogroup'
                    ,fieldLabel: _('password_method')
                    ,columns: 1
                    ,items: [{
                        id: 'fld-passwordnotifymethod-e'
                        ,name: 'passwordnotifymethod'
                        ,boxLabel: _('password_method_email')
                        ,xtype: 'radio'
                        ,value: 'e'
                    },{
                        id: 'fld-passwordnotifymethod-s'
                        ,name: 'passwordnotifymethod'
                        ,boxLabel: _('password_method_screen')
                        ,xtype: 'radio'
                        ,value: 's'
                        ,checked: true
                    }]
                },{
                    xtype: 'radiogroup'
                    ,fieldLabel: _('password_gen_method')
                    ,columns: 1
                    ,items: [{
                        id: 'fld-password-genmethod-g'
                        ,name: 'passwordgenmethod'
                        ,boxLabel: _('password_gen_gen')
                        ,xtype: 'radio'
                        ,value: 'g'
                        ,checked: true
                    },{
                        id: 'fld-password-genmethod-s'
                        ,name: 'passwordgenmethod'
                        ,boxLabel: _('password_gen_specify')
                        ,xtype: 'radio'
                        ,value: 'spec'
                    }]
                },{
                    id: 'panel-newpassword'
                    ,xtype: 'panel'
                    ,layout: 'form'
                    ,border: false
                    ,autoHeight: true
                    ,items: [{
                        id: 'fld-specifiedpassword'
                        ,name: 'specifiedpassword'
                        ,fieldLabel: _('change_password_new')
                        ,xtype: 'textfield'
                        ,inputType: 'password'
                        ,width: 175
                        ,minLength: 6
                    },{
                        id: 'fld-confirmpassword'
                        ,name: 'confirmpassword'
                        ,fieldLabel: _('change_password_confirm')
                        ,xtype: 'textfield'
                        ,inputType: 'password'
                        ,width: 175
                        ,minLength: 6
                    }]
                }]
            },{
                id: 'fs-general'
                ,title: 'General Information'
                ,xtype: 'fieldset'
                ,items: [{
                    id: 'fld-username'
                    ,name: 'username'
                    ,fieldLabel: _('username')
                    ,xtype: 'textfield'
                },{
                    id: 'fld-fullname'
                    ,name: 'fullname'
                    ,fieldLabel: _('user_full_name')
                    ,xtype: 'textfield'
                    ,width: 300
                    ,maxLength: 255
                },{
                    id: 'fld-email'
                    ,name: 'email'
                    ,fieldLabel: _('user_email')
                    ,xtype: 'textfield'
                    ,width: 300
                    ,maxLength: 255
                    ,allowBlank: false
                },{
                    id: 'fld-role'
                    ,name: 'role'
                    ,fieldLabel: _('role')
                    ,xtype: 'combo-role'
                    ,value: config.role || _('none')
                },{
                    id: 'fld-phone'
                    ,name: 'phone'
                    ,fieldLabel: _('user_phone')
                    ,xtype: 'textfield'
                    ,width: 200
                    ,maxLength: 255
                },{
                    id: 'fld-mobilephone'
                    ,name: 'mobilephone'
                    ,fieldLabel: _('user_mobile')
                    ,xtype: 'textfield'
                    ,width: 200
                    ,maxLength: 255
                },{
                    id: 'fld-fax'
                    ,name: 'fax'
                    ,fieldLabel: _('user_fax')
                    ,xtype: 'textfield'
                    ,width: 200
                    ,maxLength: 255
                },{
                    id: 'fld-state'
                    ,name: 'state'
                    ,fieldLabel: _('user_state')
                    ,xtype: 'textfield'
                    ,width: 100
                    ,maxLength: 100
                },{
                    id: 'fld-zip'
                    ,name: 'zip'
                    ,fieldLabel: _('user_zip')
                    ,xtype: 'textfield'
                    ,width: 100
                    ,maxLength: 25
                },{
                    id: 'fld-country'
                    ,fieldLabel: _('user_country')
                    ,xtype: 'combo-country'
                },{
                    id: 'fld-dob'
                    ,name: 'dob'
                    ,fieldLabel: _('user_dob')
                    ,xtype: 'datefield'
                    ,width: 150
                    ,allowBlank: true
                    ,format: 'm-d-Y'
                },{
                    id: 'fld-gender'
                    ,name: 'gender'
                    ,fieldLabel: _('user_gender')
                    ,xtype: 'combo-gender'
                },{
                    id: 'fld-comment'
                    ,name: 'comment'
                    ,fieldLabel: _('comment')
                    ,xtype: 'textarea'
                    ,width: 300
                    ,grow: true
                }]
            },{
                id: 'fs-blocked'
                ,title: 'Login Options'
                ,xtype: 'fieldset'
                ,items: [{
                    id: 'fld-logincount'
                    ,name: 'logincount'
                    ,fieldLabel: _('user_logincount')
                    ,xtype: 'statictextfield'
                },{
                    id: 'fld-lastlogin'
                    ,name: 'lastlogin'
                    ,fieldLabel: _('user_prevlogin')
                    ,xtype: 'statictextfield'
                },{
                    id: 'fld-failedlogincount'
                    ,name: 'failedlogincount'
                    ,fieldLabel: _('user_failedlogincount')
                    ,xtype: 'textfield'
                },{
                    id: 'fld-blocked'
                    ,name: 'blocked'
                    ,fieldLabel: _('user_block')
                    ,xtype: 'checkbox'
                },{
                    id: 'fld-blockeduntil'
                    ,name: 'blockeduntil'
                    ,fieldLabel: _('user_blockeduntil')
                    ,xtype: 'datefield'
                    ,width: 150
                    ,allowBlank: true
                    ,format: 'm-d-Y'
                },{
                    id: 'fld-blockedafter'
                    ,name: 'blockedafter'
                    ,fieldLabel: _('user_blockedafter')
                    ,xtype: 'datefield'
                    ,width: 150
                    ,allowBlank: true
                    ,format: 'm-d-Y'
                }]
            }]
        }];
        if (config.user != 0) {
            f.push({
                title: _('settings')
                ,items: [{
                    html: '<h3>'+'User Settings'+'</h3>'
                    ,border: false
                },{
                    html: '<p>'+'Here you can change specific settings for the user.'+'</p>'
                    ,border: false
                },{
                    xtype: 'grid-user-settings'
                    ,id: 'grid-user-settings'
                    ,preventRender: true
                    ,user: config.user
                }]
            })
        }                
        f.push({
            contentEl: 'tab_access'
            ,title: _('access_permissions')
        });
        return f;
    }
});
Ext.reg('panel-user',MODx.panel.User);




/**
 * Displays a gender combo
 * 
 * @class MODx.combo.Gender
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype combo-gender
 */
MODx.combo.Gender = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [['',0],[_('user_male'),1],[_('user_female'),2]]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
    });
    MODx.combo.Gender.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Gender,MODx.combo.ComboBox);
Ext.reg('combo-gender',MODx.combo.Gender);