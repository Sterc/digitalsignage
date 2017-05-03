userManagement.panel.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-users'
        ,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('users')+'</h2>'
            ,border: false
            ,id: 'modx-users-header'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,items: [{
                html: '<p>'+_('user_management_msg')+'</p>'
                ,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'usermanagement-grid-users'
                ,cls:'main-wrapper'
                ,preventRender: true
            }]
        }]
    });
    userManagement.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(userManagement.panel.Home,MODx.FormPanel);
Ext.reg('usermanagement-panel-home',userManagement.panel.Home);