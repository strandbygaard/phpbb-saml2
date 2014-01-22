<?php

require_once("ClaimsUser.php");

class UserRowBuilder
{

    public function build(ClaimsUser $claimsUser)
    {
        $user_row = array(
            'username' => $claimsUser->userName,
            'user_password' => phpbb_hash(PasswordFactory::generate()),
            'user_email' => $claimsUser->email,
            'group_id' => (int)$claimsUser->getDefaultGroupId(),
            'user_timezone' => (float)'2',
            'user_lang' => $claimsUser->getPreferredLanguage(),
            'user_type' => $claimsUser->userType(),
            'user_regdate' => time(),
        );

        return $user_row;
    }
}

?>
