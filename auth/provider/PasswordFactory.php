<?php

namespace noud\saml2\auth\provider;

/**
 * Created by JetBrains PhpStorm.
 * User: martin
 * Date: 13-02-12
 * Time: 13:09
 * To change this template use File | Settings | File Templates.
 */
class PasswordFactory
{
    public static function generate()
    {
        return PasswordFactory::getPassword();
    }

    protected static function getPassword()
    {

        return PasswordFactory::getRandomString();
    }

    protected static function getRandomString()
    {
        $length = 12;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = '';
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }
        return $string;
    }
}
