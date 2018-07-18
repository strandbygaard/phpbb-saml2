<?php

namespace noud\saml2\auth\provider;

class UserManager
{
    private $groupManager;

    /**
     * @param GroupManager $groupManager
     */
    public function __construct(GroupManager $groupManager)
    {
        $groupManager = $this->groupManager;
    }

    /**
     * @param ClaimsUser $claimsUser
     * @param $allFields
     * @return array
     * @throws Exception
     */
    function lookup(ClaimsUser $claimsUser, $allFields = false)
    {
        if (!isset($claimsUser)) {
            throw new Exception("claimsUser is null");
        }

        global $db;

        if ($allFields) {
            $fields = '*';
        } else {
            $fields = 'user_id, username, user_password, user_passchg, user_email, user_type, user_login_attempts';
        }
        $sql = 'SELECT ' . $fields . '
       		FROM ' . USERS_TABLE . "
       		WHERE username_clean = '" . $db->sql_escape($claimsUser->userName) . "'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);

        return $row;
    }

    /**
     * @param ClaimsUser $claimsUser
     */
    public function create(ClaimsUser $claimsUser)
    {
        if (!isset($claimsUser)) {
            throw new Exception("claimsUser is null");
        }

        $builder = new UserRowBuilder();
        $user_row = $builder->build($claimsUser);

        // all the information has been compiled, add the user
        // tables affected: users table, profile_fields_data table, groups table, and config table.
        if (!function_exists('user_add')) {
            include(__DIR__ . '/../../../../../includes/functions_user.php');
        }

        $user_id = user_add($user_row);
    }

    /**
     * @param ClaimsUser $claimsUser
     */
    public function sync(ClaimsUser $claimsUser)
    {
        if (!isset($claimsUser)) {
            throw new Exception("claimsUser is null");
        }
    }

    /**
     * @param ClaimsUser $claimsUser
     */
    public function syncGroups(ClaimsUser $claimsUser)
    {
        if (!isset($claimsUser)) {
            throw new Exception("claimsUser is null");
        }
        //TODO Implement this
    }

    /**
     * @param ClaimsUser $claimsUser
     */
    public function syncProfile(ClaimsUser $claimsUser)
    {
        if (!isset($claimsUser)) {
            throw new Exception("claimsUser is null");
        }

        $data = array();

        if (isset($claimsUser->email)) {
            $item = array('user_email' => $claimsUser->email);
            array_push($data, $item);
        }

        array_push($data, array('user_type' => $claimsUser->userType()));
        array_push($data, array('group_id' => (int)$claimsUser->getDefaultGroupId()));
        array_push($data, array('user_lang' => $claimsUser->getPreferredLanguage()));

        global $db;

        $sql = 'UPDATE ' . USERS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE username_clean = ' . $db->sql_escape($claimsUser->userName) . "'";

        $result = $db->sql_query($sql);
        $db->sql_freeresult($result);
    }
}
