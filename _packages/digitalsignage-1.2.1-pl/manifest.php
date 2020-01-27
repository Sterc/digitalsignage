<?php return array (
  'manifest-version' => '1.1',
  'manifest-attributes' => 
  array (
    'readme' => '----------------------
DigitalSignage
----------------------
Version: 1.2.0
Author: Sterc
Contact: modx@sterc.nl
----------------------

## DigitalSignage
A different way to communicatie a message towards your target group is through digitalsignage. Do you know those screens at hospitals, schools and town halls which display information like the weather, sales or waiting time? Thát\'s digitalsignage! Its purpose is to serve the target group with short, informative messages. It\'s mostly used in areas where people have to wait, for example: in front of an elevator, in waiting-rooms or at an entrance.

Sterc (https://www.sterc.com) introduced this MODX Extra, it will be possible to set up a digitalsignage system in your good old MODX installation. It lets you define/manage broadcasts, slides and players. Why should you want this? As a developer, you can offer a whole new product next to your regular websites and applications, which means: a whole new market!

## Installation
1. Install the Extra on your MODX website.
2. Setup the right permissions for the users (digitalsignage and digitalsignage_admin).
3. Setup the right permissions for the digitalsignage context.
4. Make a context switch for the digitalsignage context.

When you get a JSON output in the front-end instead of the broadcast, refresh the URI\'s and try again.

## Requirements
* MODX version 2.5.0 or newer has to be installed.

## Bugs and feature requests
We greatly value your feedback, feature requests and bug reports. Please issue them on GitHub: https://github.com/Sterc/DigitalSignage/issues/new.
',
    'changelog' => '----------------------
DigitalSignage
----------------------

----------------------
Version: 1.2.0
Released: 2019-09-06
----------------------
- Bug fixes
    - Friendly URL bug
- New editor system settings
- New default template
- New slide types
    - Countdown
    - Analog clock
    - iFrame

----------------------
Version: 1.1.4
Released: 2018-05-09
----------------------
- Bug fixes

----------------------
Version: 1.1.3
Released: 2018-01-23
----------------------
- Bug fixes

----------------------
Version: 1.1.2
Released: 2017-10-24
----------------------
- Allow img tags in content field
- Bug fixes

----------------------
Version: 1.1.1
Released: 2017-09-26
----------------------
- Bug fixes

----------------------
Version: 1.1.0
Released: 2017-08-25
----------------------
- Bug fixes
- New functions
    - Player restart
    - Player synchronisation time
 - New slide
    - Buienradar slide (Netherlands only)

----------------------
Version: 1.1.0
Released: 2017-07-20
----------------------
- First release
',
    'setup-options' => 'digitalsignage-1.2.1-pl/setup-options.php',
  ),
  'manifest-vehicles' => 
  array (
    0 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modNamespace',
      'guid' => '5ed76a6300abe9ddb1530d240adda289',
      'native_key' => 'digitalsignage',
      'filename' => 'modNamespace/81a9c36f7da6cdc5a9cb706f77e61fc5.vehicle',
      'namespace' => 'digitalsignage',
    ),
    1 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'cec32b18617bc9152f9e76a8f2fd35fe',
      'native_key' => 'digitalsignage.branding_url',
      'filename' => 'modSystemSetting/9cce73163a622cf2280c6d313aa14898.vehicle',
      'namespace' => 'digitalsignage',
    ),
    2 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'e7bcc2b328ded7a5ebf82a9d4a9c90a8',
      'native_key' => 'digitalsignage.branding_url_help',
      'filename' => 'modSystemSetting/bc877392b5dbeb4ce2ec255d8d07f272.vehicle',
      'namespace' => 'digitalsignage',
    ),
    3 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '61512a783c9fdbdc76689d16250f4aac',
      'native_key' => 'digitalsignage.auto_create_sync',
      'filename' => 'modSystemSetting/cf9099e33289ad2e2e0375d938a634a7.vehicle',
      'namespace' => 'digitalsignage',
    ),
    4 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '99228881be2a31ad875cdeef0b70cecd',
      'native_key' => 'digitalsignage.context',
      'filename' => 'modSystemSetting/9cbca85886c29f06f999d348642d7b69.vehicle',
      'namespace' => 'digitalsignage',
    ),
    5 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '52162d78e53d3a8a80bcfe06273c8a27',
      'native_key' => 'digitalsignage.export_feed_resource',
      'filename' => 'modSystemSetting/476c394238bcb8d2d2d5900f96868de8.vehicle',
      'namespace' => 'digitalsignage',
    ),
    6 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '32a4f9ea1eee181108ae6783afac6c0b',
      'native_key' => 'digitalsignage.export_resource',
      'filename' => 'modSystemSetting/bf2c6b8e55785d0f4e244448b4d67b83.vehicle',
      'namespace' => 'digitalsignage',
    ),
    7 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'd4a8b64101321870c2106785f7ed307e',
      'native_key' => 'digitalsignage.media_source',
      'filename' => 'modSystemSetting/595b710e945af4b8b573c074f0c0b23c.vehicle',
      'namespace' => 'digitalsignage',
    ),
    8 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'af9a6743a289c91378ec1c62121f559b',
      'native_key' => 'digitalsignage.request_param_broadcast',
      'filename' => 'modSystemSetting/ae763ff4e98327849703a504baceb57c.vehicle',
      'namespace' => 'digitalsignage',
    ),
    9 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'bb5c3a1da953bce2268b0d9319a273e5',
      'native_key' => 'digitalsignage.request_param_player',
      'filename' => 'modSystemSetting/e68ae35af575eae4acc5093c54eab0a7.vehicle',
      'namespace' => 'digitalsignage',
    ),
    10 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '8399d63557d432414ce74d9196a09b03',
      'native_key' => 'digitalsignage.request_resource',
      'filename' => 'modSystemSetting/857cdb3e07b13639303a3a6a2cee8271.vehicle',
      'namespace' => 'digitalsignage',
    ),
    11 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'b07b7b44e64b32da129d491f8eb860bd',
      'native_key' => 'digitalsignage.templates',
      'filename' => 'modSystemSetting/6d995e16a8c50521d404349b750e77cd.vehicle',
      'namespace' => 'digitalsignage',
    ),
    12 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '8b0facbfaa63247f08139bfe72140cd0',
      'native_key' => 'digitalsignage.editor_menubar',
      'filename' => 'modSystemSetting/655e898212b959367757660f3315d1f4.vehicle',
      'namespace' => 'digitalsignage',
    ),
    13 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '34ec05cb95dca2ec4f31ff8303fdc42f',
      'native_key' => 'digitalsignage.editor_plugins',
      'filename' => 'modSystemSetting/eb14c23a7e2439a6ee07ea101bad6b1e.vehicle',
      'namespace' => 'digitalsignage',
    ),
    14 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'd0c3b07c0728a000bd7a86d1366abe1c',
      'native_key' => 'digitalsignage.editor_statusbar',
      'filename' => 'modSystemSetting/8c7ce163cf8b243bf977b7c1826c06b8.vehicle',
      'namespace' => 'digitalsignage',
    ),
    15 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '0afafb05d21c3b28a3abed6060e915ff',
      'native_key' => 'digitalsignage.editor_toolbar1',
      'filename' => 'modSystemSetting/c354e3e8103cbf8f1dc3f842ad41c44c.vehicle',
      'namespace' => 'digitalsignage',
    ),
    16 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '9943ee94bbd356f8b3d4e7b3f3f41c72',
      'native_key' => 'digitalsignage.editor_toolbar2',
      'filename' => 'modSystemSetting/ace0b4b6c9531d3a9f329092f9931e61.vehicle',
      'namespace' => 'digitalsignage',
    ),
    17 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '4604f1371c519b552a2891df55185cfc',
      'native_key' => 'digitalsignage.editor_toolbar3',
      'filename' => 'modSystemSetting/254328731b5dfdd1974eb8cc0215e8c4.vehicle',
      'namespace' => 'digitalsignage',
    ),
    18 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modCategory',
      'guid' => '5a8939b5c6ef4de25ffe3f6b3d5e27bc',
      'native_key' => NULL,
      'filename' => 'modCategory/5fc7ad30124b54621dadb9dd9a31b40e.vehicle',
      'namespace' => 'digitalsignage',
    ),
    19 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modMenu',
      'guid' => '50e8d3647e5bdda3ed2387b241c1b8ff',
      'native_key' => 'digitalsignage',
      'filename' => 'modMenu/bc1a269e498eeb0293fd09899ff805b3.vehicle',
      'namespace' => 'digitalsignage',
    ),
  ),
);