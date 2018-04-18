DigitalSignage.panel.Home = function(config) {
    config = config || {};

    Ext.apply(config, {
        id          : 'digitalsignage-panel-home',
        cls         : 'container',
        defaults    : {
            autoHeight  : true
        },
        items       : [{
            html        : '<h2>' + _('digitalsignage') + '</h2>',
            cls         : 'modx-page-header'
        }, {
            xtype       : 'modx-tabs',
            items       : [{
                layout      : 'form',
                title       : _('digitalsignage.broadcasts'),
                defaults    : {
                    autoHeight  : true
                },
                items       : [{
                    html            : '<p>' + _('digitalsignage.broadcasts_desc') + '</p>',
                    bodyCssClass    : 'panel-desc'
                }, {
                    xtype           : 'digitalsignage-grid-broadcasts',
                    cls             : 'main-wrapper',
                    preventRender   : true,
                    refreshGrid     : ['digitalsignage-grid-slides']
                }]
            }, {
                layout      : 'form',
                title       : _('digitalsignage.slides'),
                defaults    : {
                    autoHeight  : true
                },
                items       : [{
                    html            : '<p>' + _('digitalsignage.slides_desc') + '</p>',
                    bodyCssClass    : 'panel-desc'
                }, {
                    xtype           : 'digitalsignage-grid-slides',
                    cls             : 'main-wrapper',
                    preventRender   : true,
                    refreshGrid     : ['digitalsignage-grid-broadcasts']
                }]
            }, {
                layout      : 'form',
                title       : _('digitalsignage.players'),
                defaults    : {
                    autoHeight  : true
                },
                items       : [{
                    html            : '<p>' + _('digitalsignage.players_desc') + '</p>',
                    bodyCssClass    : 'panel-desc'
                }, {
                    xtype           : 'digitalsignage-grid-players',
                    cls             : 'main-wrapper',
                    preventRender   : true,
                    refreshGrid     : ['digitalsignage-grid-broadcasts']
                }]
            }]
        }]
    });

    DigitalSignage.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.panel.Home, MODx.FormPanel);

Ext.reg('digitalsignage-panel-home', DigitalSignage.panel.Home);