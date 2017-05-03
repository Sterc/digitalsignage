var Narrowcasting = function(config) {
	config = config || {};
	
	Narrowcasting.superclass.constructor.call(this, config);
};

Ext.extend(Narrowcasting, Ext.Component, {
	page	: {},
	window	: {},
	grid	: {},
	tree	: {},
	panel	: {},
	combo	: {},
	config	: {},
	viewCalendar: function(btn, e) {
        if (this.viewCalendarWindow) {
	        this.viewCalendarWindow.destroy();
        }
        
        this.viewCalendarWindow = MODx.load({
	        modal		: true,
	        xtype		: 'narrowcasting-window-broadcast-view-calendar',
	        closeAction	: 'close'
        });
        
        this.viewCalendarWindow.show(e.target);
    }
});

Ext.reg('narrowcasting', Narrowcasting);

Narrowcasting = new Narrowcasting();