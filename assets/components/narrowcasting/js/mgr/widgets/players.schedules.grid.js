Narrowcasting.grid.PlayerSchedules = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('narrowcasting.player_schedule_create'),
        cls			:'primary-button',
        handler		: this.createSchedule,
        scope		: this
    }, '->', {
    	xtype		: 'narrowcasting-combo-broadcasts',
        name 		: 'narrowcasting-filter-search-players-broadcast',
        id			: 'narrowcasting-filter-search-players-broadcast',
        emptyText	: _('narrowcasting.filter_broadcast'),
        listeners	: {
	        'change'	: {
	        	fn			: this.filterBroadcast,
	        	scope		: this
	        }
	    },
	    width		: 250
    }, '-', {
    	xtype		: 'button',
    	cls			: 'x-form-filter-clear',
    	id			: 'narrowcasting-filter-clear-players-schedules',
    	text		: _('filter_clear'),
    	listeners	: {
        	'click'		: {
        		fn			: this.clearFilter,
        		scope		: this
        	}
        }
    }];
    
    expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{description}</p>'
        )
    });

    columns = new Ext.grid.ColumnModel({
        columns: [expander, {
            header		: _('narrowcasting.label_schedule_date'),
            dataIndex	: 'date_formatted',
            sortable	: true,
            editable	: false,
            width		: 250,
            renderer	: this.renderDate
        }, {
            header		: _('narrowcasting.label_schedule_broadcast'),
            dataIndex	: 'broadcast',
            sortable	: true,
            editable	: false,
            width		: 250,
            fixed 		: true,
            renderer	: this.renderBroadcast
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'narrowcasting-grid-player-schedules',
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
        	action		: 'mgr/players/schedules/getlist',
        	player_id 	: config.record.id
        },
        fields		: ['id', 'player_id', 'broadcast_id', 'description', 'type', 'type_formatted', 'start_time', 'start_date', 'end_time', 'end_date', 'day', 'date_formatted', 'entire_day', 'broadcast'],
        paging		: true,
        pageSize	: 7,
        showPerPage	: false,
        sortBy		: 'id',
        plugins		: expander
    });
    
    Narrowcasting.grid.PlayerSchedules.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.grid.PlayerSchedules, MODx.grid.Grid, {
	filterBroadcast: function(tf, nv, ov) {
        this.getStore().baseParams.broadcast_id = tf.getValue();
        
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.broadcast_id = '';
	    
	    Ext.getCmp('narrowcasting-filter-search-players-broadcast').reset();
	    
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
		    text	: _('narrowcasting.player_schedule_update'),
		    handler	: this.updateSchedule,
		    scope	: this
		}, '-', {
		    text	: _('narrowcasting.player_schedule_remove'),
		    handler	: this.removeSchedule,
		    scope	: this
		}];
    },
    createSchedule: function(btn, e) {
        if (this.createScheduleWindow) {
	        this.createScheduleWindow.destroy();
        }
        
        this.createScheduleWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'narrowcasting-window-player-schedule-create',
	        record		: {
		        player_id	: this.config.record.id
		    },
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	        }
        });

		this.createScheduleWindow.setValues({
	        player_id	: this.config.record.id
	    });
        this.createScheduleWindow.show(e.target);
    },
    updateSchedule: function(btn, e) {
        if (this.updateScheduleWindow) {
	        this.updateScheduleWindow.destroy();
        }
        
        this.updateScheduleWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'narrowcasting-window-player-schedule-update',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	        }
        });
        
        this.updateScheduleWindow.setValues(this.menu.record);
        this.updateScheduleWindow.show(e.target);
    },
    removeSchedule: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.player_schedule_remove'),
        	text		: _('narrowcasting.player_schedule_remove_confirm'),
        	url			: Narrowcasting.config.connector_url,
        	params		: {
            	action		: 'mgr/players/schedules/remove',
            	id			: this.menu.record.id
            },
            listeners	: {
            	'success'	: {
            		fn			: this.refresh,
		        	scope		: this
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

Ext.reg('narrowcasting-grid-player-schedules', Narrowcasting.grid.PlayerSchedules);

Narrowcasting.window.CreatePlayerSchedule = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.player_schedule_create'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/players/schedules/create'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'player_id'
        }, {
        	xtype		: 'narrowcasting-combo-broadcasts',
        	fieldLabel	: _('narrowcasting.label_schedule_broadcast'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_broadcast_desc'),
        	name		: 'broadcast_id',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_schedule_broadcast_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textarea',
        	fieldLabel	: _('narrowcasting.label_schedule_description'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_schedule_description_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'narrowcasting-combo-schedules-type',
        	fieldLabel	: _('narrowcasting.label_schedule_type'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_type_desc'),
        	name		: 'type',
        	anchor		: '100%',
        	allowBlank	: false,
        	forId		: {
	        	day			: 'narrowcasting-schedule-create-day',
	        	date 		: 'narrowcasting-schedule-create-date'
        	},
        	listeners	: {
	        	'change'	: {
		        	fn 			: this.setType,
		        	scope 		: this
	        	},
	        	'afterrender' : {
		        	fn 			: this.setType,
		        	scope 		: this
	        	}
        	}
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_schedule_type_desc'),
            cls			: 'desc-under'
        }, {
	        id 			: 'narrowcasting-schedule-create-day',
		    layout		: 'form',
		    labelSeparator : '',
	        items		: [{
	        	xtype		: 'narrowcasting-combo-schedules-day',
	        	fieldLabel	: _('narrowcasting.label_schedule_day'),
	        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_day_desc'),
	        	name		: 'day',
	        	anchor		: '100%'
	        }, {
	        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
	            html		: _('narrowcasting.label_schedule_day_desc'),
	            cls			: 'desc-under'
	        }]
        }, {
	        id 			: 'narrowcasting-schedule-create-date',
	        layout		: 'form',
	        labelSeparator : '',
	        items		: [{
		        layout		: 'column',
		        defaults	: {
		            layout		: 'form',
		            labelSeparator : ''
		        },
		        items: [{
		        	columnWidth	: .5,
		            items		: [{
			        	xtype		: 'datefield',
			        	fieldLabel	: _('narrowcasting.label_schedule_start_date'),
			        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_start_date_desc'),
			        	name		: 'start_date',
			        	anchor		: '100%',
			        	format		: MODx.config.manager_date_format
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('narrowcasting.label_schedule_start_date_desc'),
			            cls			: 'desc-under'
			        }]
			    }, {
					columnWidth	: .5,
					style		: 'margin-right: 0;',
					items		: [{
			        	xtype		: 'datefield',
			        	fieldLabel	: _('narrowcasting.label_schedule_end_date'),
			        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_end_date_desc'),
			        	name		: 'end_date',
			        	anchor		: '100%',
			        	format		: MODx.config.manager_date_format
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('narrowcasting.label_schedule_end_date_desc'),
			            cls			: 'desc-under'
			        }]
			    }]
			}]
		}, {
        	xtype		: 'checkbox',
        	boxLabel	: _('narrowcasting.label_schedule_entire_day'),
        	name		: 'entire_day',
        	anchor		: '100%',
        	inputValue	: 1,
        	checked 	: true,
        	forId		: 'narrowcasting-schedule-create-time',
        	listeners	: {
	        	'check'		: {
		        	fn 			: this.setEntireDay,
		        	scope 		: this
	        	},
	        	'afterrender' : {
		        	fn 			: this.setEntireDay,
		        	scope 		: this
	        	}
        	}
        }, {
	        id 			: 'narrowcasting-schedule-create-time',
	        layout		: 'form',
	        items		: [{
		        layout		: 'column',
	            defaults	: {
	                layout		: 'form',
	                labelSeparator : ''
	            },
	            items: [{
	            	columnWidth	: .5,
	                items		: [{
			        	xtype		: 'timefield',
			        	fieldLabel	: _('narrowcasting.label_schedule_start_time'),
			        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_start_time_desc'),
			        	name		: 'start_time',
			        	anchor		: '100%',
			        	format		: MODx.config.manager_time_format
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('narrowcasting.label_schedule_start_time_desc'),
			            cls			: 'desc-under'
			        }]
			    }, {
					columnWidth	: .5,
					style		: 'margin-right: 0;',
					items		: [{
			        	xtype		: 'timefield',
			        	fieldLabel	: _('narrowcasting.label_schedule_end_time'),
			        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_end_time_desc'),
			        	name		: 'end_time',
			        	anchor		: '100%',
			        	format		: MODx.config.manager_time_format
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('narrowcasting.label_schedule_end_time_desc'),
			            cls			: 'desc-under'
			        }]
			    }]
			}]
		}]
    });
    
    Narrowcasting.window.CreatePlayerSchedule.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.CreatePlayerSchedule, MODx.Window, {
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

Ext.reg('narrowcasting-window-player-schedule-create', Narrowcasting.window.CreatePlayerSchedule);

Narrowcasting.window.UpdatePlayerSchedule = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.player_schedule_update'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/players/schedules/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'hidden',
            name		: 'player_id'
        }, {
        	xtype		: 'narrowcasting-combo-broadcasts',
        	fieldLabel	: _('narrowcasting.label_schedule_broadcast'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_broadcast_desc'),
        	name		: 'broadcast_id',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_schedule_broadcast_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textarea',
        	fieldLabel	: _('narrowcasting.label_schedule_description'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_schedule_description_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'narrowcasting-combo-schedules-type',
        	fieldLabel	: _('narrowcasting.label_schedule_type'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_type_desc'),
        	name		: 'type',
        	anchor		: '100%',
        	allowBlank	: false,
        	forId		: {
	        	day			: 'narrowcasting-schedule-update-day',
	        	date 		: 'narrowcasting-schedule-update-date'
        	},
        	listeners	: {
	        	'change'	: {
		        	fn 			: this.setType,
		        	scope 		: this
	        	},
	        	'afterrender' : {
		        	fn 			: this.setType,
		        	scope 		: this
	        	}
        	}
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_schedule_type_desc'),
            cls			: 'desc-under'
        }, {
	        id 			: 'narrowcasting-schedule-update-day',
		    layout		: 'form',
		    labelSeparator : '',
	        items		: [{
	        	xtype		: 'narrowcasting-combo-schedules-day',
	        	fieldLabel	: _('narrowcasting.label_schedule_day'),
	        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_day_desc'),
	        	name		: 'day',
	        	anchor		: '100%'
	        }, {
	        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
	            html		: _('narrowcasting.label_schedule_day_desc'),
	            cls			: 'desc-under'
	        }]
        }, {
	        id 			: 'narrowcasting-schedule-update-date',
	        layout		: 'form',
	        labelSeparator : '',
	        items		: [{
		        layout		: 'column',
		        defaults	: {
		            layout		: 'form',
		            labelSeparator : ''
		        },
		        items: [{
		        	columnWidth	: .5,
		            items		: [{
			        	xtype		: 'datefield',
			        	fieldLabel	: _('narrowcasting.label_schedule_start_date'),
			        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_start_date_desc'),
			        	name		: 'start_date',
			        	anchor		: '100%',
			        	format		: MODx.config.manager_date_format
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('narrowcasting.label_schedule_start_date_desc'),
			            cls			: 'desc-under'
			        }]
			    }, {
					columnWidth	: .5,
					style		: 'margin-right: 0;',
					items		: [{
			        	xtype		: 'datefield',
			        	fieldLabel	: _('narrowcasting.label_schedule_end_date'),
			        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_end_date_desc'),
			        	name		: 'end_date',
			        	anchor		: '100%',
			        	format		: MODx.config.manager_date_format
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('narrowcasting.label_schedule_end_date_desc'),
			            cls			: 'desc-under'
			        }]
			    }]
			}]
		}, {
        	xtype		: 'checkbox',
        	boxLabel	: _('narrowcasting.label_schedule_entire_day'),
        	name		: 'entire_day',
        	anchor		: '100%',
        	inputValue	: 1,
        	checked 	: true,
        	forId		: 'narrowcasting-schedule-update-time',
        	listeners	: {
	        	'check'		: {
		        	fn 			: this.setEntireDay,
		        	scope 		: this
	        	},
	        	'afterrender' : {
		        	fn 			: this.setEntireDay,
		        	scope 		: this
	        	}
        	}
        }, {
	        id 			: 'narrowcasting-schedule-update-time',
	        layout		: 'form',
	        items		: [{
		        layout		: 'column',
	            defaults	: {
	                layout		: 'form',
	                labelSeparator : ''
	            },
	            items: [{
	            	columnWidth	: .5,
	                items		: [{
			        	xtype		: 'timefield',
			        	fieldLabel	: _('narrowcasting.label_schedule_start_time'),
			        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_start_time_desc'),
			        	name		: 'start_time',
			        	anchor		: '100%',
			        	format		: MODx.config.manager_time_format,
			        	value 		: '00:00'
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('narrowcasting.label_schedule_start_time_desc'),
			            cls			: 'desc-under'
			        }]
			    }, {
					columnWidth	: .5,
					style		: 'margin-right: 0;',
					items		: [{
			        	xtype		: 'timefield',
			        	fieldLabel	: _('narrowcasting.label_schedule_end_time'),
			        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_schedule_end_time_desc'),
			        	name		: 'end_time',
			        	anchor		: '100%',
			        	format		: MODx.config.manager_time_format,
			        	value 		: '00:00'
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('narrowcasting.label_schedule_end_time_desc'),
			            cls			: 'desc-under'
			        }]
			    }]
			}]
		}]
    });
    
    Narrowcasting.window.UpdatePlayerSchedule.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.UpdatePlayerSchedule, MODx.Window, {
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

Ext.reg('narrowcasting-window-player-schedule-update', Narrowcasting.window.UpdatePlayerSchedule);

Narrowcasting.combo.SchedulesType = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type', 'label'],
            data	: [
                ['day', _('narrowcasting.schedule_day')],
				['date', _('narrowcasting.schedule_date')]
            ]
        }),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'type',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Narrowcasting.combo.SchedulesType.superclass.constructor.call(this,config);
};

Ext.extend(Narrowcasting.combo.SchedulesType, MODx.combo.ComboBox);

Ext.reg('narrowcasting-combo-schedules-type', Narrowcasting.combo.SchedulesType);

Narrowcasting.combo.SchedulesDay = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type', 'label'],
            data	: [
                ['0', _('sunday')],
				['1', _('monday')],
				['2', _('tuesday')],
				['3', _('wednesday')],
				['4', _('thursday')],
				['5', _('friday')],
				['6', _('saturday')]  
            ]
        }),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'day',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Narrowcasting.combo.SchedulesDay.superclass.constructor.call(this,config);
};

Ext.extend(Narrowcasting.combo.SchedulesDay, MODx.combo.ComboBox);

Ext.reg('narrowcasting-combo-schedules-day', Narrowcasting.combo.SchedulesDay);