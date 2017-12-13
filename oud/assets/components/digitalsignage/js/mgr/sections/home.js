Ext.onReady(function() {
	MODx.load({xtype: 'digitalsignage-page-home'});
});

DigitalSignage.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [];

    if (DigitalSignage.config.branding_url) {
        config.buttons.push({
			text 		: 'DigitalSignage ' + DigitalSignage.config.version,
			cls			: 'x-btn-branding',
			handler		: this.loadBranding
		});
    }
	
	if (DigitalSignage.config.has_permission) {
		config.buttons.push({
			text		: _('digitalsignage.admin_view'),
			handler		: this.toAdminView,
			scope		: this
		}, '-');
	}

    if (DigitalSignage.config.branding_url_help) {
        config.buttons.push('-', {
            text		: _('help_ex'),
            handler		: MODx.loadHelpPane,
            scope		: this
        });
    }
	
	Ext.applyIf(config, {
		components	: [{
			xtype		: 'digitalsignage-panel-home',
			renderTo	: 'digitalsignage-panel-home-div'
		}]
	});
	
	DigitalSignage.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.page.Home, MODx.Component, {
	loadBranding: function(btn) {
		window.open(DigitalSignage.config.branding_url);
	},
	toAdminView: function() {
		var request = MODx.request || {};
		
        Ext.apply(request, {
	    	'action' : 'admin'  
	    });
        
        MODx.loadPage('?' + Ext.urlEncode(request));
	}
});

Ext.reg('digitalsignage-page-home', DigitalSignage.page.Home);