Narrowcasting.grid.Players = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('narrowcasting.player_create'),
        cls			:'primary-button',
        handler		: this.createPlayer,
        scope		: this
    }, {
		xtype		: 'checkbox',
		name		: 'narrowcasting-refresh-players',
        id			: 'narrowcasting-refresh-players',
		boxLabel	: _('narrowcasting.auto_refresh_grid'),
        checked     : true,
		listeners	: {
			'check'		: {
				fn 			: this.autoRefresh,
				scope 		: this	
			},
            'afterrender' : {
                fn 			: this.autoRefresh,
                scope 		: this
            }
		}
	}, '->', {
        xtype		: 'textfield',
        name 		: 'narrowcasting-filter-search-players',
        id			: 'narrowcasting-filter-search-players',
        emptyText	: _('search')+'...',
        listeners	: {
	        'change'	: {
	        	fn			: this.filterSearch,
	        	scope		: this
	        },
	        'render'	: {
		        fn			: function(cmp) {
			        new Ext.KeyMap(cmp.getEl(), {
				        key		: Ext.EventObject.ENTER,
			        	fn		: this.blur,
				        scope	: cmp
			        });
		        },
		        scope		: this
	        }
        }
    }, {
    	xtype		: 'button',
    	cls			: 'x-form-filter-clear',
    	id			: 'narrowcasting-filter-clear-players',
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
            header		: _('narrowcasting.label_player_key'),
            dataIndex	: 'key',
            sortable	: true,
            editable	: false,
            width		: 175,
            fixed		: true,
			renderer	: this.renderKey
        }, {
            header		: _('narrowcasting.label_player_name'),
            dataIndex	: 'name',
            sortable	: true,
            editable	: false,
            width		: 250
        }, {
            header		: _('narrowcasting.label_player_current_broadcast'),
            dataIndex	: 'current_broadcast',
            sortable	: true,
            editable	: false,
            width		: 150,
            fixed 		: true
        }, {
		   	header		: _('narrowcasting.label_player_next_sync'),
            dataIndex	: 'next_sync',
            sortable	: true,
            editable	: false,
            width		: 150,
            fixed		: true,
            renderer	: this.renderNextSync
        }, {
            header		: _('last_modified'),
            dataIndex	: 'editedon',
            sortable	: true,
            editable	: false,
            fixed		: true,
			width		: 200,
			renderer	: this.renderDate
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'narrowcasting-grid-players',
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
        	action		: 'mgr/players/getlist'
        },
        fields		: ['id', 'key', 'name', 'description', 'type', 'resolution', 'restart', 'last_online', 'last_broadcast_id', 'editedon', 'mode', 'mode_formatted', 'online', 'current_broadcast', 'next_sync', 'url'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        plugins		: expander,
        refresher	: {
	        timer 		: null,
	        duration	: 30,
	        count 		: 0
        },
        refreshGrid : []
    });
    
    Narrowcasting.grid.Players.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.grid.Players, MODx.grid.Grid, {
	autoRefresh: function(tf, nv) {
		if (tf.getValue()) {
			this.config.refresher.timer = setInterval((function() {
				tf.setBoxLabel(_('narrowcasting.auto_refresh_grid') + ' (' + (this.config.refresher.duration - this.config.refresher.count) + ')');
				
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
	    
	    Ext.getCmp('narrowcasting-filter-search-players').reset();
	    
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	    	text 	: _('narrowcasting.player_view'),
	    	handler : this.viewPlayer,
	    	scope 	: this 
	    }, '-', {
            text	: '<i class="icon icon-power-off"></i> ' + (0 == this.menu.record.restart ? _('narrowcasting.player_restart') : _('narrowcasting.player_restart_cancel')),
            handler	: this.restartPlayer,
            scope	: this
        }, '-', {
	        text	: _('narrowcasting.player_update'),
	        handler	: this.updatePlayer,
	        scope	: this
	    }, {
	        text	: _('narrowcasting.player_schedule'),
	        handler	: this.schedulePlayer,
	        scope	: this
	    }, '-', {
		    text	: _('narrowcasting.player_remove'),
		    handler	: this.removePlayer,
		    scope	: this
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
	        xtype		: 'narrowcasting-window-player-create',
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: function(data) {
			        	this.refreshGrids();
            			this.refresh();
            		},
		        	scope		: this
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
	        xtype		: 'narrowcasting-window-player-update',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: function() {
            			this.refreshGrids();
            			this.refresh();
            		},
		        	scope		: this
		        }
	        }
        });
        
        this.updatePlayerWindow.setValues(this.menu.record);
        this.updatePlayerWindow.show(e.target);
    },
    restartPlayer: function() {
        MODx.msg.confirm({
            title 		: 0 == this.menu.record.restart ? _('narrowcasting.player_restart') : _('narrowcasting.player_restart_cancel'),
            text		: 0 == this.menu.record.restart ? _('narrowcasting.player_restart_confirm') : _('narrowcasting.player_restart_cancel_confirm'),
            url			: Narrowcasting.config.connector_url,
            params		: {
                action		: 'mgr/players/restart',
                id			: this.menu.record.id
            },
            listeners	: {
                'success'	: {
                    fn			: function() {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope		: this
                }
            }
        });
    },
    removePlayer: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.player_remove'),
        	text		: _('narrowcasting.player_remove_confirm'),
        	url			: Narrowcasting.config.connector_url,
        	params		: {
            	action		: 'mgr/players/remove',
            	id			: this.menu.record.id
            },
            listeners	: {
            	'success'	: {
            		fn			: function() {
						this.refreshGrids();
            			this.refresh();
            		},
		        	scope		: this
            	}
            }
    	});
    },
    viewPlayer: function(btn, e) {
        if (this.viewPlayerWindow) {
	        this.viewPlayerWindow.destroy();
        }
        
        this.viewPlayerWindow = MODx.load({
	        xtype		: 'narrowcasting-window-player-view',
	        record		: this.menu.record,
	        closeAction	: 'close'
        });
        
        this.viewPlayerWindow.setValues(this.menu.record);
        this.viewPlayerWindow.show(e.target);
    },
    schedulePlayer: function(btn, e) {
        if (this.schedulePlayerWindow) {
	        this.schedulePlayerWindow.destroy();
        }
        
        this.schedulePlayerWindow = MODx.load({
	        modal		: true,
	        xtype		: 'narrowcasting-window-player-schedule',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: function() {
            			this.refreshGrids();
            			this.refresh();
            		},
		        	scope		: this
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

        if (1 == e.data.restart) {
            return String.format('<span class="icon icon-power-off icon-player-restart"></span>{0}', _('narrowcasting.next_sync', {
                time: minutes + ':' + seconds
            }));
        }

        return _('narrowcasting.next_sync', {
            time: minutes + ':' + seconds
        });
	},
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('narrowcasting-grid-players', Narrowcasting.grid.Players);

Narrowcasting.window.CreatePlayer = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.player_create'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/players/create'
        },
        fields		: [{
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_player_name'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_player_name_desc'),
        	name		: 'name',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_player_name_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textarea',
        	fieldLabel	: _('narrowcasting.label_player_description'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_player_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_player_description_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_player_resolution'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_player_resolution_desc'),
        	name		: 'resolution',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_player_resolution_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_player_type'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_player_type_desc'),
        	name		: 'type',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_player_type_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Narrowcasting.window.CreatePlayer.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.CreatePlayer, MODx.Window);

Ext.reg('narrowcasting-window-player-create', Narrowcasting.window.CreatePlayer);

Narrowcasting.window.UpdatePlayer = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.player_update'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/players/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_player_name'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_player_name_desc'),
        	name		: 'name',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_player_name_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textarea',
        	fieldLabel	: _('narrowcasting.label_player_description'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_player_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_player_description_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_player_resolution'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_player_resolution_desc'),
        	name		: 'resolution',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_player_resolution_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_player_type'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_player_type_desc'),
        	name		: 'type',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_player_type_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Narrowcasting.window.UpdatePlayer.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.UpdatePlayer, MODx.Window);

Ext.reg('narrowcasting-window-player-update', Narrowcasting.window.UpdatePlayer);

Narrowcasting.window.ViewPlayer = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.player_view'),
        cls			: 'narrowcasting-window-padding',
        fields		: [{
        	html		: '<p>' + _('narrowcasting.player_view_desc') + '</p>',
            cls			: 'panel-desc'
		}, {
        	xtype		: 'textfield',
        	name		: 'url',
        	anchor		: '100%',
        	hideLabel	: true
        }],
        buttons 	: [{
            text		: _('ok'),
            cls			: 'primary-button',
            handler		: function() {
	            if ('close' !== config.closeAction) {
		        	this.hide();
		        } else {
			        this.close(); 
			    }
	        },
            scope		: this
        }]
    });
    
    Narrowcasting.window.ViewPlayer.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.ViewPlayer, MODx.Window);

Ext.reg('narrowcasting-window-player-view', Narrowcasting.window.ViewPlayer);

Narrowcasting.window.SchedulePlayer = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	width		: 600,
    	autoHeight	: true,
        title 		: _('narrowcasting.player_schedule'),
        cls			: 'narrowcasting-window-padding',
        items		: [{
            xtype		: 'narrowcasting-grid-player-schedules',
            record		: config.record,
            preventRender	: true
        }],
        buttons 	: [{
	        text		: _('narrowcasting.player_calendar'),
	        handler		: this.calendarPlayer,
	        scope		: this
        }, {
            text		: _('ok'),
            cls			: 'primary-button',
            handler		: function() {
	            if ('close' !== config.closeAction) {
		        	this.hide();
		        } else {
			        this.close(); 
			    }
	        },
            scope		: this
        }]
    });
    
    Narrowcasting.window.SchedulePlayer.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.SchedulePlayer, MODx.Window, {
	calendarPlayer: function(btn, e) {
		if (this.calendarPlayerWindow) {
	        this.calendarPlayerWindow.destroy();
        }
        
        this.calendarPlayerWindow = MODx.load({
	        modal		: true,
	        xtype		: 'narrowcasting-window-player-calendar',
	        record		: this.config.record,
	        closeAction	: 'close'
        });
        
        this.calendarPlayerWindow.setValues(this.config.record);
        this.calendarPlayerWindow.show(e.target);
    }
});

Ext.reg('narrowcasting-window-player-schedule', Narrowcasting.window.SchedulePlayer);

Narrowcasting.window.CalendarPlayer = function(config) {
    config = config || {};
    
    var eventStore = new Ext.ensible.cal.EventStore({
        proxy			: new Ext.data.HttpProxy({
	    	url 			: Narrowcasting.config.connector_url,
		}),
		baseParams		: {
			action			: 'mgr/players/schedules/getcalendar',
			player_id		: config.record.id
		},
        reader			: new Ext.data.JsonReader({
	        idProperty		: 'id',
	        totalProperty 	: 'total',
	        successProperty : 'success',
	        root			: 'results',
	        fields			: Ext.ensible.cal.EventRecord.prototype.fields.getRange()
	    }),
        autoLoad		: true
    });
		    
    var calendarStore = new Ext.data.JsonStore({
	    url 			: Narrowcasting.config.connector_url,
        baseParams		: {
			action			: 'mgr/broadcasts/getcalendars'
		},
		totalProperty 	: 'total',
        successProperty : 'success',
        root			: 'results',
        fields			: Ext.ensible.cal.CalendarRecord.prototype.fields.getRange()
    });
		    
	calendarStore.load();
	
    Ext.applyIf(config, {
    	height		: 700,
    	width		: 900,
        title 		: _('narrowcasting.player_calendar'),
        layout		: 'fit',
        cls			: 'narrowcasting-window-padding',
        items		: [{
	        xtype			: 'extensible.calendarpanel',
	        readOnly		: true,
	        showDayView		: false,
	        showWeekView	: true,
	        showMultiWeekView : false,
	        showMonthView	: false,
	        activeItem		: 0,
	        eventStore		: eventStore,
		    calendarStore	: calendarStore,
		    /*tbar			: [{
		    	xtype			: 'narrowcasting-combo-broadcasts',
		        name 			: 'narrowcasting-filter-search-players-broadcast',
		        id				: 'narrowcasting-filter-search-players-broadcast',
		        emptyText		: _('narrowcasting.filter_broadcast'),
		        listeners		: {
			        'change'		: {
			        	fn				: this.filterBroadcast,
			        	scope			: this
			        }
			    }
		    }]*/
		}],
        buttons 	: [{
            text		: _('ok'),
            cls 		: 'primary-button',
            handler		: function() {
	            if ('close' !== config.closeAction) {
		        	this.hide();
		        } else {
			        this.close(); 
			    }
	        },
            scope		: this
        }]
    });
    
    Narrowcasting.window.CalendarPlayer.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.CalendarPlayer, MODx.Window, {
	/*filterBroadcast: function(tf, nv, ov) {
		
    },*/
});

Ext.reg('narrowcasting-window-player-calendar', Narrowcasting.window.CalendarPlayer);

Narrowcasting.combo.Players = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        url			: Narrowcasting.config.connector_url,
        baseParams 	: {
            action		: 'mgr/players/getnodes',
            broadcast	: config.broadcast || null
        },
        fields		: ['id', 'key', 'name'],
        hiddenName	: 'player',
        pageSize	: 15,
        valueField	: 'id',
        displayField: 'name'
    });
    
    Narrowcasting.combo.Players.superclass.constructor.call(this,config);
};

Ext.extend(Narrowcasting.combo.Players, MODx.combo.ComboBox);

Ext.reg('narrowcasting-combo-players', Narrowcasting.combo.Players);