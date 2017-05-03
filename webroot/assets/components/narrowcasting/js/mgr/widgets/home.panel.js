Narrowcasting.panel.Home = function(config) {
	config = config || {};
	
	Ext.apply(config, {
		id			: 'narrowcasting-panel-home',
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
				title		: _('narrowcasting.broadcasts'),
				defaults	: {
					autoHeight	: true,
					autoWidth	: true,
					border		: false
				},
				items		: [{
					html			: '<p>'+_('narrowcasting.broadcasts_desc')+'</p>',
					bodyCssClass	: 'panel-desc'
				}, {
					xtype			: 'narrowcasting-grid-broadcasts',
					cls				: 'main-wrapper',
					preventRender	: true
				}]
			}, {
				layout		: 'form',
				title		: _('narrowcasting.slides'),
				defaults	: {
					autoHeight	: true,
					autoWidth	: true,
					border		: false
				},
				items		: [{
					html			: '<p>'+_('narrowcasting.slides_desc')+'</p>',
					bodyCssClass	: 'panel-desc'
				}/*, {
					xtype			: 'narrowcasting-grid-slides',
					cls				: 'main-wrapper',
					preventRender	: true
				}*/]
			}, {
				layout		: 'form',
				title		: _('narrowcasting.players'),
				defaults	: {
					autoHeight	: true,
					autoWidth	: true,
					border		: false
				},
				items		: [{
					html			: '<p>'+_('narrowcasting.players_desc')+'</p>',
					bodyCssClass	: 'panel-desc'
				}, {
					xtype			: 'narrowcasting-grid-players',
					cls				: 'main-wrapper',
					preventRender	: true,
					refreshGrid		: ['narrowcasting-grid-broadcasts']
				}]
			}]
		}]
	});

	Narrowcasting.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting.panel.Home, MODx.FormPanel);

Ext.reg('narrowcasting-panel-home', Narrowcasting.panel.Home);