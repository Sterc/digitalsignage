DigitalSignage.grid.SlideTypesData = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('digitalsignage.slide_type_data_create'),
        cls         : 'primary-button',
        handler     : this.createSlideTypeData,
        scope       : this
    }, '->', {
        xtype       : 'textfield',
        name        : 'digitalsignage-filter-slide-types-data-search',
        id          : 'digitalsignage-filter-slide-types-data-search',
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
        id          : 'digitalsignage-filter-slide-types-data-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('digitalsignage.label_slide_type_data_key'),
            dataInde    : 'key',
            sortable    : false,
            editable    : false,
            width       : 175,
            fixed       : true
        }, {
            header      : _('digitalsignage.label_slide_type_data_xtype'),
            dataIndex   : 'xtype',
            sortable    : false,
            editable    : false,
            width       : 250,
            renderer    : this.renderXType
        }, {
            header      : _('digitalsignage.label_slide_type_data_required'),
            dataIndex   : 'required',
            sortable    : false,
            editable    : false,
            width       : 100,
            fixed       : true,
            renderer    : this.renderBoolean
        }]
    });

    Ext.applyIf(config, {
        cm          : columns,
        id          : 'digitalsignage-grid-slide-types-data',
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/types/data/getlist',
            id          : config.record.id
        },
        fields      : ['key', 'xtype', 'label', 'description', 'default_value', 'values', 'required', 'menuindex'],
        paging      : true,
        pageSize    : 10,
        sortBy      : 'key',
        primaryKey  : 'key',
        enableDragDrop : true,
        ddGroup     : 'digitalsignage-grid-slide-types-data'
    });

    DigitalSignage.grid.SlideTypesData.superclass.constructor.call(this, config);

    this.on('afterrender', this.sortData, this);
};

Ext.extend(DigitalSignage.grid.SlideTypesData, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.query = '';

        Ext.getCmp('digitalsignage-filter-slide-types-data-search').reset();

        this.getBottomToolbar().changePage(1);
    },
    sortData: function() {
        new Ext.dd.DropTarget(this.getView().mainBody, {
            ddGroup     : this.config.ddGroup,
            notifyDrop  : function(dd, e, data) {
                var grid    = data.grid,
                    sm      = grid.getSelectionModel(),
                    sels    = sm.getSelections(),
                    items   = grid.getStore().data.items;

                if (undefined != (index = dd.getDragData(e).rowIndex)) {
                    if (sm.hasSelection()) {
                        for (i = 0; i < sels.length; i++) {
                            grid.getStore().remove(grid.getStore().getById(sels[i].id));
                            grid.getStore().insert(index, sels[i]);
                        }

                        sm.selectRecords(sels);
                    }

                    var sort = [];

                    for (var i = 0; i < items.length; i++) {
                        sort.push({
                            'key'       : items[i].json.key,
                            'menuindex' : i
                        });
                    }

                    Ext.Ajax.request({
                         url        : DigitalSignage.config.connector_url,
                         params     : {
                             action     : 'mgr/slides/types/data/sort',
                             id         : grid.baseParams.id,
                             sort       : Ext.encode(sort)
                         },
                         success    : function(result) {
                             grid.getSelectionModel().clearSelections(true);

                             grid.refresh();
                         }
                    });
                }
            }
        });
    },
    getMenu: function() {
        return [{
            text    : '<i class="x-menu-item-icon icon icon-edit"></i>' + _('digitalsignage.slide_type_data_update'),
            handler : this.updateSlideTypeData,
            scope   : this
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('digitalsignage.slide_type_data_remove'),
            handler : this.removeSlideTypeData,
            scope   : this
        }];
    },
    createSlideTypeData: function(btn, e) {
        if (this.createSlideTypeDataWindow) {
            this.createSlideTypeDataWindow.destroy();
        }

        var record = {
            id : this.config.record.id
        };

        this.createSlideTypeDataWindow = MODx.load({
            xtype       : 'digitalsignage-window-slide-type-data-create',
            record      : record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });

        this.createSlideTypeDataWindow.setValues(record);
        this.createSlideTypeDataWindow.show(e.target);
    },
    updateSlideTypeData: function(btn, e) {
        if (this.updateSlideTypeDataWindow) {
            this.updateSlideTypeDataWindow.destroy();
        }

        var record = Ext.apply(this.menu.record, {
            id : this.config.record.id
        });

        this.updateSlideTypeDataWindow = MODx.load({
            xtype       : 'digitalsignage-window-slide-type-data-update',
            record      : record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });

        this.updateSlideTypeDataWindow.setValues(record);
        this.updateSlideTypeDataWindow.show(e.target);
    },
    removeSlideTypeData: function() {
        MODx.msg.confirm({
            title       : _('digitalsignage.slide_type_data_remove'),
            text        : _('digitalsignage.slide_type_data_remove_confirm'),
            url         : DigitalSignage.config.connector_url,
            params      : {
                action      : 'mgr/slides/types/data/remove',
                id          : this.config.record.id,
                key         : this.menu.record.key
            },
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
    },
    renderXType: function(d) {
        var types = {
            'textfield'             : 'Textfield',
            'statictextfield'       : 'Textfield (static)',
            'hidden'                : 'Textfield (hidden)',
            'datefield'             : 'Textfield (date)',
            'timefield'             : 'Textfield (time)',
            'xdatetime'             : 'Textfield (date/time)',
            'colorfield'            : 'Textfield (colorpicker)',
            'textarea'              : 'Textarea',
            'richtext'              : 'Textarea (editor)',
            'modx-combo-browser'    : 'Media',
            'combo'                 : 'Select',
            'checkbox'              : 'Checkbox',
            'checkboxgroup'         : 'Checkboxgroup',
            'radio'                 : 'Radio',
            'radiogroup'            : 'Radiogroup'
        };

        return types[d];
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

Ext.reg('digitalsignage-grid-slide-types-data', DigitalSignage.grid.SlideTypesData);

DigitalSignage.window.CreateSlideTypeData = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.slide_type_data_create'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/types/data/create'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_data_key'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_key_desc'),
            name        : 'key',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_key_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'digitalsignage-combo-field-xtype',
            fieldLabel  : _('digitalsignage.label_slide_type_data_xtype'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_xtype_desc'),
            name        : 'xtype',
            anchor      : '100%',
            listeners   : {
                'select'    : {
                    fn          : this.onUpdateXType,
                    scope       : this
                },
                'render'    : {
                    fn          : this.onUpdateXType,
                    scope       : this
                }
            }
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_xtype_desc'),
            cls         : 'desc-under'
        }, {
            layout      : 'form',
            labelAlign  : 'top',
            labelSeparator : '',
            id          : 'digitalsignage-slide-type-values-create',
            hidden      : true,
            items       : [{
                xtype       : 'textfield',
                fieldLabel  : _('digitalsignage.label_slide_type_data_values'),
                description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_values_desc'),
                name        : 'values',
                anchor      : '100%'
            }, {
                xtype       : MODx.expandHelp ? 'label' : 'hidden',
                html        : _('digitalsignage.label_slide_type_data_values_desc'),
                cls         : 'desc-under'
            }]
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_data_label'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_label_desc'),
            name        : 'label',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_label_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_slide_type_data_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_data_value'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_value_desc'),
            name        : 'default_value',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_value_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'checkbox',
            boxLabel    : _('digitalsignage.label_slide_type_data_required'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_required_desc'),
            name        : 'required',
            anchor      : '100%',
            inputValue  : 1
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_required_desc'),
            cls         : 'desc-under'
        }]
    });

    DigitalSignage.window.CreateSlideTypeData.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.CreateSlideTypeData, MODx.Window, {
    onUpdateXType: function(tf) {
        if (-1 === ['combo', 'checkboxgroup', 'radiogroup'].indexOf(tf.getValue())) {
            Ext.getCmp('digitalsignage-slide-type-values-create').hide();
        } else {
            Ext.getCmp('digitalsignage-slide-type-values-create').show();
        }
    }
});

Ext.reg('digitalsignage-window-slide-type-data-create', DigitalSignage.window.CreateSlideTypeData);

DigitalSignage.window.UpdateSlideTypeData = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('digitalsignage.slide_type_data_update'),
        url         : DigitalSignage.config.connector_url,
        baseParams  : {
            action      : 'mgr/slides/types/data/update'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_data_key'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_key_desc'),
            name        : 'key',
            anchor      : '100%',
            allowBlank  : false,
            readOnly    : true,
            cls         : 'x-static-text-field'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_key_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'digitalsignage-combo-field-xtype',
            fieldLabel  : _('digitalsignage.label_slide_type_data_xtype'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_xtype_desc'),
            name        : 'xtype',
            anchor      : '100%',
            listeners   : {
                'select'    : {
                    fn          : this.onUpdateXType,
                    scope       : this
                },
                'render'    : {
                    fn          : this.onUpdateXType,
                    scope       : this
                }
            }
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_xtype_desc'),
            cls         : 'desc-under'
        }, {
            layout      : 'form',
            labelAlign  : 'top',
            labelSeparator : '',
            id          : 'digitalsignage-slide-type-values-update',
            hidden      : true,
            items       : [{
                xtype       : 'textfield',
                fieldLabel  : _('digitalsignage.label_slide_type_data_values'),
                description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_values_desc'),
                name        : 'values',
                anchor      : '100%'
            }, {
                xtype       : MODx.expandHelp ? 'label' : 'hidden',
                html        : _('digitalsignage.label_slide_type_data_values_desc'),
                cls         : 'desc-under'
            }]
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_data_label'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_label_desc'),
            name        : 'label',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_label_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('digitalsignage.label_slide_type_data_description'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('digitalsignage.label_slide_type_data_value'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_value_desc'),
            name        : 'default_value',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_value_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'checkbox',
            boxLabel    : _('digitalsignage.label_slide_type_data_required'),
            description : MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_required_desc'),
            name        : 'required',
            anchor      : '100%',
            inputValue  : 1
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('digitalsignage.label_slide_type_data_required_desc'),
            cls         : 'desc-under'
        }]
    });

    DigitalSignage.window.UpdateSlideTypeData.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.UpdateSlideTypeData, MODx.Window, {
    onUpdateXType: function(tf) {
        if (-1 === ['combo', 'checkboxgroup', 'radiogroup'].indexOf(tf.getValue())) {
            Ext.getCmp('digitalsignage-slide-type-values-update').hide();
        } else {
            Ext.getCmp('digitalsignage-slide-type-values-update').show();
        }
    }
});

Ext.reg('digitalsignage-window-slide-type-data-update', DigitalSignage.window.UpdateSlideTypeData);

DigitalSignage.combo.FieldXType = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        store       : new Ext.data.ArrayStore({
            mode        : 'local',
            fields      : ['type','label'],
            data        : [
                ['textfield', 'Textfield'],
                ['statictextfield', 'Textfield (static)'],
                ['hidden', 'Textfield (hidden)'],
                ['datefield', 'Textfield (date)'],
                ['timefield', 'Textfield (time)'],
                ['xdatetime', 'Textfield (date/time)'],
                ['colorfield', 'Textfield (colorpicker)'],
                ['textarea', 'Textarea'],
                ['richtext', 'Textarea (editor)'],
                ['modx-combo-browser', 'Media'],
                ['combo', 'Select'],
                ['checkbox', 'Checkbox'],
                ['checkboxgroup', 'Checkboxgroup'],
                ['radio', 'Radio'],
                ['radiogroup', 'Radiogroup']
            ]
        }),
        hiddenName  : 'xtype',
        valueField  : 'type',
        displayField : 'label',
        mode        : 'local',
        value       : 'textfield'
    });

    DigitalSignage.combo.FieldXType.superclass.constructor.call(this,config);
};

Ext.extend(DigitalSignage.combo.FieldXType, MODx.combo.ComboBox);

Ext.reg('digitalsignage-combo-field-xtype', DigitalSignage.combo.FieldXType);