Narrowcasting.grid.SlideTypes = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('narrowcasting.slide_type_create'),
        cls			:'primary-button',
        handler		: this.createSlideType,
        scope		: this
    }, '->', {
        xtype		: 'textfield',
        name 		: 'narrowcasting-filter-search-slide-types',
        id			: 'narrowcasting-filter-search-slide-types',
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
    	id			: 'narrowcasting-filter-clear-slide-types',
    	text		: _('filter_clear'),
    	listeners	: {
        	'click'		: {
        		fn			: this.clearFilter,
        		scope		: this
        	}
        }
    }];
    
    expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{description_formatted}</p>'
        )
    });

    columns = new Ext.grid.ColumnModel({
        columns: [expander, {
            header		: _('narrowcasting.label_slide_type_key'),
            dataIndex	: 'key',
            sortable	: true,
            editable	: false,
            width		: 125,
            fixed		: true
        }, {
            header		: _('narrowcasting.label_slide_type_name'),
            dataIndex	: 'name_formatted',
            sortable	: true,
            editable	: false,
            width		: 250
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
        id			: 'narrowcasting-grid-slide-types',
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
        	action		: 'mgr/slides//types/getlist'
        },
        fields		: ['key', 'name', 'description', 'icon', 'time', 'data', 'editedon', 'name_formatted', 'description_formatted'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'key',
        primaryKey	: 'key',
        plugins		: expander
    });

    Narrowcasting.grid.SlideTypes.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.grid.SlideTypes, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.query = '';

	    Ext.getCmp('narrowcasting-filter-search-slide-types').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('narrowcasting.slide_type_update'),
	        handler	: this.updateSlideType,
	        scope	: this
	    }, '-', {
		    text	: _('narrowcasting.slide_type_remove'),
		    handler	: this.removeSlideType,
		    scope	: this
		}];
    },
    createSlideType: function(btn, e) {
        if (this.createSlideTypeWindow) {
	        this.createSlideTypeWindow.destroy();
        }

        this.createSlideTypeWindow = MODx.load({
	        xtype		: 'narrowcasting-window-slide-type-create',
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
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
	        xtype		: 'narrowcasting-window-slide-type-update',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	        }
        });

        this.updateSlideTypeWindow.setValues(this.menu.record);
        this.updateSlideTypeWindow.show(e.target);
    },
    removeSlideType: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.slide_type_remove'),
        	text		: _('narrowcasting.slide_type_remove_confirm'),
        	url			: Narrowcasting.config.connector_url,
        	params		: {
            	action		: 'mgr/slides/types/remove',
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

Ext.reg('narrowcasting-grid-slide-types', Narrowcasting.grid.SlideTypes);

Narrowcasting.window.CreateSlideType = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.slide_create'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/types/create'
        },
        fields		: [{
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_slide_type_key'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_key_desc'),
        	name		: 'key',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_key_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_slide_type_name'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_name_desc'),
        	name		: 'name',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_name_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textarea',
        	fieldLabel	: _('narrowcasting.label_slide_type_description'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_description_desc'),
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
				items : [{
		        	xtype		: 'textfield',
		        	fieldLabel	: _('narrowcasting.label_slide_type_icon'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_icon_desc'),
		        	name		: 'icon',
		        	anchor		: '100%'
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_slide_type_icon_desc'),
		            cls			: 'desc-under'
		        }]
			}, {
		        columnWidth	: .5,
		        style		: 'margin-right: 0;',
				items 		: [{
		        	xtype		: 'textfield',
		        	fieldLabel	: _('narrowcasting.label_slide_type_time'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_time_desc'),
		        	name		: 'time',
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
		            html		: _('narrowcasting.label_slide_type_time_desc'),
		            cls			: 'desc-under'
		        }]
			}]
		}]
    });

    Narrowcasting.window.CreateSlideType.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.CreateSlideType, MODx.Window, {
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

Ext.reg('narrowcasting-window-slide-type-create', Narrowcasting.window.CreateSlideType);

Narrowcasting.window.UpdateSlideType = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('narrowcasting.slide_update'),
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
            action		: 'mgr/slides/types/update'
        },
        fields		: [{
        	xtype		: 'statictextfield',
        	fieldLabel	: _('narrowcasting.label_slide_type_key'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_key_desc'),
        	name		: 'key',
        	anchor		: '100%',
        	allowBlank	: false,
        	submitValue	: true
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_key_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('narrowcasting.label_slide_type_name'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_name_desc'),
        	name		: 'name',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_name_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'textarea',
        	fieldLabel	: _('narrowcasting.label_slide_type_description'),
        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('narrowcasting.label_slide_type_description_desc'),
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
				items : [{
		        	xtype		: 'textfield',
		        	fieldLabel	: _('narrowcasting.label_slide_type_icon'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_icon_desc'),
		        	name		: 'icon',
		        	anchor		: '100%'
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_slide_type_icon_desc'),
		            cls			: 'desc-under'
		        }]
			}, {
		        columnWidth	: .5,
		        style		: 'margin-right: 0;',
				items 		: [{
		        	xtype		: 'textfield',
		        	fieldLabel	: _('narrowcasting.label_slide_type_time'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_time_desc'),
		        	name		: 'time',
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
		            html		: _('narrowcasting.label_slide_type_time_desc'),
		            cls			: 'desc-under'
		        }]
			}]
		}]
    });

    Narrowcasting.window.UpdateSlideType.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.UpdateSlideType, MODx.Window, {
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

Ext.reg('narrowcasting-window-slide-type-update', Narrowcasting.window.UpdateSlideType);