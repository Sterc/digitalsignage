<?php

$package = 'DigitalSignageSocialMedia';

$settings = [
    [
        'key'   => 'user_name',
        'value' => '',
        'name'  => 'Name'
    ],
    [
        'key'   => 'user_email',
        'value' => '',
        'name'  => 'Email address'
    ]
];

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        foreach ($settings as $key => $setting) {
            $c = [
                'key' => strtolower($package).'.'.$setting['key']
            ];

            if (null !== ($setting = $modx->getObject('modSystemSetting', $c))) {
                $settings[$key]['value'] = $setting->get('value');
            }
        }

        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

$output[] = '<style type="text/css">
        #modx-setupoptions-panel { display: none; }
    </style>
    <script>
        document.getElementsByClassName("x-window-header-text")[0].innerHTML = "DigitalSignageSocialMedia installation - a MODX Extra by Sterc";
    </script>
    <h2>Get free priority updates</h2>
    <p>Enter your name and email address below to receive priority updates about our extras. Be the first to know about updates and new features. <i><b>It is NOT required to enter your name and email to use this extra.</b></i></p>';

foreach ($settings as $setting) {
    $output[] = '<label for="'. $setting['key'] .'">'. $setting['name'] .' (optional)</label>
        <input type="text" name="'. $setting['key'] .'" id="'. $setting['key'] .'" width="300" value="'. $setting['value'] .'" />';
}

return implode('<br /><br />', $output);

?>