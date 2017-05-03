Ext.onReady(function() {
    MODx.load({ xtype: 'usermanagement-page-update'});
});

userManagement.page.Update = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'usermanagement-panel-user'
        ,buttons: [{
            process: MODx.request.id ? 'mgr/user/update' : 'mgr/user/new'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            // ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            text: _('close')
            ,id: 'modx-abtn-cancel'
            ,handler: function() {
                MODx.loadPage(MODx.request.a);
            }
        }]
        ,components: [{
            xtype: 'usermanagement-panel-update'
            ,renderTo: 'usermanagement-panel-update-div'
        }]
    });
    userManagement.page.Update.superclass.constructor.call(this,config);
};
Ext.extend(userManagement.page.Update,MODx.Component);
Ext.reg('usermanagement-page-update',userManagement.page.Update);