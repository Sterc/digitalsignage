Ext.onReady(function() {
	MODx.load({ xtype: 'statusoverrideips-page-home'});
});

StatusOverrideIPs.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        buttons: [{
            text: _('help_ex')
            ,handler: StatusOverrideIPs.loadHelp
        }]
		,components: [{
			xtype: 'statusoverrideips-panel-home'
			,renderTo: 'statusoverrideips-panel-home-div'
		}]
	});
    StatusOverrideIPs.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(StatusOverrideIPs.page.Home,MODx.Component);
Ext.reg('statusoverrideips-page-home',StatusOverrideIPs.page.Home);