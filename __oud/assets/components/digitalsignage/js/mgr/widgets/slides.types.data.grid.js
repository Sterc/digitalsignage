DigitalSignage.grid.SlideTypesData = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('digitalsignage.slide_type_data_create'),
        cls			:'primary-button',
        handler		: this.createSlideTypeData,
        scope		: this
    }, '->', {
        xtype		: 'textfield',
        name 		: 'digitalsignage-filter-search-slide-types-data',
        id			: 'digitalsignage-filter-search-slide-types-data',
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
    	id			: 'digitalsignage-filter-clear-slide-types-data',
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
            header		: _('digitalsignage.label_slide_type_data_key'),
            dataIndex	: 'key',
            sortable	: true,
            editable	: false,
            width		: 175,
            fixed		: true
        }, {
            header		: _('digitalsignage.label_slide_type_data_xtype'),
            dataIndex	: 'xtype',
            sortable	: false,
            editable	: false,
            width		: 250
        }, {
            header		: _('digitalsignage.label_slide_type_data_value'),
            dataIndex	: 'value',
            sortable	: false,
            editable	: false,
            width		: 175,
            fixed		: true
        }]
    });

    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'digitalsignage-grid-slide-types-data',
        url			: DigitalSignage.config.connector_url,
        baseParams	: {
        	action		: 'mgr/slides/types/data/getlist',
            id			: config.record.key
        },
        fields		: ['key', 'xtype', 'label', 'description', 'value'],
        paging		: true,
        pageSize	: 5,
        sortBy		: 'key',
        primaryKey	: 'key'
    });

    DigitalSignage.grid.SlideTypesData.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.grid.SlideTypesData, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.query = '';

	    Ext.getCmp('digitalsignage-filter-search-slide-types-data').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('digitalsignage.slide_type_data_update'),
	        handler	: this.updateSlideTypeData,
	        scope	: this
	    }, '-', {
		    text	: _('digitalsignage.slide_type_data_remove'),
		    handler	: this.removeSlideTypeData,
		    scope	: this
		}];
    },
    createSlideTypeData: function(btn, e) {
        if (this.createSlideTypeDataWindow) {
	        this.createSlideTypeDataWindow.destroy();
        }

        this.createSlideTypeDataWindow = MODx.load({
	        xtype		: 'digitalsignage-window-slide-type-data-create',
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
	        xtype		: 'digitalsignage-window-slide-type-data-update',
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
        	title 		: _('digitalsignage.slide_type_data_remove'),
        	text		: _('digitalsignage.slide_type_data_remove_confirm'),
        	url			: DigitalSignage.config.connector_url,
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

Ext.reg('digitalsignage-grid-slide-types-data', DigitalSignage.grid.SlideTypesData);

DigitalSignage.window.CreateSlideTypeData = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('digitalsignage.slide_type_data_create'),
        url			: DigitalSignage.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/types/data/create'
        },
        fields		: [{
    		xtype		: 'hidden',
			name		: 'id'
    	}, {
        	xtype		: 'textfield',
        	fieldLabel	: _('digitalsignage.label_slide_type_data_key'),
        	description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_key_desc'),
        	name		: 'key',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_key_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('digitalsignage.label_slide_type_data_xtype'),
        	description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_xtype_desc'),
        	name		: 'xtype',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_xtype_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('digitalsignage.label_slide_type_data_label'),
            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_label_desc'),
            name		: 'label',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_label_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textarea',
            fieldLabel	: _('digitalsignage.label_slide_type_data_description'),
            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_description_desc'),
            name		: 'description',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_description_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('digitalsignage.label_slide_type_data_value'),
            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_value_desc'),
            name		: 'value',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_value_desc'),
            cls			: 'desc-under'
        }]
    });

    DigitalSignage.window.CreateSlideTypeData.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.CreateSlideTypeData, MODx.Window);

Ext.reg('digitalsignage-window-slide-type-data-create', DigitalSignage.window.CreateSlideTypeData);

DigitalSignage.window.UpdateSlideTypeData = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('digitalsignage.slide_type_data_update'),
        url			: DigitalSignage.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/types/data/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('digitalsignage.label_slide_type_data_key'),
            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false,
            readOnly    : true,
            cls         : 'x-static-text-field'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_key_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('digitalsignage.label_slide_type_data_xtype'),
            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_xtype_desc'),
            name		: 'xtype',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_xtype_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('digitalsignage.label_slide_type_data_label'),
            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_label_desc'),
            name		: 'label',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_label_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textarea',
            fieldLabel	: _('digitalsignage.label_slide_type_data_description'),
            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_description_desc'),
            name		: 'description',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_description_desc'),
            cls			: 'desc-under'
        }, {
            xtype		: 'textfield',
            fieldLabel	: _('digitalsignage.label_slide_type_data_value'),
            description	: MODx.expandHelp ? '' : _('digitalsignage.label_slide_type_data_value_desc'),
            name		: 'value',
            anchor		: '100%'
        }, {
            xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('digitalsignage.label_slide_type_data_value_desc'),
            cls			: 'desc-under'
        }]
    });

    DigitalSignage.window.UpdateSlideTypeData.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.window.UpdateSlideTypeData, MODx.Window);

Ext.reg('digitalsignage-window-slide-type-data-update', DigitalSignage.window.UpdateSlideTypeData);