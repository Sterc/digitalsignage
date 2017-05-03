
userManagement.grid.User = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        url: userManagement.config.connector_url
        ,baseParams: {
            action: 'mgr/user/getList'
            ,usergroup: MODx.request['usergroup'] ? MODx.request['usergroup'] : ''
        }
        ,fields: ['id','username','fullname','email','gender','blocked','role','active','cls']
        ,paging: true
        ,remoteSort: true
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec){
                return rec.data.active ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
        ,columns: [{
            header: _('username')
            ,dataIndex: 'username'
            ,width: 150
            ,sortable: true
            ,id: 'main'
            ,renderer: {
                fn: this.mainTitleRenderer,
                scope: this
            }
        },{
            header: _('user_full_name')
            ,dataIndex: 'fullname'
            ,width: 180
            ,sortable: true
            ,renderer: Ext.util.Format.htmlEncode
        },{
            header: _('email')
            ,dataIndex: 'email'
            ,width: 180
            ,sortable: true
        },{
            header: _('active')
            ,dataIndex: 'active'
            ,width: 80
            ,sortable: true
            ,renderer: this.rendYesNo
        }/*,{
            header: _('usermanagement.user_block')
            ,dataIndex: 'blocked'
            ,width: 80
            ,sortable: true
            ,renderer: this.rendYesNo
        }*/]
        ,tbar: [{
            text: _('user_new')
            ,handler: this.createUser
            ,scope: this
            ,cls:'primary-button'
        },'->',{
            xtype: 'modx-combo-usergroup'
            ,name: 'usergroup'
            ,id: 'modx-user-filter-usergroup'
            ,itemId: 'usergroup'
            ,emptyText: _('user_group')+'...'
            ,url: userManagement.config.connector_url
            ,baseParams: {
                action: 'mgr/group/getList'
                ,addAll: true
                ,exclude: 1
            }
            ,value: MODx.request['usergroup'] ? MODx.request['usergroup'] : ''
            ,width: 200
            ,listeners: {
                'select': {fn:this.filterUsergroup,scope:this}
            }
        },{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-user-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    userManagement.grid.User.superclass.constructor.call(this,config);
};
Ext.extend(userManagement.grid.User,MODx.grid.Grid,{
    mainTitleRenderer: function(value, metaData, record, rowIndex, colIndex, store) {
        var tpl = new Ext.XTemplate('<tpl for=".">' + '<h3>{name} ({id})</h3>' + '<tpl if="actions !== null">' + '<ul class="actions">' + '<tpl for="actions">' + '<li><button type="button" class="controlBtn {className}">{text}</button></li>' + '</tpl>' + '</ul>' + '</tpl>' + '</tpl>', {
            compiled: true
        });

        var values = {
            id: record.id,
            name: value,
        };
        var h = [];

        h.push({
            className: 'update green',
            text: _('update')
        });
        h.push({
            className: 'duplicate',
            text: _('duplicate')
        });
        h.push({
            className: 'delete red',
            text: _('delete')
        });
        values.actions = h;
        return tpl.apply(values);
    },
    onClick: function(e) {

        var t = e.getTarget();
        var elm = t.className.split(' ')[0];
        if (elm == 'controlBtn') {
        var act = t.className.split(' ')[1];
        var record = this.getSelectionModel()
        .getSelected();
        this.menu.record = record.data;
        switch (act) {
            case 'update':
                MODx.loadPage(MODx.request.a, "&action=update&id=" + record.data.id);
            break;

            case 'duplicate':
                this.duplicateUser(record, e);
            break;

            case 'delete':
                this.removeUser(record, e);
            break;

            case 'password':

            break;
            default:
            break;
            }
        }
    }

    ,createUser: function() {
        MODx.loadPage(MODx.request.a, "action=update");
    }
    ,removeUser: function() {
        MODx.msg.confirm({
            title: _('user_remove')
            ,text: _('user_confirm_remove')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'security/user/delete'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,duplicateUser: function() {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'security/user/duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(){
                    Ext.Msg.alert(_('user_duplicate'), _('usermanagement.user_duplicate_msg'));

                    this.refresh();
                },scope:this}
            }
        });
    }

    ,filterUsergroup: function(cb,nv,ov) {
        this.getStore().baseParams.usergroup = Ext.isEmpty(nv) || Ext.isObject(nv) ? cb.getValue() : nv;
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    }
    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        console.log(this.getStore().baseParams);
        this.getBottomToolbar().changePage(1);
        return true;
    }
    ,clearFilter: function() {
        this.getStore().baseParams = {
            action: 'mgr/user/getList'
        };
        Ext.getCmp('modx-user-search').reset();
        Ext.getCmp('modx-user-filter-usergroup').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('usermanagement-grid-users',userManagement.grid.User);