<?php

namespace noud\saml2\auth\provider;

class GroupManager
{
    public function getManagedGroups()
    {
        //TODO the list of managed groups should be configurable
        $groupNames = array('ADMINISTRATORS', 'GLOBAL_MODERATORS', 'MODERATORS', 'REGISTERED', 'NEWLY_REGISTERED');
        $groups = array();

        foreach ($groupNames as $groupName)
        {
            $group = new ManagedGroup($groupName);
            array_push($groups, $group);
        }

        return $groups;
    }

//    public function add_user_to_groups(Array $groups, ClaimsUser $claimsUser)
//    {
//        foreach($groups as $group)
//        {
//            add_user_to_group($group, $claimsUser);
//        }
//    }

//    public function add_user_to_group(IGroup $group, ClaimsUser $claimsUser)
//    {
//        global $db;
//
//        $sql = 'SELECT group_id, group_name, group_colour, group_type
//        		FROM ' . GROUPS_TABLE;
//        $result = $db->sql_query($sql);
//
//        $existing = Array();
//        while ($row = $db->sql_fetchrow($result))
//        {
//            if(in_array(strtolower($row['group_name']), strtolower($group->getGroupName())))
//            {
//
//            }
//
//        }
//        $db->sql_freeresult($result);
//    }

    public function removeUser(IGroup $group, ClaimsUser $claimsUser)
    {
        //TODO implement this
    }

    /**
     * Makes
     * @param ClaimsUser $claimsUser The user to update
     * @param IGroupFilter $groupFilter
     */
    public function sync(ClaimsUser $claimsUser, IGroupFilter $groupFilter = Null)
    {
//        $groups = $claimsUser->getGroups();
//        $filtered = Null;
//        if (!$groupFilter) {
//            $filtered = $groupFilter->filter($groups);
//        }
//        else
//        {
//            $filtered = $groups;
//        }
//
//        foreach ($filtered as $group)
//        {
//            $this->addUser($group, $claimsUser);
//        }
    }
}

interface IGroup
{
    public function getGroupName();
}

class ManagedGroup implements IGroup
{
    private $groupName;

    public function __construct($groupName)
    {
        $this->groupName = $groupName;
    }

    public function getGroupName()
    {
        return $this->groupName;
    }
}

Interface IGroupFilter
{
    public function filter(Array $groups);
}

class ManagedGroupFilter implements IGroupFilter
{
    private $managedGroups;

    /**
     * @param Array $managedGroups
     */
    public function __construct(Array $managedGroups)
    {
        $this->managedGroups = $managedGroups;
    }

    public function filter(Array $groups)
    {
        $filtered = Array();
        foreach ($groups as $group)
        {
            if ($this->managedGroups->in_array(strtoupper($group))) {
                array_push($filtered, strtolower($group));
            }
        }
        return $filtered;
    }
}
