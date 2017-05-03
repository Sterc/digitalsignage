Ext.onReady(function() {
    MODx.load({ xtype: 'usermanagement-page-home'});
});

userManagement.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'usermanagement-panel-home'
            ,renderTo: 'usermanagement-panel-home-div'
        }]
    });
    userManagement.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(userManagement.page.Home,MODx.Component);
Ext.reg('usermanagement-page-home',userManagement.page.Home);