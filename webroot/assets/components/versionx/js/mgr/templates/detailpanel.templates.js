VersionX.panel.TemplatesDetail.Main = function(config) {
    config = config || {};
    config.id = config.id || 'versionx-panel-templatesdetail';
    Ext.apply(config,{
        border: false,
        layout: 'form',
        items: [{
            html: '<p>'+_('versionx.templates.detail.text')+'</p>',
            border: false,
            bodyCssClass: 'panel-desc'
        },{
            layout: 'form',
            cls: 'main-wrapper',
            items: [{
                layout: 'hbox',
                border: false,
                items: [{
                    xtype: 'versionx-combo-templateversions',
                    emptyText: _('versionx.compare_to'),
                    labelStyle: 'padding: 7px 0 0 5px;',
                    name: 'compare_to',
                    baseParams: {
                        template: (VersionX.record) ? VersionX.record['content_id'] : 0,
                        current: (VersionX.record) ? VersionX.record['version_id'] : 0,
                        action: 'mgr/templates/get_versions'
                    },
                    listeners: {
                        'select': this.compareVersion
                    }
                },{html: '&nbsp;', border: false, bodyStyle: 'margin-left: 10px;'},{
                    xtype: 'button',
                    text: _('versionx.templates.revert.options'),
                    handler: (VersionX.record && VersionX.cmrecord) ? Ext.emptyFn : function() {
                        this.revertVersion((VersionX.record) ? VersionX.record['version_id'] : 0);
                    },
                    scope: this,
                    menu: (VersionX.record && VersionX.cmrecord) ?
                        [{
                            text: _('versionx.templates.revert',{id: VersionX.record['version_id']}),
                            handler: function() {
                                this.revertVersion((VersionX.record) ? VersionX.record['version_id'] : 0);
                            },
                            scope: this
                        },{
                            text: _('versionx.templates.revert',{id: VersionX.cmrecord['version_id']}),
                            handler: function() {
                                this.revertVersion((VersionX.cmrecord) ? VersionX.cmrecord['version_id'] : 0);
                            },
                            scope: this
                        }] : undefined
                }]
            },{
                xtype: 'panel',
                bodyStyle: 'height: 12px',
                border: false
            },{
                xtype: 'modx-tabs',
                bodyStyle: 'padding: 15px;',
                width: '98%',
                border: true,
                defaults: {
                    border: false,
                    autoHeight: true,
                    defaults: {
                        border: false
                    }
                },
                items: [{
                    title: _('versionx.common.version-details'),
                    items: [{
                        id: 'versionx-panel-templatesdetail-versioninfo',
                        xtype: 'versionx-panel-common-detailpanel',
                        vxRecord: config.vxRecord,
                        vxRecordCmp: config.vxRecordCmp ? config.vxRecordCmp : undefined,
                        vxFieldMap: [
                            { key: 'version_id', lexicon:'versionx.version_id' },
                            { key: 'user', lexicon:'user' },
                            { key: 'saved', lexicon:'versionx.saved' },
                            { key: 'mode', lexicon:'versionx.mode' }
                        ]
                    }]
                },{
                    title: _('versionx.common.fields'),
                    items: [{
                        id: 'versionx-panel-templatesdetail-template-fields',
                        xtype: 'versionx-panel-common-detailpanel',
                        vxRecord: config.vxRecord,
                        vxRecordCmp: config.vxRecordCmp ? config.vxRecordCmp : undefined,
                        vxFieldMap: [
                            { key: 'templatename', lexicon:'name' },
                            { key: 'description', lexicon:'description' },
                            { key: 'category', lexicon:'category' }
                        ]
                    }]
                },{
                    title: _('versionx.common.content'),
                    items: [{
                        id: 'versionx-panel-templatesdetail-content',
                        xtype: 'versionx-panel-common-contentpanel',
                        border: false,
                        vxRecord: config.vxRecord,
                        vxRecordCmp: config.vxRecordCmp ? config.vxRecordCmp : undefined,
                        vxContentField: 'content'
                    }]
                },{
                    title: _('versionx.common.properties'),
                    tabTip: _('versionx.common.properties.off'),
                    items: [],
                    disabled: true
                }],
                stateful: true,
                stateId: config.id,
                stateEvents: ['tabchange'],
                getState: function() {
                    return { activeTab:this.items.indexOf(this.getActiveTab()) };
                }
            }]
        }],
        listeners: {
        }
    });
    VersionX.panel.TemplatesDetail.Main.superclass.constructor.call(this,config);
};
Ext.extend(VersionX.panel.TemplatesDetail.Main,MODx.FormPanel,{
    compareVersion: function (tf) {
        var cmid = tf.getValue();
        var backTo = (MODx.request.backTo) ? '&backTo='+MODx.request.backTo : '';
        window.location.href = '?a='+VersionX.action+'&action=template&vid='+MODx.request['vid']+'&cmid='+cmid+backTo;
    },

    revertVersion: function(version) {
        if (version < 1) { MODx.alert(_('error'),'Version not properly defined: '+version); }
        MODx.msg.confirm({
            title: _('versionx.templates.revert.confirm'),
            text: _('versionx.templates.revert.confirm.text',{id: version}),
            url: VersionX.config.connector_url,
            params: {
                version_id: version,
                content_id: VersionX.record.content_id,
                action: 'mgr/templates/revert'
            },
            listeners: {
                success: {fn: function() {
                    MODx.msg.status({
                        message: _('versionx.templates.reverted'),
                        delay: 4
                    });
                }, scope: this }
            }
        });
    }
});
Ext.reg('versionx-panel-templatesdetail',VersionX.panel.TemplatesDetail.Main);
