DigitalSignage.grid.Broadcasts = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('digitalsignage.broadcast_create'),
        cls         : 'primary-button',
        handler     : this.createBroadcast,
        scope       : this
    }, {
        text        : _('bulk_actions'),
        menu        : [{
            text        : '<i class="x-menu-item-icon icon icon-refresh"></i> ' + _('digitalsignage.broadcasts_sync_selected'),
            handler     : this.syncSelectedBroadcasts,
            scope       : this
        }]
    }, {
        xtype       : 'checkbox',
        name        : 'digitalsignage-filter-broadcasts-refresh',
        id          : 'digitalsignage-filter-broadcasts-refresh',
        boxLabel    : _('digitalsignage.auto_refresh_grid'),
        checked     : true,
        listeners   : {
            'check'     : {
                fn          : this.autoRefresh,
                scope       : this
            },
            'afterrender'   : {
                fn          : this.autoRefresh,
                scope       : this
            }
        }
    }, '->', {
        xtype       : 'textfield',
        name        : 'digitalsignage-filter-broadcasts-search',
        id          : 'digitalsignage-filter-broadcasts-search',
        emptyText   : _('search') + '...',
        listeners   : {
            'change'    : {
                fn          : this.filterSearch,
                scope       : this
            },
            'render'    : {
                fn          : function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key     : Ext.EventObject.ENTER,
                        fn      : this.blur,
                        scope   : cmp
                    });
                },
                scope       : this
            }
        }
    }, {
        xtype       : 'button',
        cls         : 'x-form-filter-clear',
        id          : 'digitalsignage-filter-broadcasts-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];

    var sm = new Ext.grid.CheckboxSelectionModel();
    
    var columns = new Ext.grid.ColumnModel({
        columns: [sm, {
            header      : _('digitalsignage.label_broadcast_name'),
            dataIndex   : 'name_formatted',
            sortable    : true,
            editable    : false,
            width       : 250
        }, {
            header      : _('digitalsignage.label_broadcast_players'),
            dataIndex   : null,
            sortable    : true,
            editable    : false,
            width       : 250,
            fixed       : true,
            renderer    : this.renderPlayers
        }, {
            header      : _('digitalsignage.label_broadcast_slides'),
            dataIndex   : 'slides',
            sortable    : true,
            editable    : false,
            width       : 100,
            fixed       : true
        }, {
            header      : _('digitalsignage.label_broadcast_feeds'),
            dataIndex   : 'feeds',
            sortable    : true,
            editable    : false,
            width       : 100,
            fixed       : true
        }, {
            header      : _('digitalsignage.label_broadcast_last_sync'),
            dataIndex   : 'sync',
            sortable    : true,
            editable    : false,
            width       : 200,
            fixed       : true,
            renderer    : this.renderSync
        }]
    });
    
    Ext.applyIf(config, {
        sm          : sm,
    	cm          : columns,
        id          : 'digitalsignage-grid-broadcasts',
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/getlist'
        },
        fields      : ['id', 'resource_id', 'ticker_url', 'name', 'name_formatted', 'description', 'template', 'editedon', 'url', 'slides', 'feeds', 'players', 'sync'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy      : 'id',
        refreshGrid : [],
        refresher   : {
            timer       : null,
            duration    : 30,
            count       : 0
        }
    });
    
    DigitalSignage.grid.Broadcasts.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.grid.Broadcasts, MODx.grid.Grid, {
    autoRefresh: function(tf, nv) {
        if (tf.getValue()) {
            this.config.refresher.timer = setInterval((function() {
                tf.setBoxLabel(_('digitalsignage.auto_refresh_grid') + ' (' + (this.config.refresher.duration - this.config.refresher.count) + ')');

                if (0 == (this.config.refresher.duration - this.config.refresher.count)) {
                    this.config.refresher.count = 0;

                    this.refresh();
                } else {
                    this.config.refresher.count++;
                }
            }).bind(this), 1000);
        } else {
            clearInterval(this.config.refresher.timer);
        }
    },
    refreshGrids: function() {
        if ('string' == typeof this.config.refreshGrid) {
            Ext.getCmp(this.config.refreshGrid).refresh();
        } else {
            for (var i = 0; i < this.config.refreshGrid.length; i++) {
                Ext.getCmp(this.config.refreshGrid[i]).refresh();
            }
        }
    },
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.query = '';

        Ext.getCmp('digitalsignage-filter-broadcasts-search').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
            text    : '<i class="x-menu-item-icon icon icon-eye"></i>' + _('digitalsignage.broadcast_preview'),
            handler : this.previewBroadcast,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-refresh"></i> ' + _('digitalsignage.broadcast_sync'),
            handler : this.syncBroadcast,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-file"></i> ' + _('digitalsignage.broadcast_slides'),
            handler : this.viewSlides,
            scope   : this
        }, {
            text    : '<i class="x-menu-item-icon icon icon-rss"></i> ' + _('digitalsignage.broadcast_feeds'),
            handler : this.viewFeeds,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-edit"></i>' + _('digitalsignage.broadcast_update'),
            handler : this.updateBroadcast,
            scope   : this
        }, {
            text    : '<i class="x-menu-item-icon icon icon-copy"></i>' + _('digitalsignage.broadcast_duplicate'),
            handler : this.duplicateBroadcast,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('digitalsignage.broadcast_remove'),
            handler : this.removeBroadcast,
            scope   : this
        }];
    },
    createBroadcast: function(btn, e) {
        if (this.createBroadcastWindow) {
            this.createBroadcastWindow.destroy();
        }

        this.createBroadcastWindow = MODx.load({
            xtype       : 'digitalsignage-window-broadcast-create',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });
        
        this.createBroadcastWindow.show(e.target);
    },
    updateBroadcast: function(btn, e) {
        if (this.updateBroadcastWindow) {
            this.updateBroadcastWindow.destroy();
        }

        this.updateBroadcastWindow = MODx.load({
            xtype       : 'digitalsignage-window-broadcast-update',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.updateBroadcastWindow.setValues(this.menu.record);
        this.updateBroadcastWindow.show(e.target);
    },
    duplicateBroadcast: function(btn, e) {
        if (this.duplicateBroadcastWindow) {
            this.duplicateBroadcastWindow.destroy();
        }

        var record = Ext.applyIf({
            name    : _('digitalsignage.broadcast_name_duplicate', {
                name    : this.menu.record.name
            })
        }, this.menu.record);

        this.duplicateBroadcastWindow = MODx.load({
            xtype       : 'digitalsignage-window-broadcast-duplicate',
            record      : record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.duplicateBroadcastWindow.setValues(record);
        this.duplicateBroadcastWindow.show(e.target);
    },
    removeBroadcast: function() {
        MODx.msg.confirm({
            title       : _('digitalsignage.broadcast_remove'),
            text        : _('digitalsignage.broadcast_remove_confirm'),
            url         : DigitalSignage.config.connector_url,
                params      : {
                action      : 'mgr/broadcasts/remove',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });
    },
    previewBroadcast: function(btn, e) {
        if (this.previewBroadcastWindow) {
            this.previewBroadcastWindow.destroy();
        }

        this.previewBroadcastWindow = MODx.load({
            xtype       : 'digitalsignage-window-broadcast-preview',
            record      : this.menu.record,
            closeAction : 'close',
            saveBtnText : _('digitalsignage.show_broadcast_preview'),
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                    this.getSelectionModel().clearSelections(true);

                    this.showPreviewBroadcast(data.a.result.object);
                    },
                    scope       : this
                }
            }
        });

        this.previewBroadcastWindow.setValues(this.menu.record);
        this.previewBroadcastWindow.show(e.target);
    },
    showPreviewBroadcast: function(record) {
        if (this.showPreviewBroadcastWindow) {
            this.showPreviewBroadcastWindow.destroy();
        }

        this.showPreviewBroadcastWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-broadcast-preview-show',
            record      : record,
            closeAction : 'close'
        });

        this.showPreviewBroadcastWindow.show();
    },
    syncBroadcast: function(btn, e) {
    	MODx.msg.confirm({
            title       : _('digitalsignage.broadcast_sync'),
            text        : _('digitalsignage.broadcast_sync_confirm'),
            url         : DigitalSignage.config.connector_url,
            params      : {
                action      : 'mgr/broadcasts/sync',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refresh();
                    },
                    scope      : this
                }
            }
        });
    },
    syncSelectedBroadcasts: function(btn, e) {
        var cs = this.getSelectedAsList();

        if (cs === false) {
            return false;
        }

        MODx.msg.confirm({
            title       : _('digitalsignage.broadcasts_sync_selected'),
            text        : _('digitalsignage.broadcasts_sync_selected_confirm'),
            url         : DigitalSignage.config.connector_url,
            params      : {
                action      : 'mgr/broadcasts/syncselected',
                ids          : cs
            },
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refresh();
                    },
                    scope      : this
                }
            }
        });
    },
    viewSlides: function(btn, e) {
        if (this.viewSlidesWindow) {
            this.viewSlidesWindow.destroy();
        }

        this.viewSlidesWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-broadcast-slides',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'close'     : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.viewSlidesWindow.setValues(this.menu.record);
        this.viewSlidesWindow.show(e.target);
    },
    viewFeeds: function(btn, e) {
        if (this.viewFeedsWindow) {
            this.viewFeedsWindow.destroy();
        }

        this.viewFeedsWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-broadcast-feeds',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'close'     : {
                    fn          : function() {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.viewFeedsWindow.setValues(this.menu.record);
        this.viewFeedsWindow.show(e.target);
    },
    renderPlayers: function(d, c, e) {
        var players = new Array();

        for (var i = 0; i < e.data.players.length; i++) {
            var player = e.data.players[i];

            players.push(String.format('<i class="icon icon-circle icon-broadcast-state {0}"></i>{1}', 1 == parseInt(player.online) || player.online ? 'green' : 'red', player.name));
        }

        return players.join(', ');
    },
    renderSync: function(a) {
        if (!a.valid) {
            if (Ext.isEmpty(a.timestamp)) {
                return _('digitalsignage.sync_never');
            }

            return String.format('<i class="icon icon-exclamation-triangle icon-broadcast-sync red"></i>{0}', a.timestamp);
        }

        return a.timestamp;
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('digitalsignage-grid-broadcasts', DigitalSignage.grid.Broadcasts);

DigitalSignage.window.CreateBroadcast = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight  : true,
        title       : _('digitalsignage.broadcast_create'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/create'
        },
        fields      : [{
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_broadcast_name'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_name_desc'),
            name        : 'name',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_broadcast_name_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_broadcast_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_broadcast_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 1 === parseInt(DigitalSignage.config.templates.length) ? 'hidden' : 'digitalsignage-combo-templates',
            fieldLabel  : _('digitalsignage.label_broadcast_template'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_template_desc'),
            name        : 'template',
            anchor      : '100%',
            allowBlank  : false,
            value       : DigitalSignage.config.templates[0]
        }, {
            xtype       : 1 === parseInt(DigitalSignage.config.templates.length) ? 'hidden' : (MODx.expandHelp ? 'label' : 'hidden'),
            html        : _('digitalsignage.label_broadcast_template_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_broadcast_ticker_url'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_ticker_url_desc'),
            name        : 'ticker_url',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_broadcast_ticker_url_desc'),
            cls         : 'desc-under'
        }]
    });
    
    DigitalSignage.window.CreateBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.CreateBroadcast, MODx.Window);

Ext.reg('digitalsignage-window-broadcast-create', DigitalSignage.window.CreateBroadcast);

DigitalSignage.window.UpdateBroadcast = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight  : true,
        title       : _('digitalsignage.broadcast_update'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/update'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'hidden',
            name        : 'resource_id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_broadcast_name'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_name_desc'),
            name        : 'name',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_broadcast_name_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_broadcast_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_broadcast_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 1 === parseInt(DigitalSignage.config.templates.length) ? 'hidden' : 'digitalsignage-combo-templates',
            fieldLabel  : _('digitalsignage.label_broadcast_template'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_template_desc'),
            name        : 'template',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : 1 === parseInt(DigitalSignage.config.templates.length) ? 'hidden' : (MODx.expandHelp ? 'label' : 'hidden'),
            html        : _('digitalsignage.label_broadcast_template_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_broadcast_ticker_url'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_ticker_url_desc'),
            name        : 'ticker_url',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_broadcast_ticker_url_desc'),
            cls         : 'desc-under'
        }]
    });
    
    DigitalSignage.window.UpdateBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.UpdateBroadcast, MODx.Window);

Ext.reg('digitalsignage-window-broadcast-update', DigitalSignage.window.UpdateBroadcast);

DigitalSignage.window.DuplicateBroadcast = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.broadcast_duplicate'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/duplicate'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        },{
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_broadcast_name'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_name_desc'),
            name        : 'name',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_broadcast_name_desc'),
            cls         : 'desc-under'
        }]
    });

    DigitalSignage.window.DuplicateBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.DuplicateBroadcast, MODx.Window);

Ext.reg('digitalsignage-window-broadcast-duplicate', DigitalSignage.window.DuplicateBroadcast);

DigitalSignage.window.PreviewBroadcast = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.broadcast_preview'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/preview'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'digitalsignage-combo-players',
            fieldLabel  : _('digitalsignage.label_broadcast_preview_player'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_broadcast_preview_player_desc'),
            name        : 'player',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_broadcast_preview_player_desc'),
            cls         : 'desc-under'
        }]
    });
    
    DigitalSignage.window.PreviewBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.PreviewBroadcast, MODx.Window);

Ext.reg('digitalsignage-window-broadcast-preview', DigitalSignage.window.PreviewBroadcast);

DigitalSignage.window.ShowPreviewBroadcast = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        maximized   : true,
        title       : _('digitalsignage.broadcast_preview') + _('digitalsignage.preview_resolution', {
            resolution  : config.record.resolution
        }),
        cls         : 'digitalsignage-window-preview',
        items       : [{
            xtype       : 'container',
            layout      : {
                type        : 'vbox',
                align       : 'stretch'
            },
            width       : '100%',
            height      : '100%',
            items       : [{
                autoEl      : {
                    tag         : 'iframe',
                    src         : config.record.url,
                    width       : '100%',
                    height      : '100%',
                    frameBorder : 0,
                }
            }]
        }],
        buttons     : [{
            text        : _('ok'),
            cls         : 'primary-button',
            handler     : function() {
                if ('close' !== config.closeAction) {
                    this.hide();
                } else {
                    this.close();
                }
            },
            scope       : this
        }],
        listeners   : {
            bodyresize  : {
                fn          : this.onResolutionSize,
                scope       : this
            }
        }
    });
    
    DigitalSignage.window.ShowPreviewBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.ShowPreviewBroadcast, MODx.Window, {
    onResolutionSize: function() {
        var maxWidth    = parseInt(this.getEl().select('.x-window-body').first().getWidth()),
            maxHeight   = parseInt(this.getEl().select('.x-window-body').first().getHeight());

        this.getEl().select('iframe').setWidth((parseInt(this.config.record.width) / 100) * (maxHeight / (parseInt(this.config.record.height) / 100)));
        this.getEl().select('iframe').setHeight(maxHeight);
    }
});

Ext.reg('digitalsignage-window-broadcast-preview-show', DigitalSignage.window.ShowPreviewBroadcast);

DigitalSignage.window.Slides = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        width       : 600,
        height      : 500,
        title       : _('digitalsignage.broadcast_slides'),
        cls         : 'digitalsignage-window-padding',
        items       : [{
            html        : '<p>' + _('digitalsignage.broadcast_slides_desc') + '</p>',
            cls         : 'panel-desc'
        }, {
            layout      : 'column',
            border      : false,
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .5,
                items       : [{
                    xtype       : 'digitalsignage-tree-broadcast-slides',
                    broadcast   : config.record.id,
                    enableDD    : true
                }]
            }, {
                columnWidth : .5,
                items       : [{
                    xtype       : 'digitalsignage-tree-available-slides',
                    broadcast   : config.record.id,
                    enableDD    : true
                }]
            }]
        }],
        buttons     : [{
            text        : _('ok'),
            cls         : 'primary-button',
            handler     : function() {
                this.fireEvent('sucesss');

                if ('close' !== config.closeAction) {
                    this.hide();
                } else {
                    this.close();
                }
            },
            scope       : this
        }]
    });
    
    DigitalSignage.window.Slides.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.Slides, MODx.Window);

Ext.reg('digitalsignage-window-broadcast-slides', DigitalSignage.window.Slides);

DigitalSignage.window.Feeds = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        width       : 600,
        autoHeight  : true,
        title       : _('digitalsignage.broadcast_feeds'),
        cls         : 'digitalsignage-window-padding',
        items       : [{
            html        : '<p>' + _('digitalsignage.broadcast_feeds_desc') + '</p>',
            cls         : 'panel-desc'
        }, {
            xtype       : 'digitalsignage-grid-broadcast-feeds',
            record      : config.record,
            preventRender : true
        }],
        buttons     : [{
            text        : _('ok'),
            cls         : 'primary-button',
            handler     : function() {
                this.fireEvent('sucesss');

                if ('close' !== config.closeAction) {
                    this.hide();
                } else {
                    this.close();
                }
            },
            scope       : this
        }]
    });
    
    DigitalSignage.window.Slides.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.Feeds, MODx.Window);

Ext.reg('digitalsignage-window-broadcast-feeds', DigitalSignage.window.Feeds);

DigitalSignage.combo.Broadcasts = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/getnodes'
        },
        fields      : ['id', 'name', 'calendar'],
        hiddenName  : 'broadcast_id',
        valueField  : 'id',
        displayField: 'name',
        tpl         : new Ext.XTemplate('<tpl for=".">' +
            '<div class="x-combo-list-item">' +
                '<span class="x-cal-combo x-cal-{calendar}">' +
                    '<span class="ext-cal-picker-icon"></span>' +
                '</span> {name}' +
            '</div>' +
        '</tpl>')
    });

    DigitalSignage.combo.Broadcasts.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.combo.Broadcasts, MODx.combo.ComboBox);

Ext.reg('digitalsignage-combo-broadcasts', DigitalSignage.combo.Broadcasts);

DigitalSignage.combo.Templates = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/templates/getnodes'
        },
        fields      : ['id', 'templatename', 'description', 'category_name'],
        hiddenName  : 'template',
        valueField  : 'id',
        displayField: 'templatename',
        tpl         : new Ext.XTemplate('<tpl for=".">' +
            '<div class="x-combo-list-item">' +
                '<span style="font-weight: bold">{templatename}</span><br />{description}' +
            '</div>' +
        '</tpl>')
    });

    DigitalSignage.combo.Templates.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.combo.Templates, MODx.combo.ComboBox);

Ext.reg('digitalsignage-combo-templates', DigitalSignage.combo.Templates);

DigitalSignage.combo.DigitalSignageBroadcastsCheckbox = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        value       : '',
        columns     : 1,
        id          : 'digitalsignage-checkboxgroup-fixed',
        cls         : 'digitalsignage-checkboxgroup-fixed x-form-item',
        store       : new Ext.data.JsonStore({
            url         : DigitalSignage.config.connector_url,
            baseParams  : {
                action      : 'mgr/broadcasts/getlist',
                start       : 0,
                limit       : 0
            },
            root        : 'results',
            totalProperty : 'total',
            fields      : ['id', 'name', 'name_formatted'],
            errorReader : MODx.util.JSONReader,
            remoteSort  : false,
            autoDestroy : true,
            autoLoad    : true,
            listeners   : {
                load        : {
                    fn          : this.setData,
                    scope       : this
                },
                loadexception : {
                    fn          : function(o, trans, resp) {
                        var status = _('code') + ': ' + resp.status + ' ' + resp.statusText + '<br/>';

                        MODx.msg.alert(_('error'), status + resp.responseText);
                    }
                }
            }
        })
    });

    DigitalSignage.combo.DigitalSignageBroadcastsCheckbox.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.combo.DigitalSignageBroadcastsCheckbox, Ext.Panel, {
    setData: function(store, data) {
        var items = [];

        Ext.each(data, function(record) {
            items.push({
                xtype      : 'checkbox',
                boxLabel   : record.data.name_formatted,
                name       : 'broadcasts[]',
                inputValue : record.data.id,
                checked    : -1 !== this.value.indexOf(record.data.id) ? true : false
            });
        }, this);

        if (items.length > 0) {
            this.add({
                xtype       : 'checkboxgroup',
                hideLabel   : true,
                columns     : this.columns,
                items       : items
            });
        }

        this.doLayout();
    }
});

Ext.reg('digitalsignage-checkbox-broadcasts', DigitalSignage.combo.DigitalSignageBroadcastsCheckbox);