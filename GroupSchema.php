<?php
/**
 * Created by JetBrains PhpStorm.
 * User: martin
 * Date: 15-02-12
 * Time: 15:17
 * To change this template use File | Settings | File Templates.
 */
class GroupSchema
{
    public static function getFoundersGroupName()
    {
        return 'globaladministrators';
    }

    public static function getAdministratorsGroupName()
    {
        return 'administrators';
    }

    public static function getModeratorsGroupName()
    {
        return 'moderators';
    }

    public static function getRegisteredUsersGroupName()
    {
        return 'registeredusers';
    }

}
