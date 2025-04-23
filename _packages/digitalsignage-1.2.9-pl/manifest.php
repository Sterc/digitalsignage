<?php return array (
  'manifest-version' => '1.1',
  'manifest-attributes' => 
  array (
    'readme' => '# MODX Digital Signage

![Poi version](https://img.shields.io/badge/version-1.2.0-red.svg) ![MODX Extra by Sterc](https://img.shields.io/badge/checked%20by-Oetzie-blue.svg) ![MODX version requirements](https://img.shields.io/badge/modx%20version%20requirement-2.4%2B-brightgreen.svg)

## System settings

| Setting                    | Omschrijving                                                                 |
|----------------------------|------------------------------------------------------------------------------|
| `digitalsignage.auto_create_sync` | Standaard is `Ja`. |
| `digitalsignage.context`    | De context van de Digital Signage, deze wordt automatisch goed ingesteld tijdens de installatie. Standaars is `ds`. |
| `digitalsignage.export_feed_resource` | De ID van de pagina die als export pagina dient voor de feeds, deze word automatisch goed ingesteld tijdens de installatie. |
| `digitalsignage.export_resource` | De ID van de pagina die als export pagina dient, deze word automatisch goed ingesteld tijdens de installatie. |
| `digitalsignage.media_source` | De media source die gebruikt wordt voor de slides. |
| `digitalsignage.request_param_broadcast` | De URL parameter van een uitzending. Standaard is `bc`. |
| `digitalsignage.request_param_player` | De URL parameter van een mediaspeler. Standaard is `pl`. |
| `digitalsignage.request_resource` | De ID van de pagina die als start pagina gebruikt word voor de Digital Signage context. |
| `digitalsignage.templates` | De ID van de template voor uitzendingen, meerdere ID\'s scheiden met een komma. |

## Permissions

| Setting                    | Omschrijving                                                                 |
|----------------------------|------------------------------------------------------------------------------|
| `digitalsignage`             | De algemene Digital Signage permission, zonder deze permission kan de gebruker het component niet gebruiken. |
| `digitalsignage_admin`       | De permission om bij de Digital Signage admin gedeelte te komen. In deze admin gedeelte kunnen de slide types ingesteld worden voor de slides. |

## Installatie

Voor elk nieuw Digital Signage project kopieer je de default template `Digital Signage (1.2.0-pl original)`, deze template word namelijk elke keer overschreven tijdens een nieuwe install of update. Vergeet de nieuwe template niet in te stellen bij de `digitalsignage.templates` system setting.
Doe dit ook voor alle front-end bestanden (CSS en afbeeldingen), deze worden namelijk ook elke keer overschreven tijdens een nieuwe install of update. Verplaats bijvoorbeeld de `/digitalsignage/assets/interface/css/` en `/digitalsignage/assets/interface/images/` naar een map in de `assets` map (doe dit ook voor alle nieuwe plug-ins en slides die je aan maakt). De officiële Javascript bestanden kunnen wel op de originele locatie blijven bestaan. Vergeet niet om de paden in de template aan te passen naar de nieuwe locatie.  

De 1.2.0-pl versie is beveiligd mbv modStore, als je hem lokaal wilt testen of uploaden dien je hem handmatig in de database te koppelen aan de modStore provider en een juiste key.

## Digital Signage lexicons

Om de standaard Digital Signage lexicons te overschrijven kun je de onderstaande functie gebruiken (na de digitalsignage.js).

``` js
<script type="text/javascript">
    $.extend($.fn.DigitalSignage.lexicons, {
        prevSlide : \'Volgende slide\',
        nextSlide : \'Vorige slide\'
    });
</script>
```

## Intel PC Stick (windows 10)

Om de Digital Signage in te stellen op een Intel PC Stick kun je een het volgende doen:

Maak een snel koppeling aan met onderstaande `locatie`:

``` sh
"C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe" -kiosk --disable-popup-blocking --disable-infobars --disable-session-crashed-bubble --remote-debugging-port=9222 "URL VAN DE MEDIASPELER"
```

En plaats deze in de onderstaande map:

``` sh
C:\\Gebruikers\\Gebruiker\\AppData\\Roaming\\Microsoft\\Windows\\Menu Start\\Programma\'s\\Opstarten\\
```

Werkt de bovenstaande map niet dan kun je de onderstaande map proberen:

``` sh
C:\\ProgramData\\Microsoft\\Windows\\Start Menu\\Programs\\Opstarten\\
```

## Onderstaande is out dated

### Subpackages

Digital Signage heeft een aan subpackages zoals widgets en slides. Momenteel moet je deze nog zelf kopieren en instellen, de bedoeling is dat er voor deze subpackages ook losse installs komen.

* Digital Signage Social Media (voorheen Facebook Widget, hier is een losse install van)
* Digital Signage Bitbucket (voorheen successrate/commitment leaderboard, hier is nog geen losse install van)

### Digital Signage Social Media

Installeer de Social Media widget (digitalsignagesocialmedia-1.0.0-pl.transport.zip), en stel de systeemstellingen in met de juiste API keys. Vervolgens zie je in de \'assets/components/digitalsignagesocialmedia/\' de core front-end bestanden van de Social Media widget:

* socialmedia.plugin.tpl, voeg deze HTML code toe aan de Digital Signage template.
* socialmedia.plugin.css, voeg dit CSS bestand toe aan de head van de Digital Signage template.
* socialmedia.plugin.js, voeg dit JavaScript bestand toe bij de rest van de JavaScript bestanden (voor de digitalsignage.js).

### TinymceWrapper

Om TinymceWrapper werkend te krijgen met Digital Signage moet je de volgende TinymceWrapper plugin properties wijzigen:

```
customJS: true
customJSchunks: DigitalSignage
```

Vervolgens maak je een nieuwe chunk aan met de naam \'TinymceWrapperDigitalSignage\' en de volgende code:

``` js
MODx.loadRTE = function(id, config) {
    if (element = Ext.get(id)) {
        if (-1 != element.dom.className.search(\'digitalsignage-richtext\')) {
            tinymce.init(Ext.applyIf({
                selector: \'#\' + id,
                [[$TinymceWrapperCommonCode]],
                setup: function(editor) {
                    editor.on(\'init\', function(e) {
                        e.target.save();
                    }).on(\'change\', function(e) {
                        e.target.save();
                    });
                }
            }, config))
        }
    }
};
```
',
    'setup-options' => 'digitalsignage-1.2.9-pl/setup-options.php',
  ),
  'manifest-vehicles' => 
  array (
    0 => 
    array (
      'vehicle_package' => '',
      'vehicle_class' => 'xPDO\\Transport\\xPDOObjectVehicle',
      'class' => 'MODX\\Revolution\\modNamespace',
      'guid' => '38dcd2fbb4f34b680b7dc5c2d1d7c977',
      'native_key' => 'digitalsignage',
      'filename' => 'MODX/Revolution/modNamespace/f9ba2492635413425a68a11effba832b.vehicle',
    ),
    1 => 
    array (
      'vehicle_package' => '',
      'vehicle_class' => 'xPDO\\Transport\\xPDOScriptVehicle',
      'class' => 'xPDO\\Transport\\xPDOScriptVehicle',
      'guid' => '04f4dd9d902fb4934dfae3547ac06d10',
      'native_key' => '04f4dd9d902fb4934dfae3547ac06d10',
      'filename' => 'xPDO/Transport/xPDOScriptVehicle/da058961a7bc48d539a67234394d3404.vehicle',
    ),
    2 => 
    array (
      'vehicle_package' => '',
      'vehicle_class' => 'xPDO\\Transport\\xPDOObjectVehicle',
      'class' => 'MODX\\Revolution\\modCategory',
      'guid' => 'ad69103992a6cb72942f2be693ada047',
      'native_key' => NULL,
      'filename' => 'MODX/Revolution/modCategory/195d6e47857581b20b957018a9ef060d.vehicle',
    ),
  ),
);