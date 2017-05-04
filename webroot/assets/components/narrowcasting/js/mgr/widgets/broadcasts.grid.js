Narrowcasting.grid.Broadcasts = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('narrowcasting.broadcast_create'),
        cls			:'primary-button',
        handler		: this.createBroadcast,
        scope		: this
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
            width		: 350,
            fixed 		: true,
            renderer	: this.renderPlayers
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
        id			: 'narrowcasting-grid-broadcasts',
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
        	action		: 'mgr/broadcasts/getlist'
        },
        fields		: ['id', 'resource_id', 'name', 'name_formatted', 'description', 'template', 'players', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id'
    });
    
    Narrowcasting.grid.Broadcasts.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.grid.Broadcasts, MODx.grid.Grid, {
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
	        text	: _('narrowcasting.broadcast_update'),
	        handler	: this.updateBroadcast,
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
    removeBroadcast: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.broadcast_remove'),
        	text		: _('narrowcasting.broadcast_remove_confirm'),
        	url			: Narrowcasting.config.connector_url,
        	params		: {
            	action		: 'mgr/broadcasts/remove',
            	id			: this.menu.record.id,
            	resource_id : this.menu.record.resource_id
            },
            listeners	: {
            	'success'	: {
            		fn			: this.refresh,
		        	scope		: this
            	}
            }
    	});
    },
    renderPlayers: function(d, c, e) {
	    var players = new Array();
	    
	    for (var i = 0; i < e.data.players.length; i++) {
		    var player = e.data.players[i];
		    
		    players.push(String.format('<span class="icon icon-circle {0}"></span> {1}', 1 == parseInt(player.online) || player.online ? 'green' : 'red', player.name));
	    }

    	return players.join(', ');
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
        }]
    });
    
    Narrowcasting.window.UpdateBroadcast.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.UpdateBroadcast, MODx.Window);

Ext.reg('narrowcasting-window-broadcast-update', Narrowcasting.window.UpdateBroadcast);

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
            action		: 'mgr/broadcasts/gettemplates'
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