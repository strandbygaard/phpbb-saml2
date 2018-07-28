<?php

namespace noud\saml2\auth\provider;

class ClaimMap
{
    function __construct()
    {
    }

    public $userNameType;
    public $emailType;
    public $givenNameType;
    public $lastNameType;
    public $groupType;
    public $preferredLanguage;
}
