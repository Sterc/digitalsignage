DigitalSignage.panel.Admin = function(config) {
	config = config || {};
	
	Ext.apply(config, {
		id			: 'digitalsignage-panel-admin',
		cls			: 'container',
		defaults	: {
			collapsible	: false,
			autoHeight	: true,
			autoWidth	: true,
			border		: false
		},
		items		: [{
			html		: '<h2>'+_('digitalsignage')+'</h2>',
			id			: 'digitalsignage-header',
			cls			: 'modx-page-header'
		}, {
			xtype		: 'modx-tabs',
			items		: [{
				layout		: 'form',
				title		: _('digitalsignage.slide_types'),
				defaults	: {
					autoHeight	: true,
					autoWidth	: true,
					border		: false
				},
				items		: [{
					html			: '<p>'+_('digitalsignage.slide_types_desc')+'</p>',
					bodyCssClass	: 'panel-desc'
				}, {
					xtype			: 'digitalsignage-grid-slide-types',
					cls				: 'main-wrapper',
					preventRender	: true
				}]
			}]
		}]
	});

	DigitalSignage.panel.Admin.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.panel.Admin, MODx.FormPanel);

Ext.reg('digitalsignage-panel-admin', DigitalSignage.panel.Admin);