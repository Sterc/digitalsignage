DigitalSignage.grid.BroadcastFeeds = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('digitalsignage.broadcast_feed_create'),
        cls         : 'primary-button',
        handler     : this.createFeed,
        scope       : this
    }];

    var expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template('<p class="desc">{description}</p>')
    });

    var columns = new Ext.grid.ColumnModel({
        columns     : [expander, {
            header      : _('digitalsignage.label_feed_name'),
            dataIndex   : 'name',
            sortable    : true,
            editable    : false,
            width       : 125,
            fixed       : true
        }, {
            header      : _('digitalsignage.label_feed_url'),
            dataIndex   : 'url',
            sortable    : true,
            editable    : false,
            width       : 250,
            renderer    : this.renderUrl
        }, {
            header      : _('digitalsignage.label_feed_published'),
            dataIndex   : 'published',
            sortable    : true,
            editable    : true,
            width       : 100,
            fixed       : true,
            renderer    : this.renderBoolean
        }]
    });
    
    Ext.applyIf(config, {
        cm          : columns,
        id          : 'digitalsignage-grid-broadcast-feeds',
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/feeds/getlist',
            broadcast_id : config.record.id
        },
        fields      : ['id', 'broadcast_id', 'key', 'url', 'time', 'frequency', 'published', 'editedon', 'name', 'description'],
        paging      : true,
        pageSize    : 7,
        showPerPage : false,
        sortBy      : 'id',
        plugins     : expander
    });
    
    DigitalSignage.grid.BroadcastFeeds.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.grid.BroadcastFeeds, MODx.grid.Grid, {
    getMenu: function() {
        return [{
            text    : '<i class="x-menu-item-icon icon icon-edit"></i>' + _('digitalsignage.broadcast_feed_update'),
            handler : this.updateFeed,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('digitalsignage.broadcast_feed_remove'),
            handler : this.removeFeed,
            scope   : this
        }];
    },
    createFeed: function(btn, e) {
        if (this.createFeedWindow) {
            this.createFeedWindow.destroy();
        }

        var record = {
            broadcast_id : this.config.record.id
        };

        this.createFeedWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-broadcast-feed-create',
            record      : record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });

        this.createFeedWindow.setValues(record);
        this.createFeedWindow.show(e.target);
    },
    updateFeed: function(btn, e) {
        if (this.updateFeedWindow) {
            this.updateFeedWindow.destroy();
        }

        var record = Ext.apply(this.menu.record, {
            broadcast_id : this.config.record.id
        });

        this.updateFeedWindow = MODx.load({
            modal       : true,
            xtype       : 'digitalsignage-window-broadcast-feed-update',
            record      : record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });

        this.updateFeedWindow.setValues(record);
        this.updateFeedWindow.show(e.target);
    },
    removeFeed: function() {
        MODx.msg.confirm({
            title       : _('digitalsignage.broadcast_feed_remove'),
            text        : _('digitalsignage.broadcast_feed_remove_confirm'),
            url         : DigitalSignage.config.connector_url,
            params      : {
                action      : 'mgr/broadcasts/feeds/remove',
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
    renderUrl: function(d, c, e) {
        return String.format('<a href="{0}" target="_blank" title="{1}">{2}</a>', d, d, d);
    },
    renderBoolean: function(d, c) {
        c.css = 1 == parseInt(d) || d ? 'green' : 'red';

        return 1 == parseInt(d) || d ? _('yes') : _('no');
    }
});

Ext.reg('digitalsignage-grid-broadcast-feeds', DigitalSignage.grid.BroadcastFeeds);

DigitalSignage.window.CreateBroadcastFeed = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.broadcast_feed_create'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/feeds/create'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'broadcast_id'
        }, {
            layout      : 'column',
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .7,
                items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_feed_key'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_key_desc'),
                    name        : 'key',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_feed_key_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .3,
                items       : [{
                    xtype       : 'checkbox',
                    fieldLabel  : '&nbsp;',
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_published_desc'),
                    name        : 'published',
                    inputValue  : 1,
                    boxLabel    : _('digitalsignage.label_feed_published'),
                    checked     : true
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_feed_published_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_feed_url'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_url_desc'),
            name        : 'url',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_feed_url_desc'),
            cls         : 'desc-under'
        }, {
            layout      : 'column',
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .5,
                items       : [{
                    xtype       : 'numberfield',
                    fieldLabel  : _('digitalsignage.label_feed_time'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_time_desc'),
                    name        : 'time',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_feed_time_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .5,
                items       : [{
                    xtype       : 'numberfield',
                    fieldLabel  : _('digitalsignage.label_feed_frequency'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_frequency_desc'),
                    name        : 'frequency',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_feed_frequency_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }]
    });

    DigitalSignage.window.CreateBroadcastFeed.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.CreateBroadcastFeed, MODx.Window);

Ext.reg('digitalsignage-window-broadcast-feed-create', DigitalSignage.window.CreateBroadcastFeed);

DigitalSignage.window.UpdateBroadcastFeed = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.broadcast_feed_update'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/broadcasts/feeds/update'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'hidden',
            name        : 'broadcast_id'
        }, {
            layout      : 'column',
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .7,
                items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_feed_key'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_key_desc'),
                    name        : 'key',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_feed_key_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .3,
                items       : [{
                    xtype       : 'checkbox',
                    fieldLabel  : '&nbsp;',
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_published_desc'),
                    name        : 'published',
                    inputValue  : 1,
                    boxLabel    : _('digitalsignage.label_feed_published')
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_feed_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_feed_url'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_url_desc'),
            name        : 'url',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_feed_url_desc'),
            cls         : 'desc-under'
        }, {
            layout      : 'column',
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .5,
                items       : [{
                    xtype       : 'numberfield',
                    fieldLabel  : _('digitalsignage.label_feed_time'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_time_desc'),
                    name        : 'time',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_feed_time_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .5,
                items       : [{
                    xtype       : 'numberfield',
                    fieldLabel  : _('digitalsignage.label_feed_frequency'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_feed_frequency_desc'),
                    name        : 'frequency',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_feed_frequency_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }]
    });
    
    DigitalSignage.window.UpdateBroadcastFeed.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.UpdateBroadcastFeed, MODx.Window);

Ext.reg('digitalsignage-window-broadcast-feed-update', DigitalSignage.window.UpdateBroadcastFeed);