DigitalSignage.grid.Slides = function(config) {
    config = config || {};

    config.tbar = [{
        text		: _('digitalsignage.slide_create'),
        cls			:'primary-button',
        handler		: this.createSlide,
        scope		: this
    }, '->', {
        xtype		: 'textfield',
        name 		: 'digitalsignage-filter-search-slides',
        id			: 'digitalsignage-filter-search-slides',
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
        id			: 'digitalsignage-filter-clear-slides',
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
            header		: _('digitalsignage.label_slide_name'),
            dataIndex	: 'name',
            sortable	: true,
            editable	: false,
            width		: 250
        }, {
            header		: _('digitalsignage.label_slide_type'),
            dataIndex	: 'type_formatted',
            sortable	: true,
            editable	: false,
            width		: 200,
            fixed 		: true
        }, {
            header		: _('digitalsignage.label_slide_published'),
            dataIndex	: 'published',
            sortable	: true,
            editable	: true,
            width		: 100,
            fixed		: true,
            renderer	: this.renderBoolean
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
        id			: 'digitalsignage-grid-slides',
        url			: DigitalSignage.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/getlist'
        },
        fields		: ['id', 'type', 'name', 'time', 'data', 'published', 'editedon', 'type_formatted', 'broadcasts'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        refreshGrid : []
    });

    DigitalSignage.grid.Slides.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.grid.Slides, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.query = '';

        Ext.getCmp('digitalsignage-filter-search-slides').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
            text	: _('digitalsignage.slide_update'),
            handler	: this.updateSlide,
            scope	: this
        }, {
            text	: _('digitalsignage.slide_duplicate'),
            handler	: this.duplicateSlide,
            scope	: this
        }, '-', {
            text	: _('digitalsignage.slide_remove'),
            handler	: this.removeSlide,
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
    createSlide: function(btn, e) {
        if (this.createSlideWindow) {
            this.createSlideWindow.destroy();
        }

        this.createSlideWindow = MODx.load({
            xtype		: 'digitalsignage-window-slide-create',
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

        this.createSlideWindow.show(e.target);
    },
    updateSlide: function(btn, e) {
        if (this.updateSlideWindow) {
            this.updateSlideWindow.destroy();
        }

        this.updateSlideWindow = MODx.load({
            xtype		: 'digitalsignage-window-slide-update',
            record		: this.menu.record,
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

        this.updateSlideWindow.setValues(this.menu.record);
        this.updateSlideWindow.show(e.target);
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
            xtype		: 'digitalsignage-window-slide-duplicate',
            record		: record,
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

        this.duplicateSlideWindow.setValues(record);
        this.duplicateSlideWindow.show(e.target);
    },
    removeSlide: function() {
        MODx.msg.confirm({
            title 		: _('digitalsignage.slide_remove'),
            text		: _('digitalsignage.slide_remove_confirm'),
            url			: DigitalSignage.config.connector_url,
            params		: {
                action		: 'mgr/slides/remove',
                id			: this.menu.record.id
            },
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
    },
    renderBoolean: function(d, c) {
        c.css = 1 == parseInt(d) || d ? 'green' : 'red';

        return 1 == parseInt(d) || d ? _('yes') : _('no');
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('digitalsignage-grid-slides', DigitalSignage.grid.Slides);

DigitalSignage.window.CreateSlide = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        width		: 1000,
        autoHeight	: true,
        title 		: _('digitalsignage.slide_create'),
        url			: DigitalSignage.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/create'
        },
        fields		: [{
            layout		: 'column',
            border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
            items		: [{
                columnWidth	: .3,
                items   : [{
                    layout		: 'column',
                    border		: false,
                    defaults	: {
                        layout		: 'form',
                        labelSeparator : ''
                    },
                    items		: [{
                        columnWidth	: .6,
                        items   : [{
                            xtype		: 'digitalsignage-combo-slides-types',
                            fieldLabel	: _('digitalsignage.label_slide_type'),
                            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_desc'),
                            name		: 'type',
                            anchor		: '100%',
                            allowBlank	: false,
                            listeners	: {
                                'change'	: {
                                    fn 			: this.getTypeFields,
                                    scope 		: this
                                },
                                'loaded' 	: {
                                    fn 			: this.getTypeFields,
                                    scope 		: this
                                }
                            }
                        }]
                    }, {
                        columnWidth	: .4,
                        style		: 'margin-right: 0;',
                        items : [{
                            xtype		: 'checkbox',
                            fieldLabel	: '&nbsp;',
                            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_published_desc'),
                            name		: 'published',
                            inputValue	: 1,
                            boxLabel	: _('digitalsignage.label_slide_published'),
                            checked		: true
                        }, {
                            xtype		: MODx.expandHelp ? 'label' : 'hidden',
                            html		: _('digitalsignage.label_slide_published_desc'),
                            cls			: 'desc-under'
                        }]
                    }]
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('digitalsignage.label_slide_type_desc'),
                    cls			: 'desc-under'
                }, {
                    xtype		: 'textfield',
                    fieldLabel	: _('digitalsignage.label_slide_name'),
                    description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_name_desc'),
                    name		: 'name',
                    anchor		: '100%',
                    allowBlank	: false
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('digitalsignage.label_slide_name_desc'),
                    cls			: 'desc-under'
                }, {
                    xtype		: 'textfield',
                    fieldLabel	: _('digitalsignage.label_slide_time'),
                    description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_time_desc'),
                    name		: 'time',
                    id			: 'digitalsignage-window-slide-create-time',
                    anchor		: '100%',
                    allowBlank	: false,
                    listeners	: {
                        'change'	: {
                            fn 			: this.getSeconds,
                            scope 		: this
                        }
                    }
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('digitalsignage.label_slide_time_desc'),
                    cls			: 'desc-under'
                }, {
                    xtype       : 'digitalsignage-checkbox-broadcasts',
                    fieldLabel  : _('digitalsignage.label_slide_broadcasts'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_broadcasts_desc')
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('digitalsignage.label_slide_broadcasts_desc'),
                    cls			: 'desc-under'
                }]
            }, {
                columnWidth	: .7,
                style		: 'margin-right: 0;',
                items 		: [{
                    xtype		: 'container',
                    layout		: 'form',
                    labelSeparator : '',
                    id			: 'digitalsignage-window-slide-create-fields',
                    cls			: 'digitalsignage-slide-options',
                    items		: [{
                        html 		: '<i class="icon icon-cog"></i>'
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

            if ('0' == res[0].substr(0, 1) && res[0].length > 1) {
                var minutes = parseInt(res[0].substr(1));
            } else {
                var minutes = parseInt(res[0]);
            }

            if ('0' == res[1].substr(0, 1) && res[1].length > 1) {
                var seconds = parseInt(res[1].substr(1));
            } else {
                var seconds = parseInt(res[1]);
            }

            tf.setValue((minutes * 60) + seconds);
        }
    },
    getTypeFields: function(tf, nv, ov) {
        var type = tf.getValue();

        if (undefined != (record = tf.findRecord(tf.valueField, type))) {
            if (undefined != (container = Ext.getCmp('digitalsignage-window-slide-create-time'))) {
                if ('' == container.getValue() || 0 == container.getValue()) {
                    container.setValue(record.data.time);
                }
            }

            if (undefined != (container = Ext.getCmp('digitalsignage-window-slide-create-fields'))) {
                container.removeAll();

                Ext.iterate(record.data.data, function(name, record) {
                    var label   = _('digitalsignage.slide_' + type + '_' + name);
                        desc    = _('digitalsignage.slide_' + type + '_' + name + '_desc');

                    if (record.label && '' != record.label) {
                        label = record.label;
                    }

                    if (record.description && '' != record.description) {
                        desc = record.description;
                    }

                    switch (record.xtype) {
                        case 'checkbox':
                            record = Ext.apply(record, {
                                hideLabel	: true,
                                boxLabel	: label,
                                inputValue  : record.value
                            });

                            break;
                        case 'textarea':
                            record = Ext.apply(record, {
                                listeners	: {
                                    'afterrender': {
                                        fn : function(data) {
                                            if (MODx.loadRTE) {
                                                MODx.loadRTE(data.id, {
                                                    height : '200px'
                                                });
                                            }
                                        }
                                    }
                                }
                            });

                            break;
                    }

                    container.add(Ext.applyIf(record, {
                        xtype 		: 'textarea',
                        fieldLabel	: label,
                        description	: MODx.expandHelp ? '' : desc,
                        name		: 'data_' + name,
                        anchor		: '100%',
                        allowBlank	: true
                    }));

                    container.add({
                        xtype		: MODx.expandHelp ? 'label' : 'hidden',
                        html		: desc,
                        cls			: 'desc-under'
                    });
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
        width		: 1000,
        autoHeight	: true,
        title 		: _('digitalsignage.slide_update'),
        url			: DigitalSignage.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            layout		: 'column',
            border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
            items		: [{
                columnWidth	: .3,
                items   : [{
                    layout		: 'column',
                    border		: false,
                    defaults	: {
                        layout		: 'form',
                        labelSeparator : ''
                    },
                    items		: [{
                        columnWidth	: .6,
                        items   : [{
                            xtype		: 'digitalsignage-combo-slides-types',
                            fieldLabel	: _('digitalsignage.label_slide_type'),
                            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_desc'),
                            name		: 'type',
                            anchor		: '100%',
                            allowBlank	: false,
                            listeners	: {
                                'change'	: {
                                    fn 			: this.getTypeFields,
                                    scope 		: this
                                },
                                'loaded' 	: {
                                    fn 			: this.getTypeFields,
                                    scope 		: this
                                }
                            }
                        }]
                    }, {
                        columnWidth	: .4,
                        style		: 'margin-right: 0;',
                        items : [{
                            xtype		: 'checkbox',
                            fieldLabel	: '&nbsp;',
                            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_published_desc'),
                            name		: 'published',
                            inputValue	: 1,
                            boxLabel	: _('digitalsignage.label_slide_published')
                        }, {
                            xtype		: MODx.expandHelp ? 'label' : 'hidden',
                            html		: _('digitalsignage.label_slide_published_desc'),
                            cls			: 'desc-under'
                        }]
                    }]
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('digitalsignage.label_slide_type_desc'),
                    cls			: 'desc-under'
                }, {
                    xtype		: 'textfield',
                    fieldLabel	: _('digitalsignage.label_slide_name'),
                    description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_name_desc'),
                    name		: 'name',
                    anchor		: '100%',
                    allowBlank	: false
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('digitalsignage.label_slide_name_desc'),
                    cls			: 'desc-under'
                }, {
                    xtype		: 'textfield',
                    fieldLabel	: _('digitalsignage.label_slide_time'),
                    description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_time_desc'),
                    name		: 'time',
                    id			: 'digitalsignage-window-slide-update-time',
                    anchor		: '100%',
                    allowBlank	: false,
                    listeners	: {
                        'change'	: {
                            fn 			: this.getSeconds,
                            scope 		: this
                        }
                    }
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('digitalsignage.label_slide_time_desc'),
                    cls			: 'desc-under'
                }, {
                    xtype       : 'digitalsignage-checkbox-broadcasts',
                    fieldLabel  : _('digitalsignage.label_slide_broadcasts'),
                    description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_broadcasts_desc'),
                    value       : config.record.broadcasts
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('digitalsignage.label_slide_broadcasts_desc'),
                    cls			: 'desc-under'
                }]
            }, {
                columnWidth	: .7,
                style		: 'margin-right: 0;',
                items 		: [{
                    xtype		: 'container',
                    layout		: 'form',
                    labelSeparator : '',
                    id			: 'digitalsignage-window-slide-update-fields',
                    cls			: 'digitalsignage-slide-options',
                    items		: [{
                        html 		: '<i class="icon icon-cog"></i>'
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

            if ('0' == res[0].substr(0, 1) && res[0].length > 1) {
                var minutes = parseInt(res[0].substr(1));
            } else {
                var minutes = parseInt(res[0]);
            }

            if ('0' == res[1].substr(0, 1) && res[1].length > 1) {
                var seconds = parseInt(res[1].substr(1));
            } else {
                var seconds = parseInt(res[1]);
            }

            tf.setValue((minutes * 60) + seconds);
        }
    },
    getTypeFields: function(tf, nv, ov) {
        var type = tf.getValue();

        if (undefined != (record = tf.findRecord(tf.valueField, type))) {
            if (undefined != (container = Ext.getCmp('digitalsignage-window-slide-update-time'))) {
                if ('' == container.getValue() || 0 == container.getValue()) {
                    container.setValue(record.data.time);
                }
            }

            if (undefined != (container = Ext.getCmp('digitalsignage-window-slide-update-fields'))) {
                container.removeAll();

                Ext.iterate(record.data.data, function(name, record) {
                    var label   = _('digitalsignage.slide_' + type + '_' + name);
                        desc    = _('digitalsignage.slide_' + type + '_' + name + '_desc');

                    if (record.label && '' != record.label) {
                        label = record.label;
                    }

                    if (record.description && '' != record.description) {
                        desc = record.description;
                    }

                    switch (record.xtype) {
                        case 'checkbox':
                            record = Ext.apply(record, {
                                hideLabel	: true,
                                boxLabel	: label,
                                inputValue	: record.value,
                                checked		: this.config.record.data[name] == record.value
                            });

                            break;
                        case 'textarea':
                            record = Ext.apply(record, {
                                value 		: this.config.record.data[name],
                                listeners	: {
                                    'afterrender': {
                                        fn : function(data) {
                                            if (MODx.loadRTE) {
                                                MODx.loadRTE(data.id, {
                                                    height : '200px'
                                                });
                                            }
                                        }
                                    }
                                }
                            });

                            break;
                        default:
                            record = Ext.apply(record, {
                                value 		: this.config.record.data[name]
                            });

                            break;
                    }

                    container.add(Ext.applyIf(record, {
                        xtype 		: 'textarea',
                        fieldLabel	: label,
                        description	: MODx.expandHelp ? '' : desc,
                        name		: 'data_' + name,
                        anchor		: '100%',
                        allowBlank	: true,
                        value 		: this.config.record.data[name]
                    }));

                    container.add({
                        xtype		: MODx.expandHelp ? 'label' : 'hidden',
                        html		:  desc,
                        cls			: 'desc-under'
                    });

                }, this);

                this.doLayout();
            }
        }
    }
});

Ext.reg('digitalsignage-window-slide-update', DigitalSignage.window.UpdateSlide);

DigitalSignage.window.DuplicateSlide = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight	: true,
        title 		: _('digitalsignage.slide_duplicate'),
        url			: DigitalSignage.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/duplicate'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('digitalsignage.label_slide_name'),
            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_name_desc'),
            name		: 'name',
            anchor		: '100%',
            allowBlank	: false
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_name_desc'),
            cls			: 'desc-under'
        }]
    });

    DigitalSignage.window.DuplicateSlide.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.DuplicateSlide, MODx.Window);

Ext.reg('digitalsignage-window-slide-duplicate', DigitalSignage.window.DuplicateSlide);

DigitalSignage.combo.SlidesTypes = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url			: DigitalSignage.config.connector_url,
        baseParams 	: {
            action		: 'mgr/slides/types/getnodes'
        },
        fields		: ['key', 'name', 'description', 'icon', 'time', 'data', 'name_formatted', 'description_formatted'],
        hiddenName	: 'type',
        pageSize	: 15,
        valueField	: 'key',
        displayField: 'name_formatted',
        tpl			: new Ext.XTemplate('<tpl for=".">' +
            '<div class="x-combo-list-item">' +
                '<span style="font-weight: bold">{name_formatted}</span><br />{description_formatted}' +
            '</div>' +
        '</tpl>')
    });

    DigitalSignage.combo.SlidesTypes.superclass.constructor.call(this,config);
};

Ext.extend(DigitalSignage.combo.SlidesTypes, MODx.combo.ComboBox);

Ext.reg('digitalsignage-combo-slides-types', DigitalSignage.combo.SlidesTypes);