var DigitalSignage = function(config) {
    config = config || {};

    DigitalSignage.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage, Ext.Component, {
    page    : {},
    window  : {},
    grid    : {},
    tree    : {},
    panel   : {},
    combo   : {},
    config  : {},
    viewCalendar: function(btn, e) {
        if (this.viewCalendarWindow) {
            this.viewCalendarWindow.destroy();
        }

        this.viewCalendarWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-broadcast-view-calendar',
            closeAction : 'close'
        });

        this.viewCalendarWindow.show(e.target);
    },
    loadRTE : function(id, config) {
        if (!Ext.isEmpty(MODx.config.which_editor)) {
            config = config || {};

            Ext.applyIf(config, {
                selector    : '#' + id,
                plugins     : MODx.config['digitalsignage.editor_plugins'],
                menubar     : MODx.config['digitalsignage.editor_menubar'],
                statusbar   : parseInt(MODx.config['digitalsignage.editor_statusbar']) === 1,
                toolbar1    : MODx.config['digitalsignage.editor_toolbar1'],
                toolbar2    : MODx.config['digitalsignage.editor_toolbar2'],
                toolbar3    : MODx.config['digitalsignage.editor_toolbar3'],
                height      : 200
            });

            if (MODx.config.which_editor === 'TinyMCE RTE') {
                config.skin = MODx.config['tinymcerte.skin'];

                new TinyMCERTE.Tiny({
                    allowDrop : true
                }, config);
            } else {
                MODx.loadRTE(id, config);
            }
        }
    }
});

Ext.reg('digitalsignage', DigitalSignage);

DigitalSignage = new DigitalSignage();