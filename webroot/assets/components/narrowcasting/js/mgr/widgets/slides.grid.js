Narrowcasting.grid.Slides = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('narrowcasting.slide_create'),
        cls			:'primary-button',
        handler		: this.createSlide,
        scope		: this
    }, '->', {
        xtype		: 'textfield',
        name 		: 'narrowcasting-filter-search-slides',
        id			: 'narrowcasting-filter-search-slides',
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
    	id			: 'narrowcasting-filter-clear-slides',
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
            header		: _('narrowcasting.label_slide_name'),
            dataIndex	: 'name',
            sortable	: true,
            editable	: false,
            width		: 250
        }, {
            header		: _('narrowcasting.label_slide_type'),
            dataIndex	: 'type_formatted',
            sortable	: true,
            editable	: false,
            width		: 200,
            fixed 		: true
        }, {
            header		: _('narrowcasting.label_slide_published'),
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
        id			: 'narrowcasting-grid-slides',
        url			: Narrowcasting.config.connector_url,
        baseParams	: {
        	action		: 'mgr/slides/getlist'
        },
        fields		: ['id', 'type', 'name', 'time', 'data', 'published', 'editedon', 'type_formatted'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        refreshGrid : []
    });

    Narrowcasting.grid.Slides.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.grid.Slides, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.query = '';

	    Ext.getCmp('narrowcasting-filter-search-slides').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('narrowcasting.slide_update'),
	        handler	: this.updateSlide,
	        scope	: this
	    }, '-', {
		    text	: _('narrowcasting.slide_remove'),
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
	        xtype		: 'narrowcasting-window-slide-create',
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
	        xtype		: 'narrowcasting-window-slide-update',
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
    removeSlide: function() {
    	MODx.msg.confirm({
        	title 		: _('narrowcasting.slide_remove'),
        	text		: _('narrowcasting.slide_remove_confirm'),
        	url			: Narrowcasting.config.connector_url,
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

Ext.reg('narrowcasting-grid-slides', Narrowcasting.grid.Slides);

Narrowcasting.window.CreateSlide = function(config) {
    config = config || {};

    Ext.applyIf(config, {
	    width		: 600,
    	autoHeight	: true,
        title 		: _('narrowcasting.slide_create'),
        url			: Narrowcasting.config.connector_url,
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
	        	columnWidth	: .5,
				items : [{
			    	xtype		: 'narrowcasting-combo-slides-types',
			    	fieldLabel	: _('narrowcasting.label_slide_type'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_desc'),
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
			    }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_slide_type_desc'),
		            cls			: 'desc-under'
		        }, {
		        	xtype		: 'textfield',
		        	fieldLabel	: _('narrowcasting.label_slide_name'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_name_desc'),
		        	name		: 'name',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_slide_name_desc'),
		            cls			: 'desc-under'
		        }, {
		        	layout		: 'column',
		        	border		: false,
		            defaults	: {
		                layout		: 'form',
		                labelSeparator : ''
		            },
		        	items		: [{
			        	columnWidth	: .7,
						items : [{
				        	xtype		: 'numberfield',
				        	fieldLabel	: _('narrowcasting.label_slide_time'),
				        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_time_desc'),
				        	name		: 'time',
				        	anchor		: '100%',
				        	allowBlank	: false,
				        	value		: 10
				        }, {
				        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
				            html		: _('narrowcasting.label_slide_time_desc'),
				            cls			: 'desc-under'
				        }]
			        }, {
				        columnWidth	: .3,
				        style		: 'margin-right: 0;',
						items : [{
					        xtype		: 'checkbox',
				            fieldLabel	: '&nbsp;',
				            description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_published_desc'),
				            name		: 'published',
				            inputValue	: 1,
				            boxLabel	: _('narrowcasting.label_slide_published'),
				            checked		: true
				        }, {
				        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
				            html		: _('narrowcasting.label_slide_published_desc'),
				            cls			: 'desc-under'
				        }]
					}]
		        }]
			}, {
		        columnWidth	: .5,
		        style		: 'margin-right: 0;',
				items 		: [{
			    	xtype		: 'container',
			    	layout		: 'form',
			    	labelSeparator : '',
			    	id			: 'narrowcasting-window-slide-create-fields',
			    	cls			: 'narrowcasting-slide-options',
			    	items		: [{
				    	html 		: '<i class="icon icon-cog"></i>'
			    	}]
			    }]
			}]
        }]
    });

    Narrowcasting.window.CreateSlide.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.CreateSlide, MODx.Window, {
	getTypeFields: function(tf, nv, ov) {
		var type = tf.getValue();

		if (undefined != (record = tf.findRecord(tf.valueField, type))) {
			if (undefined != (container = Ext.getCmp('narrowcasting-window-slide-create-fields'))) {
				container.removeAll();

				Ext.iterate(record.data.data, function(name, value) {
					if (value.xtype === 'textarea') {
						value.listeners = {
                            'afterrender' : {
								fn : function(data) {
									if (typeof TinyMCERTE.Tiny !== 'undefined') {
										MODx.loadRTE(data.id);
									}
								}
							}
						};
					}

					container.add(Ext.applyIf(value, {
						xtype 		: 'textarea',
						fieldLabel	: _('narrowcasting.slide_' + type + '_' + name),
						description	: MODx.expandHelp ? '' : _('narrowcasting.slide_' + type + '_' + name + '_desc'),
						name		: 'data_' + name,
						anchor		: '100%',
						allowBlank	: true
					}));

					container.add({
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		:  _('narrowcasting.slide_' + type + '_' + name + '_desc'),
			            cls			: 'desc-under'
			        });
				}, this);

				this.doLayout();
			}
		}
	}
});

Ext.reg('narrowcasting-window-slide-create', Narrowcasting.window.CreateSlide);

Narrowcasting.window.UpdateSlide = function(config) {
    config = config || {};

    Ext.applyIf(config, {
	    width		: 600,
    	autoHeight	: true,
        title 		: _('narrowcasting.slide_update'),
        url			: Narrowcasting.config.connector_url,
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
	        	columnWidth	: .5,
				items : [{
			    	xtype		: 'narrowcasting-combo-slides-types',
			    	fieldLabel	: _('narrowcasting.label_slide_type'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_type_desc'),
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
			    }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_slide_type_desc'),
		            cls			: 'desc-under'
		        }, {
		        	xtype		: 'textfield',
		        	fieldLabel	: _('narrowcasting.label_slide_name'),
		        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_name_desc'),
		        	name		: 'name',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('narrowcasting.label_slide_name_desc'),
		            cls			: 'desc-under'
		        }, {
		        	layout		: 'column',
		        	border		: false,
		            defaults	: {
		                layout		: 'form',
		                labelSeparator : ''
		            },
		        	items		: [{
			        	columnWidth	: .7,
						items : [{
				        	xtype		: 'numberfield',
				        	fieldLabel	: _('narrowcasting.label_slide_time'),
				        	description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_time_desc'),
				        	name		: 'time',
				        	anchor		: '100%',
				        	allowBlank	: false
				        }, {
				        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
				            html		: _('narrowcasting.label_slide_time_desc'),
				            cls			: 'desc-under'
				        }]
			        }, {
				        columnWidth	: .3,
				        style		: 'margin-right: 0;',
						items : [{
					        xtype		: 'checkbox',
					        fieldLabel	: '&nbsp;',
				            description	: MODx.expandHelp ? '' : _('narrowcasting.label_slide_published_desc'),
				            name		: 'published',
				            inputValue	: 1,
				            boxLabel	: _('narrowcasting.label_slide_published')
				        }, {
				        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
				            html		: _('narrowcasting.label_slide_published_desc'),
				            cls			: 'desc-under'
				        }]
					}]
		       }]
			}, {
		        columnWidth	: .5,
		        style		: 'margin-right: 0;',
				items 		: [{
			    	xtype		: 'container',
			    	layout		: 'form',
			    	labelSeparator : '',
			    	id			: 'narrowcasting-window-slide-update-fields',
			    	cls			: 'narrowcasting-slide-options',
			    	items		: [{
				    	html 		: '<i class="icon icon-cog"></i>'
			    	}]
			    }]
			}]
        }]
    });

    Narrowcasting.window.UpdateSlide.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.window.UpdateSlide, MODx.Window, {
	getTypeFields: function(tf, nv, ov) {
		var type = tf.getValue();

		if (undefined != (record = tf.findRecord(tf.valueField, type))) {
			if (undefined != (container = Ext.getCmp('narrowcasting-window-slide-update-fields'))) {
				container.removeAll();

				Ext.iterate(record.data.data, function(name, value) {
                    if (value.xtype === 'textarea') {
                        value.listeners = {
                            'afterrender': {
                                fn: function(data) {
                                    if (typeof TinyMCERTE.Tiny !== 'undefined') {
                                        MODx.loadRTE(data.id);
                                    }
                                }
                            }
                        };
                    }

					container.add(Ext.applyIf(value, {
						xtype 		: 'textarea',
						fieldLabel	: _('narrowcasting.slide_' + type + '_' + name),
						description	: MODx.expandHelp ? '' : _('narrowcasting.slide_' + type + '_' + name + '_desc'),
						name		: 'data_' + name,
						anchor		: '100%',
						allowBlank	: true,
						value 		: this.config.record.data[name]
					}));

					container.add({
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		:  _('narrowcasting.slide_' + type + '_' + name + '_desc'),
			            cls			: 'desc-under'
			        });
				}, this);

				this.doLayout();
			}
		}
	}
});

Ext.reg('narrowcasting-window-slide-update', Narrowcasting.window.UpdateSlide);
