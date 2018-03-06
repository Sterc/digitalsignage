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

### Installatie ###

Na dat Digital Signage package geinstalleerd is zou ik de aangemaakte 'Digital Signage (1.1.3-pl original)' template kopieren en hernoemen, zodat de template niet overschreven wordt tijdens een nieuwe install of update. Let op dat je de nieuwe template wel goed insteld bij de systeem instellingen. Doe dit ook voor alle front-end bestanden, deze zullen tijdens een nieuwe install of update overschreven worden.

### Subpackages ###

Digital Signage heeft een aan subpackages zoals widgets en slides. Momenteel moet je deze nog zelf kopieren en instellen, de bedoeling is dat er voor deze subpackages ook losse installs komen.

* Digital Signage Social Media (voorheen Facebook Widget, hier is een losse install van)
* Digital Signage Bitbucket (voorheen successrate/commitment leaderboard, hier is nog geen losse install van)

### Digital Signage Social Media ###

Installeer de Social Media widget (digitalsignagesocialmedia-1.0.0-pl.transport.zip), en stel de systeemstellingen in met de juiste API keys. Vervolgens zie je in de 'assets/components/digitalsignagesocialmedia/' de core front-end bestanden van de Social Media widget:

* socialmedia.plugin.tpl, voeg deze HTML code toe aan de Digital Signage template.
* socialmedia.plugin.css, voeg dit CSS bestand toe aan de head van de Digital Signage template.
* socialmedia.plugin.js, voeg dit JavaScript bestand toe bij de rest van de JavaScript bestanden (voor de digitalsignage.js).

### Digital Signage lexicons ###

Om de standaard Digital Signage lexicons te overschrijven kun je de onderstaande functie gebruiken (na de digitalsignage.js).

```
<script type="text/javascript">
    $.extend($.fn.DigitalSignage.lexicons, {
        prevSlide : 'Volgende slide',
        nextSlide : 'Vorige slide'
    });
</script>
```

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