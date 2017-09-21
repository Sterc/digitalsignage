Ext.onReady(function() {
	MODx.load({xtype: 'narrowcasting-page-home'});
});

Narrowcasting.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [];

    if (Narrowcasting.config.branding_url) {
        config.buttons.push({
			text 		: 'Narrowcasting ' + Narrowcasting.config.version,
			cls			: 'x-btn-branding',
			handler		: this.loadBranding
		});
    }
	
	if (Narrowcasting.config.has_permission) {
		config.buttons.push({
			text		: _('narrowcasting.admin_view'),
			handler		: this.toAdminView,
			scope		: this
		}, '-');
	}

    if (Narrowcasting.config.branding_url_help) {
        config.buttons.push('-', {
            text		: _('help_ex'),
            handler		: MODx.loadHelpPane,
            scope		: this
        });
    }
	
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
	},
	toAdminView: function() {
		var request = MODx.request || {};
		
        Ext.apply(request, {
	    	'action' : 'admin'  
	    });
        
        MODx.loadPage('?' + Ext.urlEncode(request));
	}
});

Ext.reg('narrowcasting-page-home', Narrowcasting.page.Home);