Ext.onReady(function() {
	MODx.load({xtype: 'narrowcasting-page-home'});
});

Narrowcasting.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [];
	
	if (Narrowcasting.config.branding) {
		config.buttons.push({
			text 		: 'Narrowcasting ' + Narrowcasting.config.version,
			cls			: 'x-btn-branding',
			handler		: this.loadBranding
		});
	}
	
	config.buttons.push({
		text		: _('help_ex'),
		handler		: MODx.loadHelpPane,
		scope		: this
	});
	
	Ext.applyIf(config, {
		components	: [{
			xtype		: 'narrowcasting-panel-home',
			renderTo	: 'narrowcasting-panel-home-div'
		}]
	});
	
	Narrowcasting.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.page.Home, MODx.Component, {
	loadBranding: function(btn) {
		window.open(Narrowcasting.config.branding_url);
	}
});

Ext.reg('narrowcasting-page-home', Narrowcasting.page.Home);