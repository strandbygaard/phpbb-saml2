<?php

namespace noud\saml2\auth\provider;

define('IN_PHPBB', true);

require_once(__DIR__.'/../../../../../simplesaml/lib/_autoload.php');

class auth_simplesaml extends \phpbb\auth\provider\base
{
    /** @var \phpbb\db\driver\driver_interface $db */
    protected $db;

    /**
     * Database Authentication Constructor
     *
     * @param \phpbb\db\driver\driver_interface $db
     */
    public function __construct(\phpbb\db\driver\driver_interface $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     */
    public function login($username, $password)
    {
        return $this->aLogin();
    }

 	/**
	* {@inheritdoc}
	*/
	public function autologin()
    {
        return $this->aLogin(true);
    }

    /**
     * @param bool $auto
     * @return array
     */
    public function aLogin($auto = false)
    {
        $as = new \SimpleSAML\Auth\Simple('default-sp');
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
        if (!$claimsUser->isValidUser()) {
            return array(
                'status' => LOGIN_ERROR_EXTERNAL_AUTH,
                'error_msg' => 'LOGIN_ERROR_EXTERNAL_AUTH_SS',
                'user_row' => array('user_id' => ANONYMOUS),
            );
        }
        // User is authenticated. Look up user in database.
        $row = $userManager->lookup($claimsUser);
        // User is found. Sync user attributes, get updated row, and login user using updated row.
        if (isset($row) && $row !== false) {
            $userManager->sync($claimsUser);
            $row = $userManager->lookup($claimsUser, $auto);
            if ($auto) {
                return $row;
            } else {
                return $this->get_login_array($row);
            }
        }
        // 4. User was not found, so we need to create a user first.
        $userManager->create($claimsUser);
        $row = $userManager->lookup($claimsUser, $auto);
        //5. If no row is found it's an error.
        //TODO need to show a more descriptive error. This needs to be logged somewhere.
        if (!isset($row)) {
            return array(
                'status' => LOGIN_ERROR_EXTERNAL_AUTH,
                'error_msg' => 'LOGIN_ERROR_EXTERNAL_AUTH_SS',
                'user_row' => array('user_id' => ANONYMOUS),
            );
        }
        if ($auto) {
            return $row;
        } else {
            return $this->get_login_array($row);
        }
    }

	public function logout($data, $new_session)
    {
        $as = new \SimpleSAML\Auth\Simple('default-sp');
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
}
