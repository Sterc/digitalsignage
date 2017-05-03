var userManagement = function(config) {
    config = config || {};
    userManagement.superclass.constructor.call(this,config);
};
Ext.extend(userManagement,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('usermanagement',userManagement);
userManagement = new userManagement();