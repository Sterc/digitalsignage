DigitalSignage.grid.PlayerSchedules = function(config) {
	config = config || {};

    config.tbar = [{
        text        : _('digitalsignage.player_schedule_create'),
        cls         : 'primary-button',
        handler     : this.createSchedule,
        scope       : this
    }, '->', {
        xtype       : 'digitalsignage-combo-broadcasts',
        name        : 'digitalsignage-filter-search-players-broadcast',
        id          : 'digitalsignage-filter-search-players-broadcast',
        emptyText   : _('digitalsignage.filter_broadcast'),
        listeners   : {
            'change'    : {
                fn          : this.filterBroadcast,
                scope       : this
            }
        },
        width       : 250
    }, '-', {
        xtype       : 'button',
        cls         : 'x-form-filter-clear',
        id          : 'digitalsignage-filter-clear-players-schedules',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];
    
    var expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{description}</p>'
        )
    });

    var columns = new Ext.grid.ColumnModel({
        columns     : [expander, {
            header      : _('digitalsignage.label_schedule_date'),
            dataIndex   : 'date_formatted',
            sortable    : true,
            editable    : false,
            width       : 250,
            renderer    : this.renderDate
        }, {
            header      : _('digitalsignage.label_schedule_broadcast'),
            dataIndex   : 'broadcast',
            sortable    : true,
            editable    : false,
            width       : 250,
            fixed       : true,
            renderer    : this.renderBroadcast
        }]
    });
    
    Ext.applyIf(config, {
        cm          : columns,
        id          : 'digitalsignage-grid-player-schedules',
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/players/schedules/getlist',
            player_id   : config.record.id
        },
        fields      : ['id', 'player_id', 'broadcast_id', 'description', 'type', 'type_formatted', 'start_time', 'start_date', 'end_time', 'end_date', 'day', 'date_formatted', 'entire_day', 'broadcast'],
        paging      : true,
        pageSize    : 7,
        showPerPage : false,
        sortBy      : 'id',
        plugins     : expander
    });
    
    DigitalSignage.grid.PlayerSchedules.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.grid.PlayerSchedules, MODx.grid.Grid, {
    filterBroadcast: function(tf, nv, ov) {
        this.getStore().baseParams.broadcast_id = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.broadcast_id = '';

        Ext.getCmp('digitalsignage-filter-search-players-broadcast').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
            text    : _('digitalsignage.player_schedule_update'),
            handler : this.updateSchedule,
            scope   : this
        }, '-', {
            text    : _('digitalsignage.player_schedule_remove'),
            handler : this.removeSchedule,
            scope   : this
        }];
    },
    createSchedule: function(btn, e) {
        if (this.createScheduleWindow) {
            this.createScheduleWindow.destroy();
        }

        this.createScheduleWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-player-schedule-create',
            record      : {
                player_id   : this.config.record.id
            },
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });

        this.createScheduleWindow.setValues({
            player_id   : this.config.record.id
        });
        this.createScheduleWindow.show(e.target);
    },
    updateSchedule: function(btn, e) {
        if (this.updateScheduleWindow) {
            this.updateScheduleWindow.destroy();
        }

        this.updateScheduleWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-player-schedule-update',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });

        this.updateScheduleWindow.setValues(this.menu.record);
        this.updateScheduleWindow.show(e.target);
    },
    removeSchedule: function() {
        MODx.msg.confirm({
            title       : _('digitalsignage.player_schedule_remove'),
            text        : _('digitalsignage.player_schedule_remove_confirm'),
            url         : DigitalSignage.config.connector_url,
            params      : {
                action      : 'mgr/players/schedules/remove',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
    },
    renderDate: function(d, c, e) {
        return String.format('<span class="icon icon-{0}"></span> {1}', 1 == parseInt(e.data.entire_day) || e.data.entire_day ? 'thumb-tack' : 'clock-o', d);
    },
    renderBroadcast: function(d, c, e) {
        return String.format('<span class="x-cal-combo x-cal-{0}"><span class="ext-cal-picker-icon"></span></span> {1}', d.color, d.name);
    }
});

Ext.reg('digitalsignage-grid-player-schedules', DigitalSignage.grid.PlayerSchedules);

DigitalSignage.window.CreatePlayerSchedule = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.player_schedule_create'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/players/schedules/create'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'player_id'
        }, {
            xtype       : 'digitalsignage-combo-broadcasts',
            fieldLabel  : _('digitalsignage.label_schedule_broadcast'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_broadcast_desc'),
            name        : 'broadcast_id',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_schedule_broadcast_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_schedule_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_schedule_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'digitalsignage-combo-schedules-type',
            fieldLabel  : _('digitalsignage.label_schedule_type'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_type_desc'),
            name        : 'type',
            anchor      : '100%',
            allowBlank  : false,
            forId       : {
                day         : 'digitalsignage-schedule-create-day',
                date        : 'digitalsignage-schedule-create-date'
            },
            listeners   : {
                'select'    : {
                    fn          : this.setType,
                    scope       : this
                },
                'afterrender' : {
                    fn          : this.setType,
                    scope       : this
                }
            }
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_schedule_type_desc'),
            cls         : 'desc-under'
        }, {
            id          : 'digitalsignage-schedule-create-day',
            layout      : 'form',
            labelSeparator : '',
            items       : [{
                xtype       : 'digitalsignage-combo-schedules-day',
                fieldLabel  : _('digitalsignage.label_schedule_day'),
                description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_day_desc'),
                name        : 'day',
                anchor      : '100%'
            }, {
                xtype       : MODx.expandHelp ? 'label' : 'hidden',
                html        : _('digitalsignage.label_schedule_day_desc'),
                cls         : 'desc-under'
            }]
        }, {
            id          : 'digitalsignage-schedule-create-date',
            layout      : 'form',
            labelSeparator : '',
            items       : [{
                layout      : 'column',
                defaults    : {
                    layout      : 'form',
                    labelSeparator : ''
                },
                items       : [{
                    columnWidth : .5,
                    items       : [{
                        xtype       : 'datefield',
                        fieldLabel  : _('digitalsignage.label_schedule_start_date'),
                        description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_start_date_desc'),
                        name        : 'start_date',
                        anchor      : '100%',
                        format      : MODx.config.manager_date_format
                    }, {
                        xtype       : MODx.expandHelp ? 'label' : 'hidden',
                        html        : _('digitalsignage.label_schedule_start_date_desc'),
                        cls         : 'desc-under'
                    }]
                }, {
                    columnWidth : .5,
                    items       : [{
                        xtype       : 'datefield',
                        fieldLabel  : _('digitalsignage.label_schedule_end_date'),
                        description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_end_date_desc'),
                        name        : 'end_date',
                        anchor      : '100%',
                        format      : MODx.config.manager_date_format
                    }, {
                        xtype       : MODx.expandHelp ? 'label' : 'hidden',
                        html        : _('digitalsignage.label_schedule_end_date_desc'),
                        cls         : 'desc-under'
                    }]
                }]
            }]
        }, {
            xtype       : 'checkbox',
            boxLabel    : _('digitalsignage.label_schedule_entire_day'),
            name        : 'entire_day',
            anchor      : '100%',
            inputValue  : 1,
            checked     : true,
            forId       : 'digitalsignage-schedule-create-time',
            listeners   : {
                'check'     : {
                    fn          : this.setEntireDay,
                    scope       : this
                },
                'afterrender' : {
                    fn          : this.setEntireDay,
                    scope       : this
                }
            }
        }, {
            id          : 'digitalsignage-schedule-create-time',
            layout      : 'form',
            items       : [{
                layout      : 'column',
                defaults    : {
                    layout      : 'form',
                    labelSeparator : ''
                },
                items       : [{
                    columnWidth : .5,
                    items       : [{
                        xtype       : 'timefield',
                        fieldLabel  : _('digitalsignage.label_schedule_start_time'),
                        description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_start_time_desc'),
                        name        : 'start_time',
                        anchor      : '100%',
                        format      : MODx.config.manager_time_format
                    }, {
                        xtype       : MODx.expandHelp ? 'label' : 'hidden',
                        html        : _('digitalsignage.label_schedule_start_time_desc'),
                        cls         : 'desc-under'
                    }]
                }, {
                    columnWidth : .5,
                    items       : [{
                        xtype       : 'timefield',
                        fieldLabel  : _('digitalsignage.label_schedule_end_time'),
                        description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_end_time_desc'),
                        name        : 'end_time',
                        anchor      : '100%',
                        format      : MODx.config.manager_time_format
                    }, {
                        xtype       : MODx.expandHelp ? 'label' : 'hidden',
                        html        : _('digitalsignage.label_schedule_end_time_desc'),
                        cls         : 'desc-under'
                    }]
                }]
            }]
        }]
    });
    
    DigitalSignage.window.CreatePlayerSchedule.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.CreatePlayerSchedule, MODx.Window, {
    setType: function(tf, nv) {
        Ext.iterate(tf.forId, function(key, value) {
            if (undefined !== (element = Ext.getCmp(value))) {
                if (key == tf.getValue()) {
                    element.show();
                } else {
                    element.hide();
                }
            }
        });
    },
    setEntireDay: function(tf) {
        if (undefined !== (element = Ext.getCmp(tf.forId))) {
            if (tf.getValue()) {
                element.hide();
            } else {
                element.show();
            }
        }
    }
});

Ext.reg('digitalsignage-window-player-schedule-create', DigitalSignage.window.CreatePlayerSchedule);

DigitalSignage.window.UpdatePlayerSchedule = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.player_schedule_update'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/players/schedules/update'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'hidden',
            name        : 'player_id'
        }, {
            xtype       : 'digitalsignage-combo-broadcasts',
            fieldLabel  : _('digitalsignage.label_schedule_broadcast'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_broadcast_desc'),
            name        : 'broadcast_id',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_schedule_broadcast_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_schedule_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_schedule_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'digitalsignage-combo-schedules-type',
            fieldLabel  : _('digitalsignage.label_schedule_type'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_type_desc'),
            name        : 'type',
            anchor      : '100%',
            allowBlank  : false,
            forId       : {
                day         : 'digitalsignage-schedule-update-day',
                date        : 'digitalsignage-schedule-update-date'
            },
            listeners   : {
                'change'    : {
                    fn          : this.setType,
                    scope       : this
                },
                'afterrender' : {
                    fn          : this.setType,
                    scope       : this
                }
            }
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_schedule_type_desc'),
            cls         : 'desc-under'
        }, {
            id          : 'digitalsignage-schedule-update-day',
            layout      : 'form',
            labelSeparator : '',
            items       : [{
                xtype       : 'digitalsignage-combo-schedules-day',
                fieldLabel  : _('digitalsignage.label_schedule_day'),
                description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_day_desc'),
                name        : 'day',
                anchor      : '100%'
            }, {
                xtype       : MODx.expandHelp ? 'label' : 'hidden',
                html        : _('digitalsignage.label_schedule_day_desc'),
                cls         : 'desc-under'
            }]
        }, {
            id          : 'digitalsignage-schedule-update-date',
            layout      : 'form',
            labelSeparator : '',
            items       : [{
                layout      : 'column',
                defaults    : {
                    layout      : 'form',
                    labelSeparator : ''
                },
                items       : [{
                    columnWidth : .5,
                    items       : [{
                        xtype       : 'datefield',
                        fieldLabel  : _('digitalsignage.label_schedule_start_date'),
                        description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_start_date_desc'),
                        name        : 'start_date',
                        anchor      : '100%',
                        format      : MODx.config.manager_date_format
                    }, {
                        xtype       : MODx.expandHelp ? 'label' : 'hidden',
                        html        : _('digitalsignage.label_schedule_start_date_desc'),
                        cls         : 'desc-under'
                    }]
                }, {
                    columnWidth : .5,
                    items       : [{
                        xtype       : 'datefield',
                        fieldLabel  : _('digitalsignage.label_schedule_end_date'),
                        description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_end_date_desc'),
                        name        : 'end_date',
                        anchor      : '100%',
                        format      : MODx.config.manager_date_format
                    }, {
                        xtype       : MODx.expandHelp ? 'label' : 'hidden',
                        html        : _('digitalsignage.label_schedule_end_date_desc'),
                        cls         : 'desc-under'
                    }]
                }]
            }]
        }, {
            xtype       : 'checkbox',
            boxLabel    : _('digitalsignage.label_schedule_entire_day'),
            name        : 'entire_day',
            anchor      : '100%',
            inputValue  : 1,
            checked     : true,
            forId       : 'digitalsignage-schedule-update-time',
            listeners   : {
                'select'    : {
                    fn          : this.setEntireDay,
                    scope       : this
                },
                'afterrender' : {
                    fn          : this.setEntireDay,
                    scope       : this
                }
            }
        }, {
            id          : 'digitalsignage-schedule-update-time',
            layout      : 'form',
            items       : [{
                layout      : 'column',
                defaults    : {
                    layout      : 'form',
                    labelSeparator : ''
                },
                items       : [{
                    columnWidth : .5,
                    items       : [{
                        xtype       : 'timefield',
                        fieldLabel  : _('digitalsignage.label_schedule_start_time'),
                        description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_start_time_desc'),
                        name        : 'start_time',
                        anchor      : '100%',
                        format      : MODx.config.manager_time_format,
                        value       : '00:00'
                    }, {
                        xtype       : MODx.expandHelp ? 'label' : 'hidden',
                        html        : _('digitalsignage.label_schedule_start_time_desc'),
                        cls         : 'desc-under'
                    }]
                }, {
                    columnWidth : .5,
                    items       : [{
                        xtype       : 'timefield',
                        fieldLabel  : _('digitalsignage.label_schedule_end_time'),
                        description : MODx.expandHelp ? '' : _('digitalsignage.label_schedule_end_time_desc'),
                        name        : 'end_time',
                        anchor      : '100%',
                        format      : MODx.config.manager_time_format,
                        value       : '00:00'
                    }, {
                        xtype       : MODx.expandHelp ? 'label' : 'hidden',
                        html        : _('digitalsignage.label_schedule_end_time_desc'),
                        cls         : 'desc-under'
                    }]
                }]
            }]
        }]
    });
    
    DigitalSignage.window.UpdatePlayerSchedule.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.UpdatePlayerSchedule, MODx.Window, {
    setType: function(tf, nv) {
        Ext.iterate(tf.forId, function(key, value) {
            if (undefined !== (element = Ext.getCmp(value))) {
                if (key == tf.getValue()) {
                    element.show();
                } else {
                    element.hide();
                }
            }
        });
    },
    setEntireDay: function(tf) {
        if (undefined !== (element = Ext.getCmp(tf.forId))) {
            if (tf.getValue()) {
                element.hide();
            } else {
                element.show();
            }
        }
    }
});

Ext.reg('digitalsignage-window-player-schedule-update', DigitalSignage.window.UpdatePlayerSchedule);

DigitalSignage.combo.SchedulesType = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store       : new Ext.data.ArrayStore({
            mode        : 'local',
            fields      : ['type', 'label'],
            data        : [
                ['day', _('digitalsignage.schedule_day')],
                ['date', _('digitalsignage.schedule_date')]
            ]
        }),
        remoteSort  : ['label', 'asc'],
        hiddenName  : 'type',
        valueField  : 'type',
        displayField : 'label',
        mode        : 'local'
    });

    DigitalSignage.combo.SchedulesType.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.combo.SchedulesType, MODx.combo.ComboBox);

Ext.reg('digitalsignage-combo-schedules-type', DigitalSignage.combo.SchedulesType);

DigitalSignage.combo.SchedulesDay = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        store       : new Ext.data.ArrayStore({
            mode        : 'local',
            fields      : ['type', 'label'],
            data        : [
                ['0', _('sunday')],
                ['1', _('monday')],
                ['2', _('tuesday')],
                ['3', _('wednesday')],
                ['4', _('thursday')],
                ['5', _('friday')],
                ['6', _('saturday')]
            ]
        }),
        remoteSort  : ['label', 'asc'],
        hiddenName  : 'day',
        valueField  : 'type',
        displayField : 'label',
        mode        : 'local'
    });

    DigitalSignage.combo.SchedulesDay.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.combo.SchedulesDay, MODx.combo.ComboBox);

Ext.reg('digitalsignage-combo-schedules-day', DigitalSignage.combo.SchedulesDay);