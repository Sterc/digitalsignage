Narrowcasting.grid.BroadcastPlayers = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('narrowcasting.broadcast_player_create'),
        cls			:'primary-button',
        handler		: this.createPlayer,
        scope		: this
    }, {
		xtype		: 'checkbox',
		name		: 'narrowcasting-refresh-bloardcast-players',
        id			: 'narrowcasting-refresh-bloardcast-players',
		boxLabel	: _('narrowcasting.auto_refresh_grid'),
		listeners	: {
			'check'		: {
				fn 			: this.autoRefresh,
				scope 		: this	
			}
		}
	}];

    columns = new Ext.grid.ColumnModel({
        columns: [{
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
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'narrowcasting-grid-broadcast-players',
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
        	action		: 'mgr/broadcasts/players/getlist',
        	broadcast 	: config.record.id
        },
        fields		: ['id', 'key', 'name', 'online', 'last_online', 'last_broadcast_id', 'editedon'],
        paging		: true,
        pageSize	: 10,
        showPerPage	: false,
        sortBy		: 'id',
        refresher	: {
	        timer 		: null,
	        duration	: 30,
	        count 		: 0
        }
    });
    
    Narrowcasting.grid.BroadcastPlayers.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.grid.BroadcastPlayers, MODx.grid.Grid, {
	autoRefresh: function(tf, nv) {
		if (nv) {
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
    getMenu: function() {
        return [{
		    text	: _('narrowcasting.broadcast_player_remove'),
		    handler	: this.removePlayer,
		    scope	: this
		}];
    },
    createPlayer: function(btn, e) {
        if (this.createPlayerWindow) {
	        this.createPlayerWindow.destroy();
        }
        
        this.createPlayerWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'narrowcasting-window-broadcast-player-create',
	        broadcast	: this.config.record.id,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	         }
        });

        this.createPlayerWindow.show(e.target);
    },
    removePlayer: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.broadcast_player_remove'),
        	text		: _('narrowcasting.broadcast_player_remove_confirm'),
        	url			: Narrowcasting.config.connector_url,
        	params		: {
            	action		: 'mgr/broadcasts/players/remove',
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
    renderKey: function(d, c, e) {
    	return String.format('<span class="icon icon-circle {0}"></span> {1}', 1 == parseInt(e.data.online) || e.data.online ? 'green' : 'red', d);
    }
});

Ext.reg('narrowcasting-grid-broadcast-players', Narrowcasting.grid.BroadcastPlayers);

Narrowcasting.window.CreateBroadcastPlayer = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.broadcast_player_create'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/broadcasts/players/create'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'broadcast_id',
            value 		: config.broadcast
        }, {
        	xtype		: 'narrowcasting-combo-players',
        	fieldLabel	: _('narrowcasting.label_broadcast_player'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_broadcast_player_desc'),
        	name		: 'player',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_broadcast_player_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Narrowcasting.window.CreateBroadcastPlayer.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.CreateBroadcastPlayer, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-player-create', Narrowcasting.window.CreateBroadcastPlayer);