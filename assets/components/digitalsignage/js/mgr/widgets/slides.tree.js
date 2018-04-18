DigitalSignage.tree.BroadcastSlides = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : DigitalSignage.config.connector_url,
        action      : 'mgr/broadcasts/slides/gettree',
        sortAction  : 'mgr/broadcasts/slides/sort',
        baseParams  : {
            broadcast_id    : config.broadcast
        },
        useArrows   : false,
        cls         : 'fixed-tree',
        root_id     : 'n_root',
        root_name   : _('digitalsignage.selected_slides'),
        root_iconCls : 'icon-play-circle',
        rootVisible : true,
        expandFirst : true,
        collapsible : false,
        animate     : false,
        tbar        : {},
        remoteToolbar : true,
        enableDD    : false,
        ddGroup     : 'digitalsignage-tree-broadcast-slides',
        listeners   : {
            beforeExpandNode : {
                fn          : this.onBeforeExpand,
                scope       : this
            },
            beforeCollapseNode : {
                fn          : this.onBeforeCollapse,
                scope       : this
            },
            beforeNodeDrop : {
                fn          : this.onBeforeDrop,
                scope       : this
            },
            afterSort   : {
                fn          : this.onAfterSort,
                scope       : this
            }
        }
    });

    DigitalSignage.tree.BroadcastSlides.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.tree.BroadcastSlides, MODx.tree.Tree, {
    getMenu: function() {
        if ('n_root' != this.cm.activeNode.id) {
            return [{
                text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('digitalsignage.broadcast_slide_remove'),
                handler : this.removeSlide,
                scope   : this
            }];
        }
    },
    onBeforeExpand: function(event) {
        return true;
    },
    onBeforeCollapse: function(event) {
        return false;
    },
    removeSlide: function(btn, e) {
        var tree = this;

        Ext.Msg.confirm(_('digitalsignage.broadcast_slide_remove'), _('digitalsignage.broadcast_slide_remove_confirm'), function(confirm) {
            MODx.Ajax.request({
                url         : DigitalSignage.config.connector_url,
                params      : {
                    action      : 'mgr/broadcasts/slides/remove',
                    id          : tree.cm.activeNode.attributes.clean_id
                },
                listeners   : {
                    'success'   : {
                        fn          : function () {
                            tree.cm.activeNode.remove();
                        },
                        scope       : this
                    }
                }
            });
        });
    },
    onBeforeDrop: function(event) {
        if (event.dropNode.ownerTree) {
            if (this.id != event.dropNode.ownerTree.id) {
                event.dropNode.ui.removeClass('x-tree-selected');
                event.dropNode.ui.removeClass('x-tree-node-over');

                event.dropNode = new Ext.tree.TreeNode(
                    Ext.apply({}, event.dropNode.attributes)
                );
            }
        }
    },
    onAfterSort: function(data) {
        var tree = this;

        if (data.result.results) {
            Ext.each(data.result.results, function(value) {
                if (undefined !== (node = tree.getNodeById(value.old_id))) {
                    node.id = value.new_id;
                    node.attributes.id = value.new_id;
                    node.attributes.clean_id = value.clean_id;
                }
            });
        } else {
            tree.refresh();
        }
    }
});

Ext.reg('digitalsignage-tree-broadcast-slides', DigitalSignage.tree.BroadcastSlides);

DigitalSignage.tree.AvailableSlides = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : DigitalSignage.config.connector_url,
        action      : 'mgr/slides/gettree',
        baseParams  : {
            broadcast_id : config.broadcast
        },
        useArrows   : false,
        cls         : 'fixed-tree',
        root_id     : 'n_root',
        root_name   : _('digitalsignage.available_slides'),
        root_iconCls: 'icon-play-circle',
        rootVisible : true,
        expandFirst : true,
        collapsible : false,
        animate     : false,
        tbar        : {},
        remoteToolbar : true,
        enableDD    : false,
        ddGroup     : 'digitalsignage-tree-available-slides',
        dragConfig  : {
            ddGroup     : 'digitalsignage-tree-broadcast-slides',
        },
        listeners   : {
            beforeExpandNode : {
                fn          : this.onBeforeExpand,
                scope       : this
            },
            beforeCollapseNode : {
                fn          : this.onBeforeCollapse,
                scope       : this
            }
        }
    });

    DigitalSignage.tree.AvailableSlides.superclass.constructor.call(this, config);
};

Ext.extend(DigitalSignage.tree.AvailableSlides, MODx.tree.Tree, {
    onBeforeExpand: function(event) {
        return true;
    },
    onBeforeCollapse: function(event) {
        return false;
    },
});

Ext.reg('digitalsignage-tree-available-slides', DigitalSignage.tree.AvailableSlides);