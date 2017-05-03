var StatusOverrideIPs = function(config) {
    config = config || {};
    StatusOverrideIPs.superclass.constructor.call(this,config);
};
Ext.extend(StatusOverrideIPs,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},
    loadHelp: function() {
        window.open('https://github.com/jkenters/StatusOverrideIPs/blob/master/README');
    }
});
Ext.reg('statusoverrideips',StatusOverrideIPs);
StatusOverrideIPs = new StatusOverrideIPs();