VersionX.grid.Snippets = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		url: VersionX.config.connector_url,
		id: 'versionx-grid-snippets',
		baseParams: {
            action: 'mgr/snippets/getlist',
            snippet: (VersionX.inVersion) ? MODx.request['id'] : 0
        },
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            emptyText: _('versionx.error.noresults')
        },
		fields: [
            {name: 'version_id', type: 'int'},
            {name: 'content_id', type: 'int'},
            {name: 'saved', type: 'string'},
            {name: 'username', type: 'string'},
            {name: 'mode', type: 'string'},
            {name: 'marked', type: 'boolean'},
            {name: 'name', type: 'string'},
            {name: 'category', type: 'int'},
            {name: 'categoryname', type: 'string'}
        ],
        paging: true,
		remoteSort: true,
		columns: [{
			header: _('versionx.version_id'),
			dataIndex: 'version_id',
			sortable: true,
			width: .1
		},{
			header: _('versionx.content_id',{what: _('snippet')}),
			dataIndex: 'content_id',
		    sortable: true,
			width: .1
		},{
			header: _('versionx.saved'),
			dataIndex: 'saved',
			sortable: true,
			width: .2
		},{
			header: _('versionx.content_name', {what: _('snippet')}),
			dataIndex: 'name',
		    sortable: true,
			width: .4
		},{
			header: _('category'),
			dataIndex: 'categoryname',
		    sortable: true,
			width: .2
		},{
			header: _('user'),
			dataIndex: 'username',
		    sortable: true,
			width: .2
		},{
			header: _('versionx.mode'),
			dataIndex: 'mode',
		    sortable: true,
			width: .1,
            renderer: function (val) { return _('versionx.mode.'+val); }
		},{
			header: _('versionx.marked'),
			dataIndex: 'marked',
		    sortable: true,
			width: .1,
            hidden: true
		}]
		,listeners: {}
    });
    VersionX.grid.Snippets.superclass.constructor.call(this,config);
};
Ext.extend(VersionX.grid.Snippets,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var d = r.data;

        var m = [];
        m.push({
            text: _('versionx.menu.viewdetails'),
            handler: function() {
                var eid = d.version_id;
                var backTo = (VersionX.inVersion) ? '&backTo='+MODx.request['a']+'-'+MODx.request['id'] : '';
                window.location.href = '?a='+VersionX.action+'&action=snippet&vid='+eid+backTo;
            }
        },'-',{
            text: _('versionx.snippets.revert', {id: d.version_id}),
            handler: function() {
                this.revertVersion(d.version_id, d.content_id)
            },
            scope: this
        });
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    },

    revertVersion: function(version, content) {
        if (version < 1) { MODx.alert(_('error'), 'Version not properly defined: '+version); }
        MODx.msg.confirm({
            title: _('versionx.snippets.revert.confirm'),
            text: _('versionx.snippets.revert.confirm.text',{id: version}),
            url: VersionX.config.connector_url,
            params: {
                version_id: version,
                content_id: content,
                action: 'mgr/snippets/revert'
            },
            listeners: {
                success: {fn: function() {
                    MODx.msg.status({
                        message: _('versionx.snippets.reverted'),
                        delay: 4
                    });
                }, scope: this }
            }
        });
    }
});
Ext.reg('versionx-grid-snippets',VersionX.grid.Snippets);
