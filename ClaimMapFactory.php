<?php
class ClaimMapFactory
{
    function __construct()
    {
    }

    /**
     * @return ClaimMap
     */
    public function Create()
    {
        $map = new ClaimMap();
//        $map->userNameType = "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name";
//        $map->userNameType = "urn:oid:0.9.2342.19200300.100.1.1";
//        $map->userNameType = "uid";
        $map->givenNameType = "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname";
        $map->lastNameType = "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname";
        $map->emailType = "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/email";
        $map->organizationNameType = "urn:oid:2.5.4.10";
        $map->groupType = "http://schemas.microsoft.com/ws/2008/06/identity/claims/role";
        $map->preferredLanguage = "preferredLanguage";

        return $map;
    }
}

?>
