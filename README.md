# LET OP #
Ik heb heel wat features + BUG fixes door gevoerd, maar heb nog geen tijd gehad om een goede install te maken. De bestanden in deze repo zijn is de meest recente Digital Signage versie maar hier is nog geen package install install van. Op https://demo.sterc.com draait momenteel deze nieuwste Digital Signage versie. Zodra ik een nieuw taakje heb zal ik een nieuwe package install maken.

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
    }, config));
};
```