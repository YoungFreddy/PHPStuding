<?php

namespace admin;
//include_once '.\Core\BusinessLogic.php';
class UsersController 
{
    
    public static function get(int $id): \Response
    {
        return \Domain\UserDomain::userInfo($id,true);
    }

    public static function list(): \Response
    {
        return \Domain\UserDomain::allUsersInfo(true);
    }

    public static function update(int $id, \Request $req): \Response
    {
        return \Domain\UserDomain::editUser($id,$req);
    }

    public static function delete(int $id): \Response
    {
        return \Domain\UserDomain::deleteUser($id);
    }
}