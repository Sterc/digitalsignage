DigitalSignage.grid.SlideTypes = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('digitalsignage.slide_type_create'),
        cls         : 'primary-button',
        handler     : this.createSlideType,
        scope       : this
    }, '->', {
        xtype       : 'textfield',
        name        : 'digitalsignage-filter-slide-types-search',
        id          : 'digitalsignage-filter-slide-types-search',
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
        id          : 'digitalsignage-filter-slide-types-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];
    
    var expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template('<p class="desc">{description_formatted}</p>')
    });

    var columns = new Ext.grid.ColumnModel({
        columns     : [expander, {
            header      : _('digitalsignage.label_slide_type_key'),
            dataIndex   : 'key',
            sortable    : true,
            editable    : false,
            width       : 125,
            fixed       : true,
            renderer    : this.renderKey
        }, {
            header      : _('digitalsignage.label_slide_type_name'),
            dataIndex   : 'name_formatted',
            sortable    : true,
            editable    : false,
            width       : 250
        }, {
            header      : _('digitalsignage.label_slide_type_data'),
            dataIndex   : 'data',
            sortable    : true,
            editable    : false,
            width       : 125,
            fixed       : true,
            renderer    : this.renderData
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
        id          : 'digitalsignage-grid-slide-types',
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/types/getlist'
        },
        fields      : ['id', 'key', 'name', 'description', 'icon', 'time', 'data', 'editedon', 'name_formatted', 'description_formatted'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy      : 'key',
        primaryKey  : 'key',
        plugins     : expander
    });

    DigitalSignage.grid.SlideTypes.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.grid.SlideTypes, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.query = '';

        Ext.getCmp('digitalsignage-filter-slide-types-search').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
            text    : '<i class="x-menu-item-icon icon icon-edit"></i>' + _('digitalsignage.slide_type_update'),
            handler : this.updateSlideType,
            scope   : this
        }, {
            text    : '<i class="x-menu-item-icon icon icon-copy"></i>' + _('digitalsignage.slide_type_duplicate'),
            handler : this.duplicateSlideType,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-edit"></i>' + _('digitalsignage.slide_type_data'),
            handler : this.slideTypeData,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('digitalsignage.slide_type_remove'),
            handler : this.removeSlideType,
            scope   : this
        }];
    },
    createSlideType: function(btn, e) {
        if (this.createSlideTypeWindow) {
            this.createSlideTypeWindow.destroy();
        }

        this.createSlideTypeWindow = MODx.load({
            xtype       : 'digitalsignage-window-slide-type-create',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });

        this.createSlideTypeWindow.show(e.target);
    },
    updateSlideType: function(btn, e) {
        if (this.updateSlideTypeWindow) {
            this.updateSlideTypeWindow.destroy();
        }

        this.updateSlideTypeWindow = MODx.load({
            xtype       : 'digitalsignage-window-slide-type-update',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });

        this.updateSlideTypeWindow.setValues(this.menu.record);
        this.updateSlideTypeWindow.show(e.target);
    },
    removeSlideType: function() {
        MODx.msg.confirm({
            title       : _('digitalsignage.slide_type_remove'),
            text        : _('digitalsignage.slide_type_remove_confirm'),
            url         : DigitalSignage.config.connector_url,
            params      : {
                action      : 'mgr/slides/types/remove',
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
    duplicateSlideType: function(btn, e) {
        if (this.duplicateSlideTypeWindow) {
            this.duplicateSlideTypeWindow.destroy();
        }

        var record = Ext.applyIf({
            name    : _('digitalsignage.slide_type_name_duplicate', {
                name    : this.menu.record.name
            })
        }, this.menu.record);

        this.duplicateSlideTypeWindow = MODx.load({
            xtype       : 'digitalsignage-window-slide-type-duplicate',
            record      : record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });

        this.duplicateSlideTypeWindow.setValues(record);
        this.duplicateSlideTypeWindow.show(e.target);
    },
    slideTypeData: function(btn, e) {
        if (this.slideTypeDataWindow) {
            this.slideTypeDataWindow.destroy();
        }

        this.slideTypeDataWindow = MODx.load({
            xtype       : 'digitalsignage-window-slide-type-data',
            record      : this.menu.record,
            closeAction : 'close',
            buttons     : [{
                text        : _('ok'),
                cls         : 'primary-button',
                handler	    : function() {
                    this.refresh();

                    this.slideTypeDataWindow.close();
                },
                scope       : this
            }]
        });

        this.slideTypeDataWindow.show(e.target);
    },
    renderKey: function(d, c, e) {
        return String.format('<i class="icon icon-slide-type icon-{0}"></i> {1}', e.json.icon, d);
    },
    renderData: function(d, c) {
        return Object.keys(d).length;
    },
    renderBoolean: function(d, c) {
        c.css = parseInt(d) === 1 ? 'green' : 'red';

        return parseInt(d) === 1 ? _('yes') : _('no');
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('digitalsignage-grid-slide-types', DigitalSignage.grid.SlideTypes);

DigitalSignage.window.CreateSlideType = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.slide_type_create'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/types/create'
        },
        fields      : [{
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_key'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_key_desc'),
            name        : 'key',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_key_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_name'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_name_desc'),
            name        : 'name',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_name_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_slide_type_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_description_desc'),
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
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_slide_type_icon'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_icon_desc'),
                    name        : 'icon',
                    anchor      : '100%'
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_type_icon_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .5,
                items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_slide_type_time'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_time_desc'),
                    name        : 'time',
                    anchor      : '100%',
                    allowBlank  : false,
                    listeners   : {
                        'change'    : {
                            fn          : this.getSeconds,
                            scope       : this
                        }
                    }
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_type_time_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }]
    });

    DigitalSignage.window.CreateSlideType.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.CreateSlideType, MODx.Window, {
    getSeconds: function(tf, nv, ov) {
        var time = tf.getValue();

        if (-1 !== time.search(':')) {
            var res = time.split(':');

            if ('0' == res[0].substr(0, 1)) {
                var minutes = parseInt(res[0].substr(1));
            } else {
                var minutes = parseInt(res[0]);
            }

            if ('0' == res[1].substr(0, 1)) {
                var seconds = parseInt(res[1].substr(1));
            } else {
                var seconds = parseInt(res[1]);
            }

            tf.setValue((minutes * 60) + seconds);
        }
    }
});

Ext.reg('digitalsignage-window-slide-type-create', DigitalSignage.window.CreateSlideType);

DigitalSignage.window.UpdateSlideType = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.slide_type_update'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/types/update'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_key'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_key_desc'),
            name        : 'key',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_key_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_name'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_name_desc'),
            name        : 'name',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_name_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_slide_type_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_description_desc'),
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
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_slide_type_icon'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_icon_desc'),
                    name        : 'icon',
                    anchor      : '100%'
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_type_icon_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .5,
                items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_slide_type_time'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_time_desc'),
                    name        : 'time',
                    anchor      : '100%',
                    allowBlank  : false,
                    listeners   : {
                        'change'    : {
                            fn          : this.getSeconds,
                            scope       : this
                        }
                    }
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_type_time_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }]
    });

    DigitalSignage.window.UpdateSlideType.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.UpdateSlideType, MODx.Window, {
    getSeconds: function(tf, nv, ov) {
        var time = tf.getValue();

        if (-1 !== time.search(':')) {
            var res = time.split(':');

            if ('0' == res[0].substr(0, 1)) {
                var minutes = parseInt(res[0].substr(1));
            } else {
                var minutes = parseInt(res[0]);
            }

            if ('0' == res[1].substr(0, 1)) {
                var seconds = parseInt(res[1].substr(1));
            } else {
                var seconds = parseInt(res[1]);
            }

            tf.setValue((minutes * 60) + seconds);
        }
    }
});

Ext.reg('digitalsignage-window-slide-type-update', DigitalSignage.window.UpdateSlideType);

DigitalSignage.window.DuplicateSlideType = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.slide_type_duplicate'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/types/duplicate'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_name'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_name_desc'),
            name        : 'name',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_name_desc'),
            cls         : 'desc-under'
        }]
    });

    DigitalSignage.window.DuplicateSlideType.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.DuplicateSlideType, MODx.Window);

Ext.reg('digitalsignage-window-slide-type-duplicate', DigitalSignage.window.DuplicateSlideType);

DigitalSignage.window.SlideTypeData = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        width       : 700,
        autoHeight  : true,
        title       : _('digitalsignage.slide_type_data'),
        fields      : [{
            xtype           : 'digitalsignage-grid-slide-types-data',
            record          : config.record,
            preventRender   : true
        }]
    });

    DigitalSignage.window.SlideTypeData.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.SlideTypeData, MODx.Window);

Ext.reg('digitalsignage-window-slide-type-data', DigitalSignage.window.SlideTypeData);