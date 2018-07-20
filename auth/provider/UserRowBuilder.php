<?php

namespace noud\saml2\auth\provider;

class UserRowBuilder
{

    public function build(ClaimsUser $claimsUser)
    {

		global $phpbb_container;
        $passwords_manager = $phpbb_container->get('passwords.manager');

        $user_row = array(
            'username' => $claimsUser->userName,
            'user_password' => $passwords_manager->hash(PasswordFactory::generate()),
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
