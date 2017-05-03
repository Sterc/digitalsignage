<?php
/**
 * UpgradeMODXWidget snippet for UpgradeMODX extra
 *
 * Copyright 2015 by Bob Ray <http://bobsguides.com>
 * Created on 08-16-2015
 *
 * UpgradeMODX is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * UpgradeMODX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * UpgradeMODX; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package upgrademodx
 */

/**
 * Description
 * -----------
 * UpgradeMODX Dashboard widget
 * This package was inspired by the work of a number of people and I have borrowed some of their code.
 * Dmytro Lukianenko (dmi3yy) is the original author of the MODX install script. Susan Sottwell, Sharapov,
 * Bumkaka, Inreti, Zaigham Rana, frischnetz, and AgelxNash, also contributed and I'd like to thank all
 * of them for laying the groundwork.
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package upgrademodx
 **/

/**
 * Properties
 *
 * @property &groups textfield -- group, or commma-separated list of groups, who will see the widget; Default: (empty)..
 * @property &hideWhenNoUpgrade combo-boolean -- Hide widget when no upgrade is available; Default: No.
 * @property &interval textfield -- Interval between checks -- Examples: 1 week, 3 days, 6 hours; Default: 1 week.
 * @property &language textfield -- Two-letter code of language to user; Default: en.
 * @property &lastCheck textfield -- Date and time of last check -- set automatically; Default: (empty)..
 * @property &latestVersion textfield -- Latest version (at last check) -- set automatically; Default: (empty)..
 * @property &plOnly combo-boolean -- Show only pl (stable) versions; Default: yes.
 * @property &versionsToShow textfield -- Number of versions to show in upgrade form (not widget); Default: 5.
 */

define('MODX_API_MODE', true); // Gotta set this one constant.

/**
 * include modx core config.
 * include the config file for the db settings.
 * include the MODx class.
 * require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
 */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CORE_PATH . "model/modx/modx.class.php";
require_once "upgrademodx.class.php";

$modx = new modX();
$modx->initialize('mgr');

/**
 *
*@param $modx
 */
function verifyOAuth($modx)
{
    /**
     * Options
     */
    $redirectUnauthorized = (int)$modx->getOption('redirectUnauthorized', array(), 1);
    $redirectTo           = $modx->getOption('redirectTo', array(), 'unauthorized');
    $returnOnUnauthorized = $modx->getOption('returnOnUnauthorized', array(), 0);
    $returnOnSuccess      = $modx->getOption('returnOnSuccess', array(), 1);

    /*
     * Paths
     */
    $oauth2Path = $modx->getOption('oauth2server.core_path', null, $modx->getOption('core_path') . 'components/oauth2server/');
    $oauth2Path .= 'model/oauth2server/';

    /**
     * Get class
     */
    if (file_exists($oauth2Path . 'oauth2server.class.php')) {
        $oauth2 = $modx->getService('oauth2server', 'OAuth2Server', $oauth2Path, array());
    }
    if (!($oauth2 instanceof OAuth2Server)) {
        $modx->log(modX::LOG_LEVEL_ERROR, '[authorizeOAuth2] could not load the required class!');
        return;
    }

    /*
     * We need these
     */
    $server   = $oauth2->createServer();
    $request  = $oauth2->createRequest();
    $response = $oauth2->createResponse();

    if (!$server || !$request || !$response) {
        $modx->log(modX::LOG_LEVEL_WARN, '[verifyOAuth2]: could not create the required OAuth2 Server objects.');
        return;
    }

    /**
     * Verify resource requests
     */
    $verified = $server->verifyResourceRequest($request);

    if (!$verified) {
        if ($redirectUnauthorized) {
            if ($redirectTo === 'error') {
                $modx->sendError();
            } else {
                $oauth2->sendUnauthorized();
            }
        } else {
            return $returnOnUnauthorized;
        }
    } else {
        return $returnOnSuccess;
    }
}

/**
 * checks if function returns right value.
 *
 * 1 = authorized
 * 0 = not authorized
 */
if (verifyOAuth($modx) === 1) {
    update($modx);
} else {
    echo 'not authorized';
    die();
}

function update($modx)
{
    $upgrade = new UpgradeMODX($modx);

    if (php_sapi_name() === 'cli') {
        /* This section for debugging during development. It won't execute in MODX */
        include 'C:\xampp\htdocs\addons\assets\mycomponents\instantiatemodx\instantiatemodx.php';
        $snippet =
        $scriptProperties = array(
            'versionsToShow' => 5,
            'hideWhenNoUpgrade' => false,
            'lastCheck' => '2015-11-03 10:13:00',
            'interval' => '+60 seconds',
            'plOnly' => false,
            'language' => 'en',
            'forcePclZip' => false,
            'forceFopen' => false,
            'currentVersion' => $modx->getOption('settings_version'),
            'latestVersion' => '2.5.1-pl',
            'githubTimeout' => 6,
            'modTimeout' => 6,
            'tries' => 2,
        );

    } else {
        $version     = $modx->getVersionData();
        $needsUpdate = $upgrade->upgradeAvailable($version['full_version']);

        $scriptProperties = array(
            'versionsToShow'    => 5,
            'hideWhenNoUpgrade' => false,
            'lastCheck'         => '2015-11-03 10:13:00',
            'interval'          => '+60 seconds',
            'plOnly'            => false,
            'language'          => 'en',
            'forcePclZip'       => false,
            'forceFopen'        => false,
            'currentVersion'    => $modx->getOption('settings_version'),
            'latestVersion'     => '2.5.1-pl',
            'githubTimeout'     => 6,
            'modTimeout'        => 6,
            'tries'             => 2,
        );

        /*
         * This will execute when in MODX
         */
        $language = $modx->getOption('language', $scriptProperties, 'en', true);
        $modx->lexicon->load($language . ':upgrademodx:default');
        /*
         * Return empty string if user shouldn't see widget
         */

        $groups = $modx->getOption('groups', $scriptProperties, 'Administrator', true);
        if (strpos($groups, ',') !== false) {
            $groups = explode(',', $groups);
        }
    }

    $props    = $scriptProperties;
    $corePath = $modx->getOption('core_path', null, MODX_CORE_PATH);

    $upgrade->init($props);
    $upgrade->writeScriptFile();

    /*
     * Log out all users before launching the form
     */
    $sessionTable = $modx->getTableName('modSession');
    if ($modx->query("TRUNCATE TABLE {$sessionTable}") == false) {
        $flushed = false;
    } else {
        $modx->user->endSession();
    }

    $modx->sendRedirect(MODX_BASE_URL . 'upgrade.php');

    /* Set the method */
    $forceFopen = $modx->getOption('forceFopen', $props, false, true);
    $method     = null;

    if (extension_loaded('curl') && (!$forceFopen)) {
        $method = 'curl';
    } elseif (ini_get('allow_url_fopen')) {
        $method = 'fopen';
    } else {
        die($this->modx->lexicon('ugm_no_curl_no_fopen'));
    }

    $lastCheck           = $modx->getOption('lastCheck', $props, '2015-08-17 00:00:004', true);
    $interval            = $modx->getOption('interval', $props, '+1 week', true);
    $interval            = '+1 week';
    $hideWhenNoUpgrade   = $modx->getOption('hideWhenNoUpgrade', $props, false, true);
    $plOnly              = $modx->getOption('plOnly', $props);
    $versionsToShow      = $modx->getOption('versionsToShow', $props, 5);
    $currentVersion      = $modx->getOption('settings_version');
    $latestVersion       = $modx->getOption('latestVersion', $props, '', true);
    $versionListPath     = $upgrade->getVersionListPath();
    $versionListExists   = false;
    $fullVersionListPath = $versionListPath . 'versionlist';

    if (file_exists($fullVersionListPath)) {
        $v = file_get_contents($fullVersionListPath);
        if (!empty($v)) {
            $versionListExists = true;
        }
    }

    $timeToCheck = $upgrade->timeToCheck($lastCheck, $interval);
    /* Perform check if no versionlist or latestVersion, or if it's time to check */
    if ((!$versionListExists) || $timeToCheck || empty($latestVersion)) {
        $upgradeAvailable = $upgrade->upgradeAvailable($currentVersion, $plOnly, $versionsToShow, $method);
        $latestVersion = $upgrade->getLatestVersion();
    } else {
        $upgradeAvailable = version_compare($currentVersion, $latestVersion) < 0;;
    }
    $placeholders                             = array();
    $placeholders['[[+ugm_current_version]]'] = $currentVersion;
    $placeholders['[[+ugm_latest_version]]']  = $latestVersion;

    $errors = $upgrade->getErrors();
    if (!empty($errors)) {
        $msg = '';
        foreach ($errors as $error) {
            $msg .= '<br/><span style="color:red">' . $modx->lexicon('ugm_error') . ': ' . $error . '</span>';
        }
        return $msg;
    }

    $placeholders['[[+ugm_current_version_caption]]'] = $modx->lexicon('ugm_current_version_caption');
    $placeholders['[[+ugm_latest_version_caption]]']  = $modx->lexicon('ugm_latest_version_caption');

    /* See if there's a new version and if it's downloadable */
    if ($upgradeAvailable) {
        echo 'there is an upgrade available';

        $placeholders['[[+ugm_notice]]']       = $modx->lexicon('ugm_upgrade_available');
        $placeholders['[[+ugm_notice_color]]'] = 'green';
        $placeholders['[[+ugm_logout_note]]']  = '<br/><br/>(' . $modx->lexicon('ugm_logout_note') . ')';
        $placeholders['[[+ugm_form]]']         = '<br/><br/>
            <form method="post" action="">
               <input class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon"
                        type="submit" name="UpgradeMODX" value="' . $modx->lexicon('ugm_upgrade_modx') . '">
            </form>';

    } else {
        if ($hideWhenNoUpgrade) {
            return '';
        } else {
            $placeholders['[[+ugm_notice]]']       = $modx->lexicon('ugm_modx_up_to_date');
            $placeholders['[[+ugm_notice_color]]'] = 'gray';
        }
    }
}