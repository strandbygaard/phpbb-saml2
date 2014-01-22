<?php

require_once(__DIR__.'\..\..\simplesaml\lib\_autoload.php');
require_once("ClaimMap.php");
require_once("ClaimMapFactory.php");
require_once("ClaimsUser.php");
require_once("UserManager.php");

error_reporting(E_STRICT);

if (!defined('IN_PHPBB')) {
    exit;
}

function login_simplesaml($username = '', $password = '', $ip = '', $browser = '', $forwarded_for = '')
{
    $as = new SimpleSAML_Auth_Simple('default-sp');
    $fact = new ClaimMapFactory();
    $map = $fact->Create();

    $as->requireAuth();
    $attributes = $as->getAttributes();

    // If no username claim is present, the user is not authenticated.
    //TODO error_msg is localized using common.php. Could be nice to move this to a different file.
    if (!isset($attributes[$map->userNameType]) || !isset($attributes[$map->userNameType][0])) {
        return array(
            'status' => LOGIN_ERROR_EXTERNAL_AUTH,
            'error_msg' => 'LOGIN_ERROR_EXTERNAL_AUTH_SS',
            'user_row' => array('user_id' => ANONYMOUS),
        );
    }

    $groupManager = new GroupManager();
    $userManager = new UserManager($groupManager);
    $claimsUser = new ClaimsUser($attributes, $map);

    // User is authenticated. Look up user in database.
    $row = $userManager->lookup($claimsUser);

    // User is found. Sync user attributes, get updated row, and login user using updated row.
    if (isset($row)) {
        $userManager->sync($claimsUser);
        $row = $userManager->lookup($claimsUser);

        return get_login_array($row);
    }

    // 4. User was not found, so we need to create a user first.
    $userManager->create($claimsUser);

    $row = $userManager->lookup($claimsUser);

    //5. If no row is found it's an error.
    //TODO need to show a more descriptive error. This needs to be logged somewhere.
    if (!isset($row)) {
        return array(
            'status' => LOGIN_ERROR_EXTERNAL_AUTH,
            'error_msg' => 'LOGIN_ERROR_EXTERNAL_AUTH_SS',
            'user_row' => array('user_id' => ANONYMOUS),
        );
    }

    return get_login_array($row);
}

function logout_simplesaml($user_row, $new_session)
{
    $as = new SimpleSAML_Auth_Simple('default-sp');
    $as->logout("/");
}

function get_login_array(array $row)
{
    if (!isset($row)) {
        throw new Exception("Row is null");
    }

    return array(
        'status' => LOGIN_SUCCESS,
        'error_msg' => false,
        'user_row' => $row,
    );
}


?>