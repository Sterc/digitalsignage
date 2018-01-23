Ext.onReady(function() {
    MODx.load({xtype: 'digitalsignage-page-admin'});
});

DigitalSignage.page.Admin = function(config) {
    config = config || {};

    config.buttons = [];

    if (DigitalSignage.config.branding_url) {
        config.buttons.push({
            text        : 'DigitalSignage ' + DigitalSignage.config.version,
            cls         : 'x-btn-branding',
            handler     : this.loadBranding
        });
    }

    config.buttons.push({
        text        : _('digitalsignage.default_view'),
        handler     : this.toDefaultView,
        scope       : this
    });

    if (DigitalSignage.config.branding_url_help) {
        config.buttons.push('-', {
            text        : _('help_ex'),
            handler     : MODx.loadHelpPane,
            scope       : this
        });
    }

    Ext.applyIf(config, {
        components  : [{
            xtype       : 'digitalsignage-panel-admin',
            renderTo    : 'digitalsignage-panel-admin-div'
        }]
    });

    DigitalSignage.page.Admin.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.page.Admin, MODx.Component, {
    loadBranding: function(btn) {
        window.open(DigitalSignage.config.branding_url);
    },
    toDefaultView: function() {
        var request = MODx.request || {};

        Ext.apply(request, {
            'a' : 'home'
        });

        MODx.loadPage('?' + Ext.urlEncode(request));
    }
});

Ext.reg('digitalsignage-page-admin', DigitalSignage.page.Admin);