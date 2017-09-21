Narrowcasting.grid.SlideTypesData = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('narrowcasting.slide_type_data_create'),
        cls			:'primary-button',
        handler		: this.createSlideTypeData,
        scope		: this
    }, '->', {
        xtype		: 'textfield',
        name 		: 'narrowcasting-filter-search-slide-types-data',
        id			: 'narrowcasting-filter-search-slide-types-data',
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
    	id			: 'narrowcasting-filter-clear-slide-types-data',
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
            header		: _('narrowcasting.label_slide_type_data_key'),
            dataIndex	: 'key',
            sortable	: true,
            editable	: false,
            width		: 175,
            fixed		: true
        }, {
            header		: _('narrowcasting.label_slide_type_data_xtype'),
            dataIndex	: 'xtype',
            sortable	: false,
            editable	: false,
            width		: 250
        }, {
            header		: _('narrowcasting.label_slide_type_data_value'),
            dataIndex	: 'value',
            sortable	: false,
            editable	: false,
            width		: 175,
            fixed		: true
        }]
    });

    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'narrowcasting-grid-slide-types-data',
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
        	action		: 'mgr/slides/types/data/getlist',
            id			: config.record.key
        },
        fields		: ['key', 'xtype', 'value'],
        paging		: true,
        pageSize	: 5,
        sortBy		: 'key',
        primaryKey	: 'key'
    });

    Narrowcasting.grid.SlideTypesData.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.grid.SlideTypesData, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.query = '';

	    Ext.getCmp('narrowcasting-filter-search-slide-types-data').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('narrowcasting.slide_type_data_update'),
	        handler	: this.updateSlideTypeData,
	        scope	: this
	    }, '-', {
		    text	: _('narrowcasting.slide_type_data_remove'),
		    handler	: this.removeSlideTypeData,
		    scope	: this
		}];
    },
    createSlideTypeData: function(btn, e) {
        if (this.createSlideTypeDataWindow) {
	        this.createSlideTypeDataWindow.destroy();
        }

        this.createSlideTypeDataWindow = MODx.load({
	        xtype		: 'narrowcasting-window-slide-type-data-create',
			record		: Ext.apply({}, {
                id	: this.config.record.key
            }),
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	         }
        });

        this.createSlideTypeDataWindow.setValues(Ext.apply({}, {
            id	: this.config.record.key
        }));
        this.createSlideTypeDataWindow.show(e.target);
    },
    updateSlideTypeData: function(btn, e) {
        if (this.updateSlideTypeDataWindow) {
	        this.updateSlideTypeDataWindow.destroy();
        }

        this.updateSlideTypeDataWindow = MODx.load({
	        xtype		: 'narrowcasting-window-slide-type-data-update',
	        record		: Ext.apply(this.menu.record, {
                id	: this.config.record.key
            }),
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	        }
        });

        this.updateSlideTypeDataWindow.setValues(Ext.apply(this.menu.record, {
            id	: this.config.record.key
        }));
        this.updateSlideTypeDataWindow.show(e.target);
    },
	removeSlideTypeData: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.slide_type_data_remove'),
        	text		: _('narrowcasting.slide_type_data_remove_confirm'),
        	url			: Narrowcasting.config.connector_url,
        	params		: {
            	action		: 'mgr/slides/types/data/remove',
                id	        : this.config.record.key,
            	key			: this.menu.record.key
            },
            listeners	: {
            	'success'	: {
            		fn			: this.refresh,
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

Ext.reg('narrowcasting-grid-slide-types-data', Narrowcasting.grid.SlideTypesData);

Narrowcasting.window.CreateSlideTypeData = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.slide_type_data_create'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/types/data/create'
        },
        fields		: [{
    		xtype		: 'hidden',
			name		: 'id'
    	}, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_slide_type_data_key'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_data_key_desc'),
        	name		: 'key',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_data_key_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_slide_type_data_xtype'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_data_xtype_desc'),
        	name		: 'xtype',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_data_xtype_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('narrowcasting.label_slide_type_data_value'),
            description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_data_value_desc'),
            name		: 'value',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_data_value_desc'),
            cls			: 'desc-under'
        }]
    });

    Narrowcasting.window.CreateSlideTypeData.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.CreateSlideTypeData, MODx.Window);

Ext.reg('narrowcasting-window-slide-type-data-create', Narrowcasting.window.CreateSlideTypeData);

Narrowcasting.window.UpdateSlideTypeData = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.slide_type_data_update'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/types/data/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('narrowcasting.label_slide_type_data_key'),
            description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_data_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false,
            readOnly    : true,
            cls         : 'x-static-text-field'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_data_key_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('narrowcasting.label_slide_type_data_xtype'),
            description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_data_xtype_desc'),
            name		: 'xtype',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_data_xtype_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('narrowcasting.label_slide_type_data_value'),
            description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_data_value_desc'),
            name		: 'value',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_data_value_desc'),
            cls			: 'desc-under'
        }]
    });

    Narrowcasting.window.UpdateSlideTypeData.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.UpdateSlideTypeData, MODx.Window);

Ext.reg('narrowcasting-window-slide-type-data-update', Narrowcasting.window.UpdateSlideTypeData);