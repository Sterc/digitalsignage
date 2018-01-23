# LET OP #
De volgende issues zijn gefixt maar worden nog getest/door ontwikkeld:

* [XS-165](https://stercbv.atlassian.net/browse/XS-165)
* [XS-205](https://stercbv.atlassian.net/browse/XS-205)

# README #
Zorg er voor dat dit bestand niet gedeployed wordt door DeployHQ.

### Project details ###

|                       |                                                                    |
|-----------------------|--------------------------------------------------------------------|
| **Summary**           | Write a summary about this project here.                           |
| **Production**        | https://demo.sterc.com                                             |
| **Project Manager**   | -                                                                  |
| **Lead Developer**    | Oene Tjeerd de Bruin                                               |
| **Developer**         | Oene Tjeerd de Bruin                                               |
| **JIRA Board**        | https://stercbv.atlassian.net/secure/RapidBoard.jspa?rapidView=33&view=detail&selectedIssue=XS-210&quickFilter=264 |
| **Development branch**| development                                                        |

### Subpackages ###

Digital Signage heeft een aan subpackages zoals widgets en slides. Momenteel moet je deze nog zelf kopieren en instellen, de bedoeling is dat er voor deze subpackages ook losse installs komen.

* Digital Signage Social Media (voorheen Facebook Widget)
* Digital Signage Bitbucket (voorheen successrate/commitment leaderboard)

### TinymceWrapper ###

Om TinymceWrapper werkend te krijgen met Digital Signage moet je de volgende TinymceWrapper plugin properties wijzigen:

```
customJS: true
customJSchunks: DigitalSignage
```

Vervolgens maak je een nieuwe chunk aan met de naam 'TinymceWrapperDigitalSignage' en de volgende code:

```
MODx.loadRTE = function(id, config) {
    if (element = Ext.get(id)) {
        if (-1 != element.dom.className.search('digitalsignage-richtext')) {
            tinymce.init(Ext.applyIf({
                selector: '#' + id,
                [[$TinymceWrapperCommonCode]],
                setup: function(editor) {
                    editor.on('init', function(e) {
                        e.target.save();
                    }).on('change', function(e) {
                        e.target.save();
                    });
                }
            }, config))
        }
    }
};
```