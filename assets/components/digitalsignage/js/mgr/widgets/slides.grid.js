DigitalSignage.grid.Slides = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('digitalsignage.slide_create'),
        cls         : 'primary-button',
        handler     : this.createSlide,
        scope       : this
    }, '->', {
        xtype       : 'digitalsignage-combo-broadcasts',
        name        : 'digitalsignage-filter-slides-broadcasts',
        id          : 'digitalsignage-filter-slides-broadcasts',
        emptyText   : _('digitalsignage.filter_broadcast'),
        listeners   : {
            'select'    : {
                fn          : this.filterBroadcast,
                scope       : this
            }
        },
        width       : 200
    }, '-', {
        xtype       : 'textfield',
        name        : 'digitalsignage-filter-slides-search',
        id          : 'digitalsignage-filter-slides-search',
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
        id          : 'digitalsignage-clear-slides-filter',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];

    var expander = new Ext.grid.RowExpander({
        getRowClass : function(record, rowIndex, p, ds) {
            return 1 === parseInt(record.json.protected) ? ' grid-row-inactive' : '';
        }
    });

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('digitalsignage.label_slide_name'),
            dataIndex   : 'name',
            sortable    : true,
            editable    : false,
            width       : 250
        }, {
            header      : _('digitalsignage.label_slide_type'),
            dataIndex   : 'type_formatted',
            sortable    : true,
            editable    : false,
            width       : 200,
            fixed       : true,
            renderer    : this.renderType
        }, {
            header      : _('digitalsignage.label_slide_published'),
            dataIndex   : 'published',
            sortable    : true,
            editable    : true,
            width       : 100,
            fixed       : true,
            renderer    : this.renderBoolean
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
        id          : 'digitalsignage-grid-slides',
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/getlist'
        },
        fields      : ['id', 'type', 'name', 'icon', 'time', 'protected', 'data', 'published', 'editedon', 'type_formatted', 'broadcasts', 'pub_date', 'unpub_date'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy      : 'id',
        plugins     : expander,
        refreshGrid : []
    });

    DigitalSignage.grid.Slides.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.grid.Slides, MODx.grid.Grid, {
    filterBroadcast: function(tf, nv, ov) {
        this.getStore().baseParams.broadcast_id = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.broadcast_id = '';
        this.getStore().baseParams.query = '';

        Ext.getCmp('digitalsignage-filter-slides-broadcasts').reset();
        Ext.getCmp('digitalsignage-filter-slides-search').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
            text    : '<i class="x-menu-item-icon icon icon-edit"></i>' + _('digitalsignage.slide_update'),
            handler : this.updateSlide,
            scope   : this
        }, {
            text    : '<i class="x-menu-item-icon icon icon-eye"></i>' + _('digitalsignage.slide_preview'),
            handler : this.previewSlide,
            scope   : this
        }, {
            text    : '<i class="x-menu-item-icon icon icon-copy"></i>' + _('digitalsignage.slide_duplicate'),
            handler : this.duplicateSlide,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('digitalsignage.slide_remove'),
            handler : this.removeSlide,
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
    createSlide: function(btn, e) {
        if (this.createSlideWindow) {
            this.createSlideWindow.destroy();
        }

        this.createSlideWindow = MODx.load({
            xtype       : 'digitalsignage-window-slide-create',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                        this.refreshGrids();
                        this.refresh();
                    },
                scope           : this
                }
            }
        });

        this.createSlideWindow.show(e.target);
    },
    updateSlide: function(btn, e) {
        if (this.updateSlideWindow) {
            this.updateSlideWindow.destroy();
        }

        this.updateSlideWindow = MODx.load({
            xtype       : 'digitalsignage-window-slide-update',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.updateSlideWindow.setValues(this.menu.record);
        this.updateSlideWindow.show(e.target);
    },
    previewSlide: function(btn, e) {
        if (this.previewBroadcastWindow) {
            this.previewBroadcastWindow.destroy();
        }

        this.previewBroadcastWindow = MODx.load({
            xtype       : 'digitalsignage-window-broadcast-preview',
            record      : this.menu.record,
            closeAction : 'close',
            saveBtnText : _('digitalsignage.show_broadcast_preview'),
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                    this.getSelectionModel().clearSelections(true);

                    this.showPreviewBroadcast(data.a.result.object);
                    },
                    scope       : this
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
            modal       : true,
            xtype       : 'digitalsignage-window-broadcast-preview-show',
            record      : record,
            closeAction : 'close'
        });

        this.showPreviewBroadcastWindow.show();
    },
    duplicateSlide: function(btn, e) {
        if (this.duplicateSlideWindow) {
            this.duplicateSlideWindow.destroy();
        }

        var record = Ext.applyIf({
            name    : _('digitalsignage.slide_name_duplicate', {
                name    : this.menu.record.name
            })
        }, this.menu.record);

        this.duplicateSlideWindow = MODx.load({
            xtype       : 'digitalsignage-window-slide-duplicate',
            record      : record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.duplicateSlideWindow.setValues(record);
        this.duplicateSlideWindow.show(e.target);
    },
    removeSlide: function() {
        MODx.msg.confirm({
            title       : _('digitalsignage.slide_remove'),
            text        : _('digitalsignage.slide_remove_confirm'),
            url         : DigitalSignage.config.connector_url,
            params      : {
                action      : 'mgr/slides/remove',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });
    },
    renderType: function(d, c, e) {
        return String.format('<i class="icon icon-slide-type icon-{0}"></i> {1}', e.json.icon, d);
    },
    renderBoolean: function(d, c) {
        c.css = parseInt(d) === 1 ? 'green' : 'red';

        return parseInt(d) === 1 ? _('yes') : _('no');
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return 'вЂ”';
        }

        return a;
    }
});

Ext.reg('digitalsignage-grid-slides', DigitalSignage.grid.Slides);

DigitalSignage.window.CreateSlide = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        width       : 800,
        autoHeight  : true,
        title       : _('digitalsignage.slide_create'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/create'
        },
        fields      : [{
            layout      : 'column',
            border      : false,
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .4,
                items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_slide_name'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_name_desc'),
                    name        : 'name',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_name_desc'),
                    cls         : 'desc-under'
                }, {
                    layout      : 'column',
                    border      : false,
                    defaults    : {
                        layout      : 'form',
                        labelSeparator : ''
                    },
                    items       : [{
                        columnWidth : .6,
                        items       : [{
                            xtype       : 'digitalsignage-combo-slides-types',
                            fieldLabel  : _('digitalsignage.label_slide_type'),
                            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_desc'),
                            name        : 'type',
                            anchor      : '100%',
                            allowBlank  : false,
                            listeners   : {
                                'select'    : {
                                    fn          : this.getTypeFields,
                                    scope       : this
                                },
                                'loaded'    : {
                                    fn          : this.getTypeFields,
                                    scope       : this
                                }
                            }
                        }]
                    }, {
                        columnWidth : .4,
                        items       : [{
                            xtype       : 'checkbox',
                            fieldLabel  : '&nbsp;',
                            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_published_desc'),
                            name        : 'published',
                            inputValue  : 1,
                            boxLabel    : _('digitalsignage.label_slide_published'),
                            checked     : true
                        }, {
                            xtype       : MODx.expandHelp ? 'label' : 'hidden',
                            html        : _('digitalsignage.label_slide_published_desc'),
                            cls         : 'desc-under'
                        }]
                    }]
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_type_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'xdatetime',
                    fieldLabel  : _('digitalsignage.label_pub_date'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_pub_date_desc'),
                    name        : 'pub_date',
                    anchor      : '100%',
                    allowBlank  : true
                }, {
                    xtype       : 'xdatetime',
                    fieldLabel  : _('digitalsignage.label_unpub_date'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_unpub_date_desc'),
                    name        : 'unpub_date',
                    anchor      : '100%',
                    allowBlank  : true
                }, {
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_slide_time'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_time_desc'),
                    name        : 'time',
                    id          : 'digitalsignage-window-slide-create-time',
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
                    html        : _('digitalsignage.label_slide_time_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'digitalsignage-checkbox-broadcasts',
                    fieldLabel  : _('digitalsignage.label_slide_broadcasts'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_broadcasts_desc'),
                    id          : 'digitalsignage-checkbox-broadcasts-create'
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_broadcasts_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'checkbox',
                    hidden      : !DigitalSignage.config.permissions.admin,
                    fieldLabel  : '',
                    boxLabel    : _('digitalsignage.label_slide_protected_desc'),
                    name        : 'protected',
                    anchor      : '100%',
                    inputValue  : 1
                }]
            }, {
                columnWidth : .6,
                items       : [{
                    xtype       : 'container',
                    layout      : 'form',
                    labelSeparator : '',
                    id          : 'digitalsignage-window-slide-create-fields',
                    cls         : 'digitalsignage-slide-options',
                    items       : [{
                        html        : '<i class="icon icon-cog"></i>'
                    }]
                }]
            }]
        }]
    });

    DigitalSignage.window.CreateSlide.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.CreateSlide, MODx.Window, {
    getSeconds: function(tf, nv, ov) {
        var time = tf.getValue();

        if (-1 !== time.search(':')) {
            var res = time.split(':');

            if (res[0].substr(0, 1) === '0' && res[0].length > 1) {
                var minutes = parseInt(res[0].substr(1));
            } else {
                var minutes = parseInt(res[0]);
            }

            if (res[0].substr(0, 1) === '0' && res[1].length > 1) {
                var seconds = parseInt(res[1].substr(1));
            } else {
                var seconds = parseInt(res[1]);
            }

            tf.setValue((minutes * 60) + seconds);
        }
    },
    getTypeFields: function(tf, nv, ov) {
        var record = record = tf.findRecord(tf.valueField, tf.getValue());

        if (record) {
            var type = record.data.key,
                time = Ext.getCmp('digitalsignage-window-slide-create-time'),
                container = Ext.getCmp('digitalsignage-window-slide-create-fields');

            if (time) {
                if (time.getValue() === '' || parseInt(time.getValue()) === 0) {
                    time.setValue(record.data.time);
                }
            }

            if (container) {
                container.removeAll();

                Ext.iterate(record.data.data, function(name, record) {
                    var label   = _('digitalsignage.slide_' + type + '_' + name);
                    var desc    = _('digitalsignage.slide_' + type + '_' + name + '_desc');

                    if (record.label && record.label !== '') {
                        label = record.label;
                    }

                    if (record.description && record.description !== '') {
                        desc = record.description;
                    }

                    switch (record.xtype) {
                        case 'statictextfield':
                            record = Ext.apply(record, {
                                xtype       : 'statictextfield',
                                value       : record.default_value || '',
                                submitValue : true
                            });

                            break;
                        case 'hidden':
                            record = Ext.apply(record, {
                                xtype       : 'hidden',
                                value       : record.default_value || ''
                            });

                            break;
                        case 'datefield':
                            record = Ext.apply(record, {
                                xtype       : 'datefield',
                                format      : MODx.config.manager_date_format,
                                startDay    : parseInt(MODx.config.manager_week_start),
                                value       : record.default_value || ''
                            });

                            break;
                        case 'timefield':
                            record = Ext.apply(record, {
                                xtype       : 'timefield',
                                format      : MODx.config.manager_time_format,
                                offset_time : MODx.config.server_offset_time,
                                value       : record.default_value || ''
                            });

                            break;
                        case 'xdatetime':
                            record = Ext.apply(record, {
                                xtype       : 'xdatetime',
                                dateFormat  : MODx.config.manager_date_format,
                                timeFormat  : MODx.config.manager_time_format,
                                startDay    : parseInt(MODx.config.manager_week_start),
                                offset_time : MODx.config.server_offset_time,
                                value       : record.default_value || ''
                            });

                            break;
                        case 'colorfield':
                            record = Ext.apply(record, {
                                xtype       : 'colorfield',
                                value       : record.default_value || ''
                            });

                            break;
                        case 'radio':
                        case 'checkbox':
                            record = Ext.apply(record, {
                                hideLabel   : true,
                                boxLabel    : label,
                                inputValue  : record.default_value || '',
                                values      : record.values || '',
                            });

                            break;
                        case 'richtext':
                            record = Ext.apply(record, {
                                xtype       : 'textarea',
                                value       : record.default_value || '',
                                cls         : 'digitalsignage-richtext',
                                listeners   : {
                                    'afterrender' : {
                                        fn      : function(event) {
                                            if (DigitalSignage.loadRTE) {
                                                DigitalSignage.loadRTE(event.id);
                                            }
                                        }
                                    }
                                }
                            });

                            break;
                        case 'combo':
                            var results = new Array(),
                                values  = record.values.split('||');

                            for (var i = 0; i < values.length; i++) {
                                results.push(values[i].split('=='));
                            }

                            record = Ext.apply(record, {
                                store       : new Ext.data.ArrayStore({
                                    mode        : 'local',
                                    fields      : ['label', 'value'],
                                    data        : results
                                }),
                                hiddenName  : 'data_' + name,
                                valueField  : 'value',
                                displayField: 'label',
                                mode        : 'local',
                                triggerAction: 'all',
                                value       : record.default_value || ''
                            });

                            break;
                        case 'radiogroup':
                        case 'checkboxgroup':
                            var results = new Array(),
                                values  = record.values.split('||');

                            for (var i = 0; i < values.length; i++) {
                                var item  = values[i].split('==');

                                results.push({
                                    boxLabel    : item[0],
                                    inputValue  : item[1],
                                    name        : 'checkboxgroup' === record.xtype ? ('data_' + name + '[]') : ('data_' + name)
                                });
                            }

                            record = Ext.apply(record, {
                                columns : 1,
                                items   : results,
                                value   : record.default_value || ''
                            });

                            break;
                        case 'digitalsignage-checkbox-group':
                            record = Ext.apply(record, {
                                xtype       : 'panel',
                                fieldLabel  : '',
                            });
                            break;
                        case 'modx-combo-browser':
                            record = Ext.apply(record, {
                                source :  MODx.config['digitalsignage.media_source'] || MODx.config.default_media_source
                            });

                            break;
                        default:
                            record = Ext.apply(record, {
                                value   : record.default_value || ''
                            });

                            break;
                    }

                    container.add(Ext.applyIf(record, {
                        xtype       : 'textarea',
                        fieldLabel  : label,
                        description : MODx.expandHelp ? '' : name,
                        name        : 'data_' + name,
                        anchor      : '100%',
                        allowBlank  : !(record.required && parseInt(record.required))
                    }));

                    if (record.xtype !== 'hidden') {
                        container.add({
                            xtype       : MODx.expandHelp ? 'label' : 'hidden',
                            html        : desc,
                            cls         : 'desc-under'
                        });
                    }
                }, this);

                this.doLayout();
            }
        }
    }
});

Ext.reg('digitalsignage-window-slide-create', DigitalSignage.window.CreateSlide);

DigitalSignage.window.UpdateSlide = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        width       : 800,
        autoHeight  : true,
        title       : _('digitalsignage.slide_update'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/update'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            layout      : 'column',
            border      : false,
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .4,
                items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_slide_name'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_name_desc'),
                    name        : 'name',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_name_desc'),
                    cls         : 'desc-under'
                }, {
                    layout      : 'column',
                    border      : false,
                    defaults    : {
                        layout      : 'form',
                        labelSeparator : ''
                    },
                    items       : [{
                        columnWidth : .6,
                        items       : [{
                            xtype       : 'digitalsignage-combo-slides-types',
                            fieldLabel  : _('digitalsignage.label_slide_type'),
                            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_desc'),
                            name        : 'type',
                            anchor      : '100%',
                            allowBlank  : false,
                            listeners   : {
                                'select'    : {
                                    fn          : this.getTypeFields,
                                    scope       : this
                                },
                                'loaded'    : {
                                    fn          : this.getTypeFields,
                                    scope       : this
                                }
                            }
                        }]
                    }, {
                        columnWidth : .4,
                        items       : [{
                            xtype       : 'checkbox',
                            fieldLabel  : '&nbsp;',
                            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_published_desc'),
                            name        : 'published',
                            inputValue  : 1,
                            boxLabel    : _('digitalsignage.label_slide_published')
                        }, {
                            xtype       : MODx.expandHelp ? 'label' : 'hidden',
                            html        : _('digitalsignage.label_slide_published_desc'),
                            cls         : 'desc-under'
                        }]
                    }]
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_type_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'xdatetime',
                    fieldLabel  : _('digitalsignage.label_pub_date'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_pub_date_desc'),
                    name        : 'pub_date',
                    anchor      : '100%',
                    allowBlank  : true
                }, {
                    xtype       : 'xdatetime',
                    fieldLabel  : _('digitalsignage.label_unpub_date'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_unpub_date_desc'),
                    name        : 'unpub_date',
                    anchor      : '100%',
                    allowBlank  : true
                }, {
                    xtype       : 'textfield',
                    fieldLabel  : _('digitalsignage.label_slide_time'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_time_desc'),
                    name        : 'time',
                    id          : 'digitalsignage-window-slide-update-time',
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
                    html        : _('digitalsignage.label_slide_time_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'digitalsignage-checkbox-broadcasts',
                    fieldLabel  : _('digitalsignage.label_slide_broadcasts'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_broadcasts_desc'),
                    id          : 'digitalsignage-checkbox-broadcasts-update',
                    value       : config.record.broadcasts
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('digitalsignage.label_slide_broadcasts_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'checkbox',
                    hidden      : !DigitalSignage.config.permissions.admin,
                    fieldLabel  : '',
                    boxLabel    : _('digitalsignage.label_slide_protected_desc'),
                    name        : 'protected',
                    anchor      : '100%',
                    inputValue  : 1
                }]
            }, {
                columnWidth : .6,
                items       : [{
                    xtype       : 'container',
                    layout      : 'form',
                    labelSeparator : '',
                    id          : 'digitalsignage-window-slide-update-fields',
                    cls         : 'digitalsignage-slide-options',
                    items       : [{
                        html        : '<i class="icon icon-cog"></i>'
                    }]
                }]
            }]
        }]
    });

    DigitalSignage.window.UpdateSlide.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.UpdateSlide, MODx.Window, {
    getSeconds: function(tf, nv, ov) {
        var time = tf.getValue();

        if (-1 !== time.search(':')) {
            var res = time.split(':');

            if (res[0].substr(0, 1) === '0' && res[0].length > 1) {
                var minutes = parseInt(res[0].substr(1));
            } else {
                var minutes = parseInt(res[0]);
            }

            if (res[1].substr(0, 1) === '0' && res[1].length > 1) {
                var seconds = parseInt(res[1].substr(1));
            } else {
                var seconds = parseInt(res[1]);
            }

            tf.setValue((minutes * 60) + seconds);
        }
    },
    getTypeFields: function(tf, nv, ov) {
        var record = tf.findRecord(tf.valueField, tf.getValue());

        if (record) {
            var type = record.data.key,
                time = Ext.getCmp('digitalsignage-window-slide-update-time'),
                container = Ext.getCmp('digitalsignage-window-slide-update-fields');

            if (time) {
                if (time.getValue() === '' || parseInt(time.getValue()) === 0) {
                    time.setValue(record.data.time);
                }
            }

            if (container) {
                container.removeAll();

                Ext.iterate(record.data.data, function(name, record) {
                    var label   = _('digitalsignage.slide_' + type + '_' + name);
                    var desc    = _('digitalsignage.slide_' + type + '_' + name + '_desc');

                    if (record.label && record.label !== '') {
                        label = record.label;
                    }

                    if (record.description && record.description !== '') {
                        desc = record.description;
                    }

                    switch (record.xtype) {
                        case 'statictextfield':
                            record = Ext.apply(record, {
                                xtype       : 'statictextfield',
                                value       : record.default_value || '',
                                submitValue : true
                            });

                            break;
                        case 'hidden':
                            record = Ext.apply(record, {
                                xtype       : 'hidden',
                                value       : record.default_value || ''
                            });

                            break;
                        case 'datefield':
                            record = Ext.apply(record, {
                                xtype       : 'datefield',
                                format      : MODx.config.manager_date_format,
                                startDay    : parseInt(MODx.config.manager_week_start),
                                value       : this.config.record.data[name] || ''
                            });

                            break;
                        case 'timefield':
                            record = Ext.apply(record, {
                                xtype       : 'timefield',
                                format      : MODx.config.manager_time_format,
                                offset_time : MODx.config.server_offset_time,
                                value       : this.config.record.data[name] || ''
                            });

                            break;
                        case 'xdatetime':
                            record = Ext.apply(record, {
                                xtype       : 'xdatetime',
                                dateFormat  : MODx.config.manager_date_format,
                                timeFormat  : MODx.config.manager_time_format,
                                startDay    : parseInt(MODx.config.manager_week_start),
                                offset_time : MODx.config.server_offset_time,
                                value       : this.config.record.data[name] || ''
                            });

                            break;
                        case 'colorfield':
                            record = Ext.apply(record, {
                                xtype       : 'colorfield',
                                value       : this.config.record.data[name] || ''
                            });

                            break;
                        case 'radio':
                        case 'checkbox':
                            record = Ext.apply(record, {
                                hideLabel   : true,
                                boxLabel    : label,
                                inputValue  : record.default_value,
                                checked     : this.config.record.data[name] === record.default_value
                            });

                            break;
                        case 'richtext':
                            record = Ext.apply(record, {
                                xtype       : 'textarea',
                                value       : this.config.record.data[name],
                                cls         : 'digitalsignage-richtext',
                                listeners   : {
                                    'afterrender' : {
                                        fn      : function(event) {
                                            if (DigitalSignage.loadRTE) {
                                                DigitalSignage.loadRTE(event.id);
                                            }
                                        }
                                    }
                                }
                            });

                            break;
                        case 'combo':
                            var results = new Array(),
                                values  = record.values.split('||');

                            for (var i = 0; i < values.length; i++) {
                                results.push(values[i].split('=='));
                            }

                            record = Ext.apply(record, {
                                store       : new Ext.data.ArrayStore({
                                    mode        : 'local',
                                    fields      : ['label', 'value'],
                                    data        : results
                                }),
                                hiddenName  : 'data_' + name,
                                valueField  : 'value',
                                displayField: 'label',
                                mode        : 'local',
                                triggerAction: 'all',
                                value       : this.config.record.data[name] || ''
                            });

                            break;
                        case 'radiogroup':
                        case 'checkboxgroup':
                            var results = new Array(),
                                values  = record.values.split('||');

                            for (var i = 0; i < values.length; i++) {
                                var item  = values[i].split('==');

                                results.push({
                                    boxLabel    : item[0],
                                    inputValue  : item[1],
                                    name        : 'checkboxgroup' === record.xtype ? ('data_' + name + '[]') : ('date_' + name)
                                });
                            }

                            record = Ext.apply(record, {
                                columns : 1,
                                items   : results,
                                value   : this.config.record.data[name] || ''
                            });

                            break;
                        case 'digitalsignage-checkbox-group':
                            if (this.config.record.data[record.values].length) {
                                MODx.Ajax.request({
                                    url: DigitalSignage.config.connector_url,
                                    params: {
                                        action: 'mgr/slides/checkboxgroup/getlist',
                                        url: this.config.record.data[record.values]
                                    },
                                    listeners: {
                                        'success':{fn:function(r) {

                                            var results = new Array();
                                            if (typeof(this.config.record.data[name]) == 'undefined') {
                                                this.config.record.data[name] = [];
                                            }
                                            var city = '';
                                            for (var i = 0; i < r.results[0].length; i++) {
                                                city = r.results[0][i].address_city;
                                                results.push({
                                                    boxLabel    : r.results[0][i].pagetitle + (city ? ', ' + city : ''),
                                                    inputValue  : r.results[0][i].id,
                                                    name        : 'data_' + name + '[]',
                                                    xtype       : 'checkbox',
                                                    checked     : (-1 == this.config.record.data[name].indexOf(parseInt(r.results[0][i].id)))
                                                });
                                            }
                
                                            record = Ext.apply(record, {
                                                xtype   : 'panel',
                                                items: [{
                                                    xtype       : 'textfield',
                                                    name        : 'digitalsignage-checkboxgroup-items-search',
                                                    id          : 'digitalsignage-checkboxgroup-items-search',
                                                    emptyText   : _('search') + '...',
                                                    anchor      : '100%',
                                                    style       : {
                                                        width           : 'calc(100% - 12px)',
                                                        'margin-bottom' : '-1px',
                                                    },
                                                    enableKeyEvents : true,
                                                    listeners   : {
                                                        'keyup'    : {
                                                            fn          : function(e) {
                                                                var search_query = e.getValue();
                                                                var checkboxgroup = Ext.getCmp('digitalsignage-checkboxgroup-items');
                                                                for (var i = 0; i < checkboxgroup.items.items.length; i++) {
                                                                    if (search_query) {
                                                                        if (-1 == checkboxgroup.items.items[i].boxLabel.toLowerCase().indexOf(search_query.toLowerCase())) {
                                                                            checkboxgroup.items.items[i].hide();
                                                                        } else {
                                                                            checkboxgroup.items.items[i].show();
                                                                        }
                                                                    } else {
                                                                        checkboxgroup.items.items[i].show();
                                                                    }
                                                                }
                                                            },
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
                                                    columns : 1,
                                                    xtype   : 'panel',
                                                    cls     : 'digitalsignage-checkboxgroup-fixed x-form-item',
                                                    style   : {
                                                        height  : '200px'
                                                    },
                                                    items   : [{
                                                        xtype       : 'checkboxgroup',
                                                        id          : 'digitalsignage-checkboxgroup-items',
                                                        hideLabel   : true,
                                                        columns     : 1,
                                                        style       : {
                                                            'max-height': '200px'
                                                        },
                                                        items       : results
                                                    }],
                                                }]
                                            });
                                        },scope:this},
                                        'failure':{fn:function(r) {
                                            console.log('Ajax error');
                                        },scope:this},
                                    }
                                });
                            }
                            break;
                        case 'modx-combo-browser':
                            record = Ext.apply(record, {
                                source  :  MODx.config['digitalsignage.media_source'] || MODx.config.default_media_source
                            });

                            break;
                        default:
                            record = Ext.apply(record, {
                                value : this.config.record.data[name] || ''
                            });

                            break;
                    }
                    
                    var wrapper = this;

                    setTimeout(function(){
                        
                        container.add(Ext.applyIf(record, {
                            xtype       : 'textarea',
                            fieldLabel  : label,
                            description : MODx.expandHelp ? '' : name,
                            name        : 'data_' + name,
                            anchor      : '100%',
                            allowBlank  : !(record.required && parseInt(record.required)),
                            value       : wrapper.config.record.data[name] || ''
                        }));
    
                        if (record.xtype !== 'hidden') {
                            container.add({
                                xtype       : MODx.expandHelp ? 'label' : 'hidden',
                                html        :  desc,
                                cls         : 'desc-under'
                            });
                        }
                        
                        wrapper.doLayout();
                    }, 1300);
                }, this);

                /*this.doLayout();*/
            }
        }
    }
});

Ext.reg('digitalsignage-window-slide-update', DigitalSignage.window.UpdateSlide);

DigitalSignage.window.DuplicateSlide = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.slide_duplicate'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/duplicate'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_name'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_name_desc'),
            name        : 'name',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_name_desc'),
            cls         : 'desc-under'
        }]
    });

    DigitalSignage.window.DuplicateSlide.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.DuplicateSlide, MODx.Window);

Ext.reg('digitalsignage-window-slide-duplicate', DigitalSignage.window.DuplicateSlide);

DigitalSignage.combo.SlidesTypes = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/types/getnodes'
        },
        fields      : ['id', 'key', 'name', 'description', 'icon', 'time', 'data', 'name_formatted', 'description_formatted'],
        hiddenName  : 'type',
        pageSize    : 15,
        valueField  : 'id',
        displayField: 'name_formatted',
        tpl         : new Ext.XTemplate('<tpl for=".">' +
            '<div class="x-combo-list-item">' +
                '<i class="icon icon-slide-type icon-{icon}"></i>' +
                '<span><span style="font-weight: bold;">{name_formatted}</span><br />{description_formatted}</span>' +
            '</div>' +
        '</tpl>')
    });

    DigitalSignage.combo.SlidesTypes.superclass.constructor.call(this,config);
};

Ext.extend(DigitalSignage.combo.SlidesTypes, MODx.combo.ComboBox);

Ext.reg('digitalsignage-combo-slides-types', DigitalSignage.combo.SlidesTypes);