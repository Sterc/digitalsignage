Ext.onReady(function() {
    MODx.load({xtype: 'digitalsignage-page-home'});
});

DigitalSignage.page.Home = function(config) {
    config = config || {};

    config.buttons = [];

    if (DigitalSignage.config.branding_url) {
        config.buttons.push({
            text        : 'Digital Signage ' + DigitalSignage.config.version,
            cls         : 'x-btn-branding',
            handler     : this.loadBranding
        });
    }

    if (DigitalSignage.config.permissions.admin) {
        config.buttons.push({
            text        : '<i class="icon icon-cogs"></i>' + _('digitalsignage.admin_view'),
            handler     : this.toAdminView,
            scope       : this
        }, '-');
    }

    if (DigitalSignage.config.branding_url_help) {
        config.buttons.push('-', {
            text        : _('help_ex'),
            handler     : MODx.loadHelpPane,
            scope       : this
        });
    }

    Ext.applyIf(config, {
        components  : [{
            xtype       : 'digitalsignage-panel-home',
            renderTo    : 'digitalsignage-panel-home-div'
        }]
    });

    DigitalSignage.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.page.Home, MODx.Component, {
    loadBranding: function(btn) {
        window.open(DigitalSignage.config.branding_url);
    },
    toAdminView: function() {
        MODx.loadPage('?a=admin&namespace=' + DigitalSignage.config.namespace);
    }
});

Ext.reg('digitalsignage-page-home', DigitalSignage.page.Home);