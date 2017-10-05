Narrowcasting.grid.Broadcasts = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('narrowcasting.broadcast_create'),
        cls			:'primary-button',
        handler		: this.createBroadcast,
        scope		: this
    }, {
		xtype		: 'checkbox',
		name		: 'narrowcasting-refresh-broadcasts',
        id			: 'narrowcasting-refresh-broadcasts',
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
        name 		: 'narrowcasting-filter-search-broadcasts',
        id			: 'narrowcasting-filter-search-broadcasts',
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
    	id			: 'narrowcasting-filter-clear-broadcasts',
    	text		: _('filter_clear'),
    	listeners	: {
        	'click'		: {
        		fn			: this.clearFilter,
        		scope		: this
        	}
        }
    }];
    
    columns = new Ext.grid.ColumnModel({
        columns: [{
            header		: _('narrowcasting.label_broadcast_name'),
            dataIndex	: 'name_formatted',
            sortable	: true,
            editable	: false,
            width		: 250
        }, {
            header		: _('narrowcasting.label_broadcast_players'),
            dataIndex	: null,
            sortable	: true,
            editable	: false,
            width		: 250,
            fixed 		: true,
            renderer	: this.renderPlayers
        }, {
            header		: _('narrowcasting.label_broadcast_slides'),
            dataIndex	: 'slides',
            sortable	: true,
            editable	: false,
            width		: 100,
            fixed 		: true
        }, {
            header		: _('narrowcasting.label_broadcast_feeds'),
            dataIndex	: 'feeds',
            sortable	: true,
            editable	: false,
            width		: 100,
            fixed 		: true
        }, {
            header		: _('narrowcasting.label_broadcast_last_sync'),
            dataIndex	: 'sync',
            sortable	: true,
            editable	: false,
			width		: 200,
            fixed		: true,
			renderer	: this.renderSync
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'narrowcasting-grid-broadcasts',
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
        	action		: 'mgr/broadcasts/getlist'
        },
        fields		: ['id', 'resource_id', 'ticker_url', 'name', 'name_formatted', 'description', 'template', 'editedon', 'url', 'slides', 'feeds', 'players', 'sync'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        refresher	: {
	        timer 		: null,
	        duration	: 30,
	        count 		: 0
        }
    });
    
    Narrowcasting.grid.Broadcasts.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.grid.Broadcasts, MODx.grid.Grid, {
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
	    
	    Ext.getCmp('narrowcasting-filter-search-broadcasts').reset();
	    
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('narrowcasting.broadcast_preview'),
	        handler	: this.previewBroadcast,
	        scope	: this
	    }, '-', {
	        text	: '<i class="icon icon-refresh"></i> ' + _('narrowcasting.broadcast_sync'),
	        handler	: this.syncBroadcast,
	        scope	: this
	    }, '-', {
	    	text 	: _('narrowcasting.broadcast_slides'),
	    	handler : this.viewSlides,
	    	scope 	: this 
	    }, {
	    	text 	: _('narrowcasting.broadcast_feeds'),
	    	handler : this.viewFeeds,
	    	scope 	: this 
	    }, '-', {
	        text	: _('narrowcasting.broadcast_update'),
	        handler	: this.updateBroadcast,
	        scope	: this
	    }, {
            text	: _('narrowcasting.broadcast_duplicate'),
            handler	: this.duplicateBroadcast,
            scope	: this
        }, '-', {
		    text	: _('narrowcasting.broadcast_remove'),
		    handler	: this.removeBroadcast,
		    scope	: this
		}];
    },
    createBroadcast: function(btn, e) {
        if (this.createBroadcastWindow) {
	        this.createBroadcastWindow.destroy();
        }
        
        this.createBroadcastWindow = MODx.load({
	        xtype		: 'narrowcasting-window-broadcast-create',
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
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
	        xtype		: 'narrowcasting-window-broadcast-update',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
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
            name    : _('narrowcasting.broadcast_name_duplicate', {
                name    : this.menu.record.name
            })
        }, this.menu.record);

        this.duplicateBroadcastWindow = MODx.load({
            xtype		: 'narrowcasting-window-broadcast-duplicate',
            record		: record,
            closeAction	: 'close',
            listeners	: {
                'success'	: {
                    fn			: this.refresh,
                    scope		: this
                }
            }
        });

        this.duplicateBroadcastWindow.setValues(record);
        this.duplicateBroadcastWindow.show(e.target);
    },
    removeBroadcast: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.broadcast_remove'),
        	text		: _('narrowcasting.broadcast_remove_confirm'),
        	url			: Narrowcasting.config.connector_url,
        	params		: {
            	action		: 'mgr/broadcasts/remove',
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
    previewBroadcast: function(btn, e) {
        if (this.previewBroadcastWindow) {
	        this.previewBroadcastWindow.destroy();
        }
        
        this.previewBroadcastWindow = MODx.load({
	        xtype		: 'narrowcasting-window-broadcast-preview',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        saveBtnText	: _('narrowcasting.show_broadcast_preview'),
	        listeners	: {
            	'success'	: {
            		fn			: function(data) {
	            		this.showPreviewBroadcast(data.a.result.object);
					},
		        	scope		: this
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
	        modal 		: true,
	        xtype		: 'narrowcasting-window-broadcast-preview-show',
	        record		: record,
	        closeAction	: 'close'
        });
        
        this.showPreviewBroadcastWindow.show();
    },
    syncBroadcast: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.broadcast_sync'),
        	text		: _('narrowcasting.broadcast_sync_confirm'),
        	url			: Narrowcasting.config.connector_url,
        	params		: {
            	action		: 'mgr/broadcasts/sync',
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
    viewSlides: function(btn, e) {
        if (this.viewSlidesWindow) {
	        this.viewSlidesWindow.destroy();
        }
        
        this.viewSlidesWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'narrowcasting-window-broadcast-slides',
	        record		: this.menu.record,
	        closeAction	: 'close',
            listeners	: {
            	'close'		: {
            		fn			: this.refresh,
		        	scope		: this
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
	        modal 		: true,
	        xtype		: 'narrowcasting-window-broadcast-feeds',
	        record		: this.menu.record,
	        closeAction	: 'close',
            listeners	: {
            	'close'		: {
            		fn			: this.refresh,
		        	scope		: this
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
				return _('narrowcasting.sync_never');
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

Ext.reg('narrowcasting-grid-broadcasts', Narrowcasting.grid.Broadcasts);

Narrowcasting.window.CreateBroadcast = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.broadcast_create'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/broadcasts/create'
        },
        fields		: [{
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_broadcast_name'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_name_desc'),
        	name		: 'name',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_name_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textarea',
        	fieldLabel	: _('narrowcasting.label_broadcast_description'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_description_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'narrowcasting-combo-templates',
            fieldLabel	: _('narrowcasting.label_broadcast_template'),
            description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_template_desc'),
            name		: 'template',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_template_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_broadcast_ticker_url'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_ticker_url_desc'),
        	name		: 'ticker_url',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_ticker_url_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Narrowcasting.window.CreateBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.CreateBroadcast, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-create', Narrowcasting.window.CreateBroadcast);

Narrowcasting.window.UpdateBroadcast = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.broadcast_update'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/broadcasts/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'hidden',
            name		: 'resource_id'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_broadcast_name'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_name_desc'),
        	name		: 'name',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_name_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textarea',
        	fieldLabel	: _('narrowcasting.label_broadcast_description'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_description_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'narrowcasting-combo-templates',
            fieldLabel	: _('narrowcasting.label_broadcast_template'),
            description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_template_desc'),
            name		: 'template',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_template_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_broadcast_ticker_url'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_ticker_url_desc'),
        	name		: 'ticker_url',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_ticker_url_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Narrowcasting.window.UpdateBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.UpdateBroadcast, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-update', Narrowcasting.window.UpdateBroadcast);

Narrowcasting.window.DuplicateBroadcast = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight	: true,
        title 		: _('narrowcasting.broadcast_duplicate'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/broadcasts/duplicate'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        },{
            xtype		: 'textfield',
            fieldLabel	: _('narrowcasting.label_broadcast_name'),
            description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_name_desc'),
            name		: 'name',
            anchor		: '100%',
            allowBlank	: false
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_name_desc'),
            cls			: 'desc-under'
        }]
    });

    Narrowcasting.window.DuplicateBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.DuplicateBroadcast, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-duplicate', Narrowcasting.window.DuplicateBroadcast);

Narrowcasting.window.PreviewBroadcast = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.broadcast_preview'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/broadcasts/preview'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'narrowcasting-combo-players',
            fieldLabel	: _('narrowcasting.label_broadcast_preview_player'),
            description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_preview_player_desc'),
            name		: 'player',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_preview_player_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Narrowcasting.window.PreviewBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.PreviewBroadcast, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-preview', Narrowcasting.window.PreviewBroadcast);

Narrowcasting.window.ShowPreviewBroadcast = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
		maximized	: true,
        title 		: _('narrowcasting.broadcast_preview') + _('narrowcasting.preview_resolution', {
            resolution  : config.record.resolution
        }),
        cls			: 'narrowcasting-window-preview',
        items		: [{
        	xtype		: 'container',
			layout		: {
            	type		: 'vbox',
				align		: 'stretch'
			},
			width		: '100%',
			height		: '100%',
			items		:[{
				autoEl 		: {
	                tag 		: 'iframe',
	                src			: config.record.url,
	                width		: '100%',
					height		: '100%',
					frameBorder	: 0,
				}
			}]
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
        }],
        listeners	: {
			'bodyresize' : {
				fn 			: this.setResolutionSize,
				scope 		: this
			}
		}
    });
    
    Narrowcasting.window.ShowPreviewBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.ShowPreviewBroadcast, MODx.Window, {
	setResolutionSize: function() {
		var width 		= this.config.record.width,
			height 		= this.config.record.height,
			maxWidth	= parseInt(this.getEl().select('.x-window-body').first().getWidth()),
			maxHeight 	= parseInt(this.getEl().select('.x-window-body').first().getHeight());

		var ratio 		= maxHeight / (height / 100),
			newWidth 	= (width / 100) * ratio,
			newHeight 	= maxHeight;
		
		this.getEl().select('iframe').setWidth(newWidth);
		this.getEl().select('iframe').setHeight(newHeight);
	}
});

Ext.reg('narrowcasting-window-broadcast-preview-show', Narrowcasting.window.ShowPreviewBroadcast);

Narrowcasting.window.Slides = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	width		: 600,
    	height 		: 500,
        title 		: _('narrowcasting.broadcast_slides'),
        cls			: 'narrowcasting-window-padding',
        items		: [{
	    	html 		: '<p>' + _('narrowcasting.broadcast_slides_desc') + '</p>',
	    	cls			: 'panel-desc'
	    }, {
        	layout		: 'column',
        	border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
        	items		: [{
	        	columnWidth	: .5,
				items 		: [{
		            xtype		: 'narrowcasting-tree-broadcast-slides',
		            broadcast 	: config.record.id,
		            enableDD	: true
		        }]
	        }, {
		        columnWidth	: .5,
		        style		: 'margin-right: 0;',
				items 		: [{
		            xtype		: 'narrowcasting-tree-available-slides',
		            broadcast 	: config.record.id,
		            enableDD	: true
		        }]
			}]
        }],
        buttons 	: [{
            text		: _('ok'),
            cls			: 'primary-button',
            handler		: function() {
	            this.fireEvent('sucesss');
	            
	            if ('close' !== config.closeAction) {
		        	this.hide();
		        } else {
			        this.close(); 
			    }
	        },
            scope		: this
        }]
    });
    
    Narrowcasting.window.Slides.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.Slides, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-slides', Narrowcasting.window.Slides);

Narrowcasting.window.Feeds = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	width		: 600,
    	autoHeight	: true,
        title 		: _('narrowcasting.broadcast_feeds'),
        cls			: 'narrowcasting-window-padding',
        items		: [{
	    	html 		: '<p>' + _('narrowcasting.broadcast_feeds_desc') + '</p>',
	    	cls			: 'panel-desc'
	    }, {
            xtype		: 'narrowcasting-grid-broadcast-feeds',
            record		: config.record,
            preventRender	: true
        }],
        buttons 	: [{
            text		: _('ok'),
            cls			: 'primary-button',
            handler		: function() {
	            this.fireEvent('sucesss');
	            
	            if ('close' !== config.closeAction) {
		        	this.hide();
		        } else {
			        this.close(); 
			    }
	        },
            scope		: this
        }]
    });
    
    Narrowcasting.window.Slides.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.Feeds, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-feeds', Narrowcasting.window.Feeds);

Narrowcasting.combo.Broadcasts = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        url			: Narrowcasting.config.connector_url,
        baseParams 	: {
            action		: 'mgr/broadcasts/getnodes'
        },
        fields		: ['id', 'name', 'calendar'],
        hiddenName	: 'broadcast_id',
        pageSize	: 15,
        valueField	: 'id',
        displayField: 'name',
        tpl			: new Ext.XTemplate('<tpl for=".">' + 
        	'<div class="x-combo-list-item">' + 
        		'<span class="x-cal-combo x-cal-{calendar}">' + 
        			'<span class="ext-cal-picker-icon"></span>' + 
        		'</span> {name}' + 
			'</div>' + 
		'</tpl>')
    });
    
    Narrowcasting.combo.Broadcasts.superclass.constructor.call(this,config);
};

Ext.extend(Narrowcasting.combo.Broadcasts, MODx.combo.ComboBox);

Ext.reg('narrowcasting-combo-broadcasts', Narrowcasting.combo.Broadcasts);

Narrowcasting.combo.Templates = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        url			: Narrowcasting.config.connector_url,
        baseParams 	: {
            action		: 'mgr/broadcasts/templates/getnodes'
        },
        fields		: ['id', 'templatename', 'description', 'category_name'],
        hiddenName	: 'template',
        pageSize	: 15,
        valueField	: 'id',
        displayField: 'templatename',
        tpl			: new Ext.XTemplate('<tpl for=".">' + 
        	'<div class="x-combo-list-item">' + 
        		'<span style="font-weight: bold">{templatename}</span><br />{description}' + 
			'</div>' + 
		'</tpl>')
    });
    
    Narrowcasting.combo.Templates.superclass.constructor.call(this,config);
};

Ext.extend(Narrowcasting.combo.Templates, MODx.combo.ComboBox);

Ext.reg('narrowcasting-combo-templates', Narrowcasting.combo.Templates);