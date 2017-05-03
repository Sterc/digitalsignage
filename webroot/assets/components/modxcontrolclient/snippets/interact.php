<?php
define('MODX_API_MODE', true); // Gotta set this one constant.

/**
 * include modx core config.
 * include the config file for the db settings.
 * include the MODx class.
 */
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CORE_PATH . "model/modx/modx.class.php";
require_once "upgrademodx.class.php";

/*initalize modx*/
$modx = new modX();
$modx->initialize('mgr');

/**
 * @param $modx
 */
function verifyOAuth($modx)
{
    /*
     * Options
     */
    $redirectUnauthorized = (int) $modx->getOption('redirectUnauthorized', [], 1);
    $redirectTo           = $modx->getOption('redirectTo', [], 'unauthorized');
    $returnOnUnauthorized = $modx->getOption('returnOnUnauthorized', [], 0);
    $returnOnSuccess      = $modx->getOption('returnOnSuccess', [], 1);

    /*
     * Paths
     */
    $oauth2Path = $modx->getOption(
        'oauth2server.core_path',
        null,
        $modx->getOption('core_path') . 'components/oauth2server/'
    );
    $oauth2Path .= 'model/oauth2server/';

    /*
     * Get class
     */
    if (file_exists($oauth2Path . 'oauth2server.class.php')) {
        $oauth2 = $modx->getService(
            'oauth2server',
            'OAuth2Server',
            $oauth2Path,
            []
        );
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

    /*
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
    checkEventType($_SERVER['HTTP_EVENT_TYPE'], $modx);
} else {
    echo 'not authorized';
    die();
}

/**
 * @param $eventType
 * @param $modx
 */
function checkEventType($eventType, $modx)
{
    if (empty($eventType)) {
        'error no Event-Type specified in header';
    }

    $data     = $_POST['data'];
    $resource = $_POST['class_key'];

    switch ($eventType) {
        case 'PUT':
            updateResource($data, $resource, $modx);
            break;
        case 'CREATE':
            createResource($data, $resource, $modx);
            break;
        case 'DELETE':
            deleteResource($data, $resource, $modx);
            break;
        case 'GET':
            if (isset($_POST['get_modx_data'])) {
                unset($_POST['get_modx_data']);
                getModxData($data, $resource, $modx);
            } else {
                getResources($data, $resource, $modx);
            }
            break;
    }
}

/**
 * @param $data
 * @param $resource
 * @param $modx
 */
function updateResource($data, $resource, $modx)
{
    if (empty($data['username']) || empty($data['password'])) {
        die('ERROR: Missing criteria.');
    }

    $query = $modx->newQuery($resource);
    $query->where(
        [
            'username' => $data['username']
        ]
    );
    $user = $modx->getObjectGraph($resource, '{ "Profile":{}, "UserGroupMembers":{} }', $query);

    if (!$user) {
        die("ERROR: No user with username " . $data['username']);
    }

    $user->set('username', $data['username']);
    $user->set('active', 1);
    $user->set('password', $data['password']);
    $user->Profile->set('email', $data['email']);
    $user->Profile->set('blocked', 0);
    $user->Profile->set('blockeduntil', 0);
    $user->Profile->set('blockedafter', 0);

    /* save user */
    if (!$user->save()) {
        die('ERROR: Could not save user.');
    }

    if ($user->save()) {

        /**
         * @todo Get all current user settings.
         * @todo remove all settings where key is not in data settings.
         */
        $settingsToRemove    = [];
        $currentUserSettings = $modx->getIterator('modUserSetting', ['user' => $user->get('id')]);
        if ($currentUserSettings) {
            foreach ($currentUserSettings as $currentUserSetting) {
                if (!array_key_exists($currentUserSetting->get('key'), $data['settings'])) {
                    $settingsToRemove[] = $currentUserSetting->get('key');
                }
            }
        }

        if (isset($data['settings']) && !empty($data['settings'])) {
            foreach ($data['settings'] as $key => $settings) {
                $modxSetting = $modx->getObject('modUserSetting', [
                    'user' => $user->get('id'),
                    'key' => $key
                ]);
                if (!$modxSetting)  {
                    $modxSetting = $modx->newObject('modUserSetting');
                    $modxSetting->set('user',      $user->get('id'));
                    $modxSetting->set('key',       $key);
                }

                $modxSetting->set('value',     $settings['value']);
                $modxSetting->set('xtype',     $settings['xtype']);
                $modxSetting->set('namespace', $settings['namespace']);
                $modxSetting->set('area',      $settings['area']);

                $modxSetting->save();
            }
        }

        if (!empty($settingsToRemove)) {
            foreach ($settingsToRemove as $key) {
                $setting = $modx->getObject('modUserSetting', [
                    'user' => $user->get('id'),
                    'key'  => $key
                ]);

                $setting->remove();
            }
        }
    }

    echo "SUCCESS: User" . $data['username'] . $data['e-mail'] . "updated";
}

/**
 * @param $data
 * @param $resource
 * @param $modx
 */
function createResource($data, $resource, $modx)
{
    // check if user with email exists in db
    $query = $modx->newQuery($resource);
    $query->leftJoin('modUserProfile', 'Profile');
    $query->where(array(
                      'Profile.email' => $data['email']
                  ));
    $modUser = $modx->getObject($resource, $query);
    /**
     * if user exists return...
     */
    if ($modUser) {
        /**
         * Error.
         */
        echo 'user already exists';
        exit;
    }

    /**
     * check if username exists
     */
    $query = $modx->newQuery('modUser');
    $query->where(array('username' => $data['username']));
    $user = $modx->getObjectGraph('modUser', '{ "Profile":{}, "UserGroupMembers":{} }', $query);

    if (!$user) {
        $user = $modx->newObject($resource);
        $user->set('username', $data['username']);
        $user->set('active', 1);
        $user->set('sudo', 1);
        $user->set('password', $data['password']);

        //add email to profile
        $profile = $modx->newObject('modUserProfile');
        $profile->set('email', $data['email']);
        $success = $user->addOne($profile);

        if ($success) {
            if (!$user->save()) {
                die('ERROR: Could not create user');
            }
            $user->save();

            //user needs to be saved first before adding to group
            $user->joinGroup(1, 2);

            echo 'successfully created user';
        }
    } else {
        echo "error could not create user, user already exists";
    }
}

/**
 * @param $data
 * @param $resource
 * @param $modx
 */
function deleteResource($data, $resource, $modx)
{
    $user = $modx->getObject($resource, array('username' => $data['username']));

    if (!isset($user)) {
        echo 'user not exists';
    }
    if ($user->remove() == false) {
        echo 'failed to remove user';
    } else {
        $user->remove();
        echo 'user has been removed';
    }
}

function getResources($data, $resource, $modx)
{
    $result    = [];
    $resources = $modx->getIterator($resource, $data);

    if ($resources) {
        foreach($resources as $resource) {
            $result[] = $resource->toArray();
        }
    }

    echo json_encode($result);
}

/**
 * @param $data
 * @param $resource
 * @param $modx
 */
function getModxData($data, $resource, $modx)
{
    global $lastInstallTime;

    $c = $modx->newQuery('modUser');
    $c->leftJoin('modUserProfile', 'Profile');

    /**
     * add column names that u want to show
     */
    $c->select(
        array(
            'modUser.*',
            'Profile.fullname',
            'Profile.email',
            'Profile.blocked',
        )
    );

    $c->where(
        [
            'modUser.sudo' => 1
        ]
    );

    $users    = $modx->getCollection("modUser", $c);
    $allUsers = [];

    /**
     * loop over users and put them in allUsers array
     */
    foreach ($users as $user) {
        $userArray = $user->toArray();
        $allUsers[] = $userArray;
    }

    /**
     * Get modx version.
     */
    $version = $modx->getVersionData();

    /*
     * Init modx upgrade class.
     */
    $modxUpgrade = new UpgradeMODX($modx);
    $needsUpdate = $modxUpgrade->upgradeAvailable($version['full_version']);

    /*
     * Get website version.
     */
    $modxCoreVersionData = [
        'version'          => $version['full_version'],
        'needs_update'     => $needsUpdate,
        'modx_last_update' => $lastInstallTime,
    ];

    /*
     * Fetch all packages & loop over them.
     */
    $processorsPath = MODX_CORE_PATH . 'components/modxcontrolclient/processors/';
    $response = $modx->runProcessor(
        'packages/getlist',
        ['limit' => 0],
        ['processors_path' => $processorsPath]
    );
    $response = json_decode($response->response);

    $allPackages = [];
    if (!empty($response->results)) {
        foreach ($response->results as $result) {
            $allPackages[] = $result;
        }
    }

    $dbVersion = '';
    $stmt      = $modx->query("SELECT VERSION()");
    if ($stmt) {
        $dbVersion = $stmt->fetch(PDO::FETCH_COLUMN);
        $stmt->closeCursor();
    }

    $serverInfo = [
        'php_version' => phpversion(),
        'db_type'     => $modx->getOption('dbtype'),
        'db_version'  => $dbVersion
    ];

    $siteData = [$modxCoreVersionData, $allPackages, $allUsers, $serverInfo];

    echo json_encode($siteData);
}