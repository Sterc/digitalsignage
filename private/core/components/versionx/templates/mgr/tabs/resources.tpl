<script type="text/javascript">
    MODx.on("ready",function() {
        MODx.addTab("modx-resource-tabs",{
            title: _('versionx.tabheader'),
            id: 'versionx-resource-tab',
            width: '95%',
            items: [{
                xtype: 'versionx-panel-resources',
                //layout: 'anchor',
                width: 500
            },{
                html: '<hr />',
                width: '95%'
            },{
                layout: 'anchor',
                anchor: '1',
                items: [{
                    xtype: 'versionx-grid-resources'
                }]
            }]
        });
    });
</script>
