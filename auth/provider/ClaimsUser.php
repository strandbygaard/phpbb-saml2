<?php

namespace noud\saml2\auth\provider;

class ClaimsUser
{
    private $attributes;
    private $map;
    private $groups;

    public $userName;
    public $email;

    function __construct(Array $attributes, ClaimMap $map)
    {
        $this->attributes = $attributes;
        $this->map = $map;

        $this->setUserName($attributes, $map);
        $this->trySetEmail($attributes, $map);

        if(isset($this->attributes[$this->map->groupType]))
        {
            $this->groups = $this->attributes[$this->map->groupType];
        }
    }

    private function setUserName(Array $attributes, ClaimMap $map)
    {
        if (!isset($attributes[$map->userNameType]) || !isset($attributes[$map->userNameType][0])) {
            die("Missing username");
        }

        $userName = $attributes[$map->userNameType][0];

        if(strlen($userName) > 20)
        {
            $val = strtolower($userName);
            $crc64 = ( '0x' . hash('crc32', $val) . hash('crc32b', $val) );
            $this->userName = $crc64;
        }
        else
        {
            $this->userName = strtolower($userName);
        }
    }

    private function trySetEmail(Array $attributes, ClaimMap $map)
    {
        if (!isset($attributes[$map->emailType]) || !isset($attributes[$map->emailType][0])) {
            $this->email = '';
            return;
        }
        $email = $attributes[$map->emailType][0];
        $this->email = strtolower($email);
    }

    public function isFounder()
    {
        return $this->isUserInRole(GroupSchema::getFoundersGroupName());
    }

    public function isAdministrator()
    {
        if ($this->isFounder()) {
            return true;
        }

        return $this->isUserInRole(GroupSchema::getAdministratorsGroupName());
    }

    public function isModerator()
    {
        if ($this->isAdministrator()) {
            return true;
        }

        return $this->isUserInRole(GroupSchema::getModeratorsGroupName());
    }

    public function isRegistered()
    {
        if ($this->isModerator()) {
            return true;
        }
        return $this->isUserInRole(GroupSchema::getRegisteredUsersGroupName());
    }

    private function isUserInRole($role)
    {
        if (!isset($this->attributes[$this->map->groupType])) {
            return false;
        }

        $r = strtolower($role);
        $groups = $this->attributes[$this->map->groupType];
        foreach ($groups as $group)
        {
            $g = strtolower($group);
            if ($g == $r) {
                return true;
            }
        }

        return false;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function userType()
    {
        if ($this->isFounder()) {
            return USER_FOUNDER;
        }

        return USER_NORMAL;
    }


    /**
     * @return int the groupId of the user's default group
     */
    public function getDefaultGroupId()
    {
        if ($this->isAdministrator()) {
            return 5;
        }

        if($this->isModerator())
        {
            return 4;
        }

        if ($this->isRegistered()) {
            return 2;
        }

        return 2;
//        return 7; // Return 7 for non-registered users
    }

    public function getPreferredLanguage()
    {
        // If no preferredLanguage claim is present, we default to Danish
        if (!isset($this->attributes[$this->map->preferredLanguage])) {
            return 'da';
        }

        // Languages supported by the phpBB installation
        $supportedLanguages = array('da', 'en');
        $lang = $this->attributes[$this->map->preferredLanguage][0];

        if (!isset($lang)) {
            return 'da';
        }

        // Comparer the supplied claim value with the list of supported languages
        foreach ($supportedLanguages as $supported) {
            if (strtolower($lang) == strtolower($supported)) {
                // Return the supported language if a match is found
                return $supported;
            }
        }

        // Fallback to Danish, if the preferred language is not supported
        return 'da';
    }
}
