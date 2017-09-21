Ext.onReady(function() {
	MODx.load({xtype: 'narrowcasting-page-admin'});
});

Narrowcasting.page.Admin = function(config) {
	config = config || {};
	
	config.buttons = [];

    if (Narrowcasting.config.branding_url) {
		config.buttons.push({
			text 		: 'Narrowcasting ' + Narrowcasting.config.version,
			cls			: 'x-btn-branding',
			handler		: this.loadBranding
		});
	}
	
	config.buttons.push({
		text		: _('narrowcasting.default_view'),
		handler		: this.toDefaultView,
		scope		: this
	});

    if (Narrowcasting.config.branding_url_help) {
        config.buttons.push('-', {
            text		: _('help_ex'),
            handler		: MODx.loadHelpPane,
            scope		: this
        });
    }
	
	Ext.applyIf(config, {
		components	: [{
			xtype		: 'narrowcasting-panel-admin',
			renderTo	: 'narrowcasting-panel-admin-div'
		}]
	});
	
	Narrowcasting.page.Admin.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.page.Admin, MODx.Component, {
	loadBranding: function(btn) {
		window.open(Narrowcasting.config.branding_url);
	},
	toDefaultView: function() {
		var request = MODx.request || {};
		
        Ext.apply(request, {
	    	'action' : 'home'  
	    });
        
        MODx.loadPage('?' + Ext.urlEncode(request));
	}
});

Ext.reg('narrowcasting-page-admin', Narrowcasting.page.Admin);