DigitalSignage.grid.Players = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('digitalsignage.player_create'),
        cls         : 'primary-button',
        handler     : this.createPlayer,
        scope       : this
    }, {
        xtype       : 'checkbox',
        name        : 'digitalsignage-filter-players-refresh',
        id          : 'digitalsignage-filter-players-refresh',
        boxLabel    : _('digitalsignage.auto_refresh_grid'),
        checked     : true,
        listeners   : {
            'check'     : {
                fn          : this.autoRefresh,
                scope       : this
            },
            'afterrender' : {
                fn          : this.autoRefresh,
                scope       : this
            }
        }
    }, '->', {
        xtype       : 'textfield',
        name        : 'digitalsignage-filter-players-search',
        id          : 'digitalsignage-filter-players-search',
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
        id          : 'digitalsignage-filter-players-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];
    
    var expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template('<p class="desc">{description}</p>')
    });

    var columns = new Ext.grid.ColumnModel({
        columns     : [expander, {
            header      : _('digitalsignage.label_player_key'),
            dataIndex   : 'key',
            sortable    : true,
            editable    : false,
            width       : 175,
            fixed       : true,
            renderer    : this.renderKey
        }, {
            header      : _('digitalsignage.label_player_name'),
            dataIndex   : 'name',
            sortable    : true,
            editable    : false,
            width       : 250
        }, {
            header      : _('digitalsignage.label_player_current_broadcast'),
            dataIndex   : 'current_broadcast',
            sortable    : true,
            editable    : false,
            width       : 150,
            fixed       : true
        }, {
            header      : _('digitalsignage.label_player_next_sync'),
            dataIndex   : 'next_sync',
            sortable    : true,
            editable    : false,
            width       : 150,
            fixed       : true,
            renderer    : this.renderNextSync
        }, {
            header      : _('last_modified'),
            dataIndex   : 'editedon',
            sortable    : true,
            editable    : false,
            fixed       : true,
            width       : 200,
            renderer    : this.renderDate
        }]
    });
    
    Ext.applyIf(config, {
        cm          : columns,
        id          : 'digitalsignage-grid-players',
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/players/getlist'
        },
        fields      : ['id', 'key', 'name', 'description', 'type', 'resolution', 'restart', 'last_online', 'last_broadcast_id', 'editedon', 'mode', 'mode_formatted', 'online', 'current_broadcast', 'next_sync', 'url'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy      : 'id',
        plugins     : expander,
        refresher   : {
            timer       : null,
            duration    : 30,
            count       : 0
        },
        refreshGrid : []
    });
    
    DigitalSignage.grid.Players.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.grid.Players, MODx.grid.Grid, {
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
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.query = '';

        Ext.getCmp('digitalsignage-filter-players-search').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
            text    : '<i class="x-menu-item-icon icon icon-link"></i>' + _('digitalsignage.player_view'),
            handler : this.viewPlayer,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-power-off"></i> ' + (0 == parseInt(this.menu.record.restart) ? _('digitalsignage.player_restart') : _('digitalsignage.player_restart_cancel')),
            handler : this.restartPlayer,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-edit"></i>' + _('digitalsignage.player_update'),
            handler : this.updatePlayer,
            scope   : this
        }, {
            text    : '<i class="x-menu-item-icon icon icon-calendar"></i>' + _('digitalsignage.player_schedule'),
            handler : this.schedulePlayer,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('digitalsignage.player_remove'),
            handler : this.removePlayer,
            scope   : this
        }];
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
    createPlayer: function(btn, e) {
        if (this.createPlayerWindow) {
            this.createPlayerWindow.destroy();
        }

        this.createPlayerWindow = MODx.load({
            xtype       : 'digitalsignage-window-player-create',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn      : function(data) {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.createPlayerWindow.show(e.target);
    },
    updatePlayer: function(btn, e) {
        if (this.updatePlayerWindow) {
            this.updatePlayerWindow.destroy();
        }

        this.updatePlayerWindow = MODx.load({
            xtype       : 'digitalsignage-window-player-update',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.updatePlayerWindow.setValues(this.menu.record);
        this.updatePlayerWindow.show(e.target);
    },
    restartPlayer: function() {
        MODx.msg.confirm({
            title       : 0 == parseInt(this.menu.record.restart) ? _('digitalsignage.player_restart') : _('digitalsignage.player_restart_cancel'),
            text        : 0 == parseInt(this.menu.record.restart) ? _('digitalsignage.player_restart_confirm') : _('digitalsignage.player_restart_cancel_confirm'),
            url         : DigitalSignage.config.connector_url,
            params      : {
                action      : 'mgr/players/restart',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });
    },
    removePlayer: function() {
        MODx.msg.confirm({
            title       : _('digitalsignage.player_remove'),
            text        : _('digitalsignage.player_remove_confirm'),
            url         : DigitalSignage.config.connector_url,
            params      : {
                action      : 'mgr/players/remove',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });
    },
    viewPlayer: function(btn, e) {
        if (this.viewPlayerWindow) {
            this.viewPlayerWindow.destroy();
        }

        this.viewPlayerWindow = MODx.load({
            xtype       : 'digitalsignage-window-player-view',
            record      : this.menu.record,
            closeAction : 'close'
        });

        this.viewPlayerWindow.setValues(this.menu.record);
        this.viewPlayerWindow.show(e.target);
    },
    schedulePlayer: function(btn, e) {
        if (this.schedulePlayerWindow) {
            this.schedulePlayerWindow.destroy();
        }

        this.schedulePlayerWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-player-schedule',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.schedulePlayerWindow.setValues(this.menu.record);
        this.schedulePlayerWindow.show(e.target);
    },
    renderKey: function(d, c, e) {
        return String.format('<i class="icon icon-circle icon-broadcast-state {0}"></i>{1}', 1 == parseInt(e.data.online) || e.data.online ? 'green' : 'red', d);
    },
    renderMode: function(d, c, e) {
        return String.format('<i class="icon icon-large icon-arrows-{0} icon-player-mode"></i> {1}', 'landscape' == e.data.mode ? 'h' : 'v', d);
    },
    renderOnline: function(d, c, e) {
        c.css = 1 == parseInt(d) || d ? 'green' : 'red';

        return '<i class="icon icon-circle"></i>';
    },
    renderNextSync: function(d, c, e) {
        if (Ext.isEmpty(d)) {
            return '';
        }

        var minutes = Math.floor(d / 60),
            seconds = d - (minutes * 60);

        if (10 > minutes) {
            minutes = '0' + minutes;
        }

        if (10 > seconds) {
            seconds = '0' + seconds;
        }

        if (parseInt(e.data.restart) === 1) {
            return String.format('<span class="icon icon-power-off icon-player-restart"></span>{0}', _('digitalsignage.next_sync', {
                time : minutes + ':' + seconds
            }));
        }

        return _('digitalsignage.next_sync', {
            time : minutes + ':' + seconds
        });
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('digitalsignage-grid-players', DigitalSignage.grid.Players);

DigitalSignage.window.CreatePlayer = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.player_create'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/players/create'
        },
        fields      : [{
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_player_name'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_player_name_desc'),
            name        : 'name',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_player_name_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_player_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_player_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_player_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_player_resolution'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_player_resolution_desc'),
            name        : 'resolution',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_player_resolution_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_player_type'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_player_type_desc'),
            name        : 'type',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_player_type_desc'),
            cls         : 'desc-under'
        }]
    });
    
    DigitalSignage.window.CreatePlayer.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.CreatePlayer, MODx.Window);

Ext.reg('digitalsignage-window-player-create', DigitalSignage.window.CreatePlayer);

DigitalSignage.window.UpdatePlayer = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.player_update'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/players/update'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_player_name'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_player_name_desc'),
            name        : 'name',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_player_name_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_player_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_player_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_player_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_player_resolution'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_player_resolution_desc'),
            name        : 'resolution',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_player_resolution_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_player_type'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_player_type_desc'),
            name        : 'type',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_player_type_desc'),
            cls         : 'desc-under'
        }]
    });
    
    DigitalSignage.window.UpdatePlayer.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.UpdatePlayer, MODx.Window);

Ext.reg('digitalsignage-window-player-update', DigitalSignage.window.UpdatePlayer);

DigitalSignage.window.ViewPlayer = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.player_view'),
        cls         : 'digitalsignage-window-padding',
        fields      : [{
            html        : '<p>' + _('digitalsignage.player_view_desc') + '</p>',
            cls         : 'panel-desc'
        }, {
            xtype       : 'textfield',
            name        : 'url',
            anchor      : '100%',
            hideLabel   : true
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
        }]
    });

    DigitalSignage.window.ViewPlayer.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.ViewPlayer, MODx.Window);

Ext.reg('digitalsignage-window-player-view', DigitalSignage.window.ViewPlayer);

DigitalSignage.window.SchedulePlayer = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        width       : 600,
        autoHeight  : true,
        title       : _('digitalsignage.player_schedule'),
        cls         : 'digitalsignage-window-padding',
        items       : [{
            xtype       : 'digitalsignage-grid-player-schedules',
            record      : config.record,
            preventRender : true
        }],
        buttons     : [{
            text        :  '<i class="icon icon-calendar"></i>' + _('digitalsignage.player_calendar'),
            handler     : this.calendarPlayer,
            scope       : this
        }, {
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
        }]
    });

    DigitalSignage.window.SchedulePlayer.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.SchedulePlayer, MODx.Window, {
    calendarPlayer: function(btn, e) {
        if (this.calendarPlayerWindow) {
            this.calendarPlayerWindow.destroy();
        }

        this.calendarPlayerWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-player-calendar',
            record      : this.config.record,
            closeAction : 'close'
        });

        this.calendarPlayerWindow.setValues(this.config.record);
        this.calendarPlayerWindow.show(e.target);
    }
});

Ext.reg('digitalsignage-window-player-schedule', DigitalSignage.window.SchedulePlayer);

DigitalSignage.window.CalendarPlayer = function(config) {
    config = config || {};

    var eventStore = new Ext.ensible.cal.EventStore({
        proxy       : new Ext.data.HttpProxy({
            url         : DigitalSignage.config.connector_url,
        }),
        baseParams  : {
            action      : 'mgr/players/schedules/getcalendar',
            player_id   : config.record.id
        },
        reader      : new Ext.data.JsonReader({
            idProperty      : 'id',
            totalProperty   : 'total',
            successProperty : 'success',
            root            : 'results',
            fields          : Ext.ensible.cal.EventRecord.prototype.fields.getRange()
        }),
        autoLoad    : true
    });

    var calendarStore = new Ext.data.JsonStore({
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/getcalendars'
        },
        totalProperty : 'total',
        successProperty : 'success',
        root        : 'results',
        fields      : Ext.ensible.cal.CalendarRecord.prototype.fields.getRange()
    });

    calendarStore.load();

    Ext.applyIf(config, {
        height      : 700,
        width       : 900,
        title       : _('digitalsignage.player_calendar'),
        layout      : 'fit',
        cls         : 'digitalsignage-window-padding',
        items       : [{
            xtype       : 'extensible.calendarpanel',
            readOnly    : true,
            showDayView : false,
            showWeekView : true,
            showMultiWeekView : false,
            showMonthView : false,
            activeItem  : 0,
            eventStore  : eventStore,
            calendarStore : calendarStore
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
        }]
    });

    DigitalSignage.window.CalendarPlayer.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.CalendarPlayer, MODx.Window);

Ext.reg('digitalsignage-window-player-calendar', DigitalSignage.window.CalendarPlayer);

DigitalSignage.combo.Players = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/players/getnodes',
            broadcast   : config.broadcast || null
        },
        fields      : ['id', 'key', 'name'],
        hiddenName  : 'player',
        pageSize    : 15,
        valueField  : 'id',
        displayField: 'name'
    });

    DigitalSignage.combo.Players.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.combo.Players, MODx.combo.ComboBox);

Ext.reg('digitalsignage-combo-players', DigitalSignage.combo.Players);