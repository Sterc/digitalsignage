Narrowcasting.tree.BroadcastSlides = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
	    url			: Narrowcasting.config.connector_url,
	    action		: 'mgr/broadcasts/slides/gettree',
	    sortAction	: 'mgr/broadcasts/slides/sort',
	    baseParams	: {
		    broadcast_id : config.broadcast || null
	    },
        cls			: 'fixed-tree',
        root_id		: 'n_root',
        root_name	: _('narrowcasting.selected_slides'),
        rootVisible	: true,
        expandFirst	: true,
        enableDD	: false,
        root_iconCls: 'icon-play-circle',
        ddGroup		: 'narrowcasting-tree-broadcast-slides',
        listeners	: {
	        'beforeNodeDrop': {
		        fn 			: this.beforeDrop,
		        scope 		: this
	        },
	        'afterSort'	: {
		        fn 			: this.afterSort,
		        scope 		: this
	        }
        }
    });
    
    Narrowcasting.tree.BroadcastSlides.superclass.constructor.call(this,config);
};

Ext.extend(Narrowcasting.tree.BroadcastSlides, MODx.tree.Tree, {
	getMenu: function() {
		return [{
			text	: _('narrowcasting.broadcast_slide_remove'),
			handler : this.removeSlide,
			scope 	: this
		}]
	},
	removeSlide: function(btn, e) {
		MODx.Ajax.request({
            url			: Narrowcasting.config.connector_url,
            params		: {
                action 		: 'mgr/broadcasts/slides/remove',
                id			: this.cm.activeNode.attributes.data.c_id
            },
            listeners	: {
                'success'	: {
	                fn			: function() {
		                this.cm.activeNode.remove();
		            },
					scope 		: this
                }
            }
        });
	},
	beforeDrop: function(event) {
		if (this.id != event.dropNode.ownerTree.id) {
			event.dropNode.ui.removeClass('x-tree-selected');
		    event.dropNode.ui.removeClass('x-tree-node-over');
		        
			event.dropNode = new Ext.tree.TreeNode(
				Ext.apply({}, event.dropNode.attributes)
			);
		}
	},
	afterSort: function(data) {     
		Ext.iterate(data.result.results, function(key, value) {
			if (undefined !== (node = this.getNodeById(key))) {
				node.id = value.id;
				node.attributes.id = value.id;
				node.attributes.data = value.data;
			}
		}, this);
	}
});

Ext.reg('narrowcasting-tree-broadcast-slides', Narrowcasting.tree.BroadcastSlides);

Narrowcasting.tree.AvailableSlides = function(config) {
    config = config || {};

    Ext.applyIf(config, {
	    url			: Narrowcasting.config.connector_url,
	    action		: 'mgr/slides/gettree',
	    baseParams	: {
		    broadcast_id : config.broadcast || null
	    },
		cls			: 'fixed-tree',
        root_id		: 'n_root',
        root_name	: _('narrowcasting.available_slides'),
        rootVisible	: true,
        expandFirst	: true,
        enableDD	: false,
        root_iconCls: 'icon-play-circle',
        ddGroup		: 'narrowcasting-tree-available-slides',
        dragConfig : {
            ddGroup : 'narrowcasting-tree-broadcast-slides',
        }
    });
    
    Narrowcasting.tree.AvailableSlides.superclass.constructor.call(this,config);
};

Ext.extend(Narrowcasting.tree.AvailableSlides, MODx.tree.Tree);

Ext.reg('narrowcasting-tree-available-slides', Narrowcasting.tree.AvailableSlides);