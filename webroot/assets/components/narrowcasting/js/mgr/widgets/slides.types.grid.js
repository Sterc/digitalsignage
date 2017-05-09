Narrowcasting.combo.SlidesTypes = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        url			: Narrowcasting.config.connector_url,
        baseParams 	: {
            action		: 'mgr/slides/types/getnodes'
        },
        fields		: ['key', 'name', 'description', 'data'],
        hiddenName	: 'type',
        pageSize	: 15,
        valueField	: 'key',
        displayField: 'name',
        tpl			: new Ext.XTemplate('<tpl for=".">' + 
        	'<div class="x-combo-list-item">' + 
        		'<span style="font-weight: bold">{name}</span><br />{description}' + 
			'</div>' + 
		'</tpl>')
    });
    
    Narrowcasting.combo.SlidesTypes.superclass.constructor.call(this,config);
};

Ext.extend(Narrowcasting.combo.SlidesTypes, MODx.combo.ComboBox);

Ext.reg('narrowcasting-combo-slides-types', Narrowcasting.combo.SlidesTypes);