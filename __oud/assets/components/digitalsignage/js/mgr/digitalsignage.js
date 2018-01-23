var DigitalSignage = function(config) {
	config = config || {};
	
	DigitalSignage.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage, Ext.Component, {
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
	        xtype		: 'digitalsignage-window-broadcast-view-calendar',
	        closeAction	: 'close'
        });
        
        this.viewCalendarWindow.show(e.target);
    }
});

Ext.reg('digitalsignage', DigitalSignage);

DigitalSignage = new DigitalSignage();