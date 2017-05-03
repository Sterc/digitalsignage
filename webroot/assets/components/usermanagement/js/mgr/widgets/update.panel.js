/**
 * @class MODx.panel.User
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-user
 */
userManagement.panel.User = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: userManagement.config.connector_url
        ,baseParams: {}
        ,id: 'usermanagement-panel-user'
        ,cls: 'container'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,bodyStyle: ''
        ,items: [{
             html: '<h2>'+_('user_new')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-user-header'
        },{
            xtype: 'modx-tabs'
            ,id: 'modx-user-tabs'
            ,deferredRender: false
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,labelWidth: 150
                ,bodyCssClass: 'tab-panel-wrapper'
                ,layoutOnTabChange: true
            }
            ,items: this.getFields(config)
        }]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    userManagement.panel.User.superclass.constructor.call(this,config);
    Ext.getCmp('modx-user-panel-newpassword').getEl().dom.style.display = 'none';
    Ext.getCmp('modx-user-password-genmethod-s').on('check',this.showNewPassword,this);
};
Ext.extend(userManagement.panel.User,MODx.FormPanel,{
    setup: function() {
        if (MODx.request.id === '' || MODx.request.id === '0' || MODx.request.id === 0 || typeof MODx.request.id === 'undefined') {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: userManagement.config.connector_url
            ,params: {
                action: 'mgr/user/get'
                ,id: MODx.request.id
                ,getGroups: false
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);

                    var d = Ext.decode(r.object.groups);
                    var g = Ext.getCmp('modx-grid-user-groups');
                    if (g) {
                        var s = g.getStore();
                        if (s) { s.loadData(d); }
                    }
                    Ext.get('modx-user-header').update('<h2>'+_('user')+': '+r.object.username+'</h2>');
                    this.fireEvent('ready',r.object);
                    MODx.fireEvent('ready');
                },scope:this}
            }
        });
    }

    ,success: function(o) {
        var userId = MODx.request.id ? MODx.request.id : 0;
        if (Ext.getCmp('modx-user-passwordnotifymethod-s').getValue() === true && o.result.message != '') {
            Ext.Msg.hide();
            Ext.Msg.show({
                title: _('password_notification')
                ,msg: o.result.message
                ,buttons: Ext.Msg.OK
                ,fn: function(btn) {
                    if (userId == 0) {
                        MODx.loadPage(MODx.request.a, "&action=update&id=" + o.result.object.id);
                    }
                    return false;
                }
            });
            this.clearDirty();
        } else if (userId == 0) {
            MODx.loadPage(MODx.request.a, "&action=update&id=" + o.result.object.id);
        }
    }
    ,beforeSubmit: function(o) {
        var d = {};
        var h = Ext.getCmp('modx-grid-user-groups');
        if (h) { d.groups = h.encode(); }
        Ext.apply(o.form.baseParams,d);
    }
    ,showNewPassword: function(cb,v) {
        var el = Ext.getCmp('modx-user-panel-newpassword').getEl();
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
            ,cls: 'main-wrapper form-with-labels'
            ,labelAlign: 'top' // prevent default class of x-form-label-left
            ,items: this.getGeneralFields(config)
        }];
        f.push({
            title: _('access_permissions')
            ,layout: 'form'
            ,defaults: { border: false ,autoHeight: true }
            ,hideMode: 'offsets'
            ,items: [{
                xtype: 'usermanagement-grid-user-groups'
                ,cls: 'main-wrapper'
                ,title: ''
                ,preventRender: true
                ,user: config.user
                ,width: '97%'
                ,listeners: {
                    'afterRemoveRow':{fn:this.markDirty,scope:this}
                    ,'afterUpdateRole':{fn:this.markDirty,scope:this}
                    ,'afterAddGroup':{fn:this.markDirty,scope:this}
                    ,'afterReorderGroup':{fn:this.markDirty,scope:this}
                }
            }]
        });
        return f;
    }

    ,getGeneralFields: function(config) {
        return [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .5
                ,defaults: {
                    msgTarget: 'under'
                }
                ,items: [{
                    id: 'modx-user-id'
                    ,name: 'id'
                    ,xtype: 'hidden'
                    ,value: config.user
                },/*{
                    id: 'modx-user-gender'
                    ,name: 'gender'
                    ,hiddenName: 'gender'
                    ,fieldLabel: _('user_gender')
                    ,xtype: 'modx-combo-gender'
                    ,width: 200
                },*/{
                    id: 'modx-user-username'
                    ,name: 'username'
                    ,fieldLabel: _('username')+'<span style="font-size: 11px;font-weight: normal;padding-left: 10px;font-style: italic;">'+_('usermanagement.username_desc')+'</span>'
                    ,description: _('user_username_desc')
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,regex: /[a-zA-Z0-9]+/
                    ,maskRe: /([a-zA-Z0-9]+)$/
                },{
                    id: 'modx-user-fullname'
                    ,name: 'fullname'
                    ,fieldLabel: _('user_full_name')
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,maxLength: 255
                },{
                    id: 'modx-user-email'
                    ,name: 'email'
                    ,fieldLabel: _('user_email')
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,maxLength: 255
                    ,allowBlank: false
                    ,vtype: 'email'
                }/*,{
                    id: 'modx-user-dob'
                    ,name: 'dob'
                    ,fieldLabel: _('user_dob')
                    ,xtype: 'datefield'
                    ,width: 200
                    ,allowBlank: true
                    ,format: MODx.config.manager_date_format
                }*/,{
                    id: 'modx-user-fs-newpassword'
                    ,title: _('password_new')
                    ,xtype: 'fieldset'
                    ,cls: 'x-fieldset-checkbox-toggle' // add a custom class for checkbox replacement
                    ,checkboxToggle: true
                    ,collapsed: (MODx.request.id ? true : false)
                    ,forceLayout: true
                    ,listeners: {
                        'expand': {fn:function(p) {
                            Ext.getCmp('modx-user-newpassword').setValue(true);
                            this.markDirty();
                        },scope:this}
                        ,'collapse': {fn:function(p) {
                            Ext.getCmp('modx-user-newpassword').setValue(false);
                            this.markDirty();
                        },scope:this}
                    }
                    ,items: [{
                        xtype: 'radiogroup'
                        ,fieldLabel: _('password_method')
                        ,columns: 1
                        ,hidden:true
                        ,items: [{
                            id: 'modx-user-passwordnotifymethod-e'
                            ,name: 'passwordnotifymethod'
                            ,boxLabel: _('password_method_email')
                            ,xtype: 'radio'
                            ,value: 'e'
                            ,inputValue: 'e'
                        },{
                            id: 'modx-user-passwordnotifymethod-s'
                            ,name: 'passwordnotifymethod'
                            ,boxLabel: _('password_method_screen')
                            ,xtype: 'radio'
                            ,value: 's'
                            ,inputValue: 's'
                            ,checked: true
                        }]
                    },{
                        xtype: 'radiogroup'
                        ,fieldLabel: _('password_gen_method')
                        ,columns: 1
                        ,items: [{
                            id: 'modx-user-password-genmethod-g'
                            ,name: 'passwordgenmethod'
                            ,boxLabel: _('password_gen_gen')
                            ,xtype: 'radio'
                            ,inputValue: 'g'
                            ,value: 'g'
                            ,checked: true
                        },{
                            id: 'modx-user-password-genmethod-s'
                            ,name: 'passwordgenmethod'
                            ,boxLabel: _('password_gen_specify')
                            ,xtype: 'radio'
                            ,inputValue: 'spec'
                            ,value: 'spec'
                        }]
                    },{
                        id: 'modx-user-panel-newpassword'
                        ,xtype: 'panel'
                        ,layout: 'form'
                        ,border: false
                        ,autoHeight: true
                        ,style: 'padding-top: 15px' // nested form, add padding-top as the label will not have it
                        ,items: [{
                            id: 'modx-user-specifiedpassword'
                            ,name: 'specifiedpassword'
                            ,fieldLabel: _('change_password_new')
                            ,xtype: 'textfield'
                            ,inputType: 'password'
                            ,anchor: '100%'
                            ,msgTarget: 'under'
                        },{
                            id: 'modx-user-confirmpassword'
                            ,name: 'confirmpassword'
                            ,fieldLabel: _('change_password_confirm')
                            ,xtype: 'textfield'
                            ,inputType: 'password'
                            ,anchor: '100%'
                            ,msgTarget: 'under'
                        }]
                    }]
                }]
            },{
                columnWidth: .5
                ,defaults: {
                    msgTarget: 'under'
                }
                ,items: [{
                    id: 'modx-user-newpassword'
                    ,name: 'newpassword'
                    ,xtype: 'hidden'
                    ,value: false
                },{
                    id: 'modx-user-primary-group'
                    ,name: 'primary_group'
                    ,xtype: 'hidden'
                },{
                    id: 'modx-user-active'
                    ,name: 'active'
                    ,hideLabel: true
                    ,boxLabel: _('active')
                    ,description: _('user_active_desc')
                    ,xtype: 'xcheckbox'
                    ,inputValue: 1
                },{
                    id: 'modx-user-blocked'
                    ,name: 'blocked'
                    ,hideLabel: true
                    ,boxLabel: _('user_block')
                    ,description: _('user_block_desc')
                    ,xtype: 'xcheckbox'
                    ,inputValue: 1
                }/*,{
                    id: 'modx-user-blockeduntil'
                    ,name: 'blockeduntil'
                    ,fieldLabel: _('user_blockeduntil')
                    ,description: _('user_blockeduntil_desc')
                    ,xtype: 'xdatetime'
                    ,width: 300
                    ,timeWidth: 150
                    ,dateWidth: 150
                    ,allowBlank: true
                    ,dateFormat: MODx.config.manager_date_format
                    ,timeFormat: MODx.config.manager_time_format
                    ,hiddenFormat: 'Y-m-d H:i:s'
                },{
                    id: 'modx-user-blockedafter'
                    ,name: 'blockedafter'
                    ,fieldLabel: _('user_blockedafter')
                    ,description: _('user_blockedafter_desc')
                    ,xtype: 'xdatetime'
                    ,width: 300
                    ,timeWidth: 150
                    ,dateWidth: 150
                    ,allowBlank: true
                    ,dateFormat: MODx.config.manager_date_format
                    ,timeFormat: MODx.config.manager_time_format
                    ,hiddenFormat: 'Y-m-d H:i:s'
                }*/,{
                    id: 'modx-user-logincount'
                    ,name: 'logincount'
                    ,fieldLabel: _('user_logincount')
                    ,description: _('user_logincount_desc')
                    ,xtype: 'statictextfield'
                },{
                    id: 'modx-user-lastlogin'
                    ,name: 'lastlogin'
                    ,fieldLabel: _('user_prevlogin')
                    ,description: _('user_prevlogin_desc')
                    ,xtype: 'statictextfield'
                },{
                    id: 'modx-user-failedlogincount'
                    ,name: 'failedlogincount'
                    ,fieldLabel: _('user_failedlogincount')
                    ,description: _('user_failedlogincount_desc')
                    ,xtype: 'statictextfield'
                }]
            }]
        }];
    }
});
Ext.reg('usermanagement-panel-update',userManagement.panel.User);

/**
 * Displays a gender combo
 *
 * @class MODx.combo.Gender
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-gender
 */
MODx.combo.Gender = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [['',0],[_('user_male'),1],[_('user_female'),2],[_('user_other'),3]]
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
Ext.extend(MODx.combo.Gender,Ext.form.ComboBox);
Ext.reg('modx-combo-gender',MODx.combo.Gender);