Narrowcasting.grid.BroadcastFeeds = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('narrowcasting.broadcast_feed_create'),
        cls			:'primary-button',
        handler		: this.createFeed,
        scope		: this
    }];
    
    expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{description}</p>'
        )
    });

    columns = new Ext.grid.ColumnModel({
        columns: [expander, {
            header		: _('narrowcasting.label_feed_name'),
            dataIndex	: 'name',
            sortable	: true,
            editable	: false,
            width		: 125,
            fixed 		: true
        }, {
            header		: _('narrowcasting.label_feed_url'),
            dataIndex	: 'url',
            sortable	: true,
            editable	: false,
            width		: 250,
            //fixed 		: true,
            renderer	: this.renderUrl
        }, {
            header		: _('narrowcasting.label_feed_published'),
            dataIndex	: 'published',
            sortable	: true,
            editable	: true,
            width		: 100,
            fixed		: true,
			renderer	: this.renderBoolean
		}]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'narrowcasting-grid-broadcast-feeds',
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
        	action		: 'mgr/broadcasts/feeds/getlist',
        	broadcast_id : config.record.id
        },
        fields		: ['id', 'broadcast_id', 'key', 'url', 'limit', 'frequency', 'published', 'editedon', 'name', 'description'],
        paging		: true,
        pageSize	: 7,
        showPerPage	: false,
        sortBy		: 'id',
        plugins		: expander
    });
    
    Narrowcasting.grid.BroadcastFeeds.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.grid.BroadcastFeeds, MODx.grid.Grid, {
    getMenu: function() {
        return [{
		    text	: _('narrowcasting.broadcast_feed_update'),
		    handler	: this.updateFeed,
		    scope	: this
		}, '-', {
		    text	: _('narrowcasting.broadcast_feed_remove'),
		    handler	: this.removeFeed,
		    scope	: this
		}];
    },
    createFeed: function(btn, e) {
        if (this.createFeedWindow) {
	        this.createFeedWindow.destroy();
        }
        
        this.createFeedWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'narrowcasting-window-broadcast-feed-create',
	        record		: {
		        broadcast_id	: this.config.record.id
		    },
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	        }
        });

		this.createFeedWindow.setValues({
	        broadcast_id	: this.config.record.id
	    });
        this.createFeedWindow.show(e.target);
    },
    updateFeed: function(btn, e) {
        if (this.updateFeedWindow) {
	        this.updateFeedWindow.destroy();
        }
        
        this.updateFeedWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'narrowcasting-window-broadcast-feed-update',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	        }
        });
        
        this.updateFeedWindow.setValues(this.menu.record);
        this.updateFeedWindow.show(e.target);
    },
    removeFeed: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.broadcast_feed_remove'),
        	text		: _('narrowcasting.broadcast_feed_remove_confirm'),
        	url			: Narrowcasting.config.connector_url,
        	params		: {
            	action		: 'mgr/broadcasts/feeds/remove',
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
    renderUrl: function(d, c, e) {
	    return String.format('<a href="{0}" target="_blank" title="{1}">{2}</a>', d, d, d);
    },
    renderBoolean: function(d, c) {
    	c.css = 1 == parseInt(d) || d ? 'green' : 'red';
    	
    	return 1 == parseInt(d) || d ? _('yes') : _('no');
	}
});

Ext.reg('narrowcasting-grid-broadcast-feeds', Narrowcasting.grid.BroadcastFeeds);

Narrowcasting.window.CreateBroadcastFeed = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.broadcast_feed_create'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/broadcasts/feeds/create'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'broadcast_id'
        }, {
        	layout		: 'column',
        	border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
        	items		: [{
	        	columnWidth	: .7,
				items 		: [{
		        	xtype		: 'textfield',
		        	fieldLabel	: _('narrowcasting.label_feed_key'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_key_desc'),
		        	name		: 'key',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_feed_key_desc'),
		            cls			: 'desc-under'
		        }]
	        }, {
		        columnWidth	: .3,
		        style		: 'margin-right: 0;',
				items 		: [{
			        xtype		: 'checkbox',
		            fieldLabel	: '&nbsp;',
		            description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_published_desc'),
		            name		: 'published',
		            inputValue	: 1,
		            boxLabel	: _('narrowcasting.label_feed_published'),
		            checked		: true
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_feed_published_desc'),
		            cls			: 'desc-under'
		        }]
			}]
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_feed_url'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_url_desc'),
        	name		: 'url',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_feed_url_desc'),
            cls			: 'desc-under'
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
		        	xtype		: 'numberfield',
		        	fieldLabel	: _('narrowcasting.label_feed_limit'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_limit_desc'),
		        	name		: 'limit',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_feed_limit_desc'),
		            cls			: 'desc-under'
		        }]
	        }, {
		        columnWidth	: .5,
		        style		: 'margin-right: 0;',
				items 		: [{
		        	xtype		: 'numberfield',
		        	fieldLabel	: _('narrowcasting.label_feed_frequency'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_frequency_desc'),
		        	name		: 'frequency',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_feed_frequency_desc'),
		            cls			: 'desc-under'
		        }]
			}]
        }]
    });
    
    Narrowcasting.window.CreateBroadcastFeed.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.CreateBroadcastFeed, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-feed-create', Narrowcasting.window.CreateBroadcastFeed);

Narrowcasting.window.UpdateBroadcastFeed = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.broadcast_feed_update'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/broadcasts/feeds/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'hidden',
            name		: 'broadcast_id'
        }, {
        	layout		: 'column',
        	border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
        	items		: [{
	        	columnWidth	: .7,
				items 		: [{
		        	xtype		: 'textfield',
		        	fieldLabel	: _('narrowcasting.label_feed_key'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_key_desc'),
		        	name		: 'key',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_feed_key_desc'),
		            cls			: 'desc-under'
		        }]
	        }, {
		        columnWidth	: .3,
		        style		: 'margin-right: 0;',
				items 		: [{
			        xtype		: 'checkbox',
		            fieldLabel	: '&nbsp;',
		            description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_published_desc'),
		            name		: 'published',
		            inputValue	: 1,
		            boxLabel	: _('narrowcasting.label_feed_published')
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_slide_feed_desc'),
		            cls			: 'desc-under'
		        }]
			}]
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_feed_url'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_url_desc'),
        	name		: 'url',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_feed_url_desc'),
            cls			: 'desc-under'
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
		        	xtype		: 'numberfield',
		        	fieldLabel	: _('narrowcasting.label_feed_limit'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_limit_desc'),
		        	name		: 'limit',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_feed_limit_desc'),
		            cls			: 'desc-under'
		        }]
	        }, {
		        columnWidth	: .5,
		        style		: 'margin-right: 0;',
				items 		: [{
		        	xtype		: 'numberfield',
		        	fieldLabel	: _('narrowcasting.label_feed_frequency'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_feed_frequency_desc'),
		        	name		: 'frequency',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_feed_frequency_desc'),
		            cls			: 'desc-under'
		        }]
			}]
        }]
    });
    
    Narrowcasting.window.UpdateBroadcastFeed.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.UpdateBroadcastFeed, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-feed-update', Narrowcasting.window.UpdateBroadcastFeed);