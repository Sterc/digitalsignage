Narrowcasting.panel.Admin = function(config) {
	config = config || {};
	
	Ext.apply(config, {
		id			: 'narrowcasting-panel-admin',
		cls			: 'container',
		defaults	: {
			collapsible	: false,
			autoHeight	: true,
			autoWidth	: true,
			border		: false
		},
		items		: [{
			html		: '<h2>'+_('narrowcasting')+'</h2>',
			id			: 'narrowcasting-header',
			cls			: 'modx-page-header'
		}, {
			xtype		: 'modx-tabs',
			items		: [{
				layout		: 'form',
				title		: _('narrowcasting.slide_types'),
				defaults	: {
					autoHeight	: true,
					autoWidth	: true,
					border		: false
				},
				items		: [{
					html			: '<p>'+_('narrowcasting.slide_types_desc')+'</p>',
					bodyCssClass	: 'panel-desc'
				}, {
					xtype			: 'narrowcasting-grid-slide-types',
					cls				: 'main-wrapper',
					preventRender	: true
				}]
			}]
		}]
	});

	Narrowcasting.panel.Admin.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.panel.Admin, MODx.FormPanel);

Ext.reg('narrowcasting-panel-admin', Narrowcasting.panel.Admin);