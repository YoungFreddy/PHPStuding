<?php

namespace admin;
//include_once '.\Core\BuisnessLogic.php';
class UsersController
{
    public static function get(?int $id): array
    {
        return \Business::userInfo($id);
    }

    public static function list(): array
    {
        return \Business::allUsersInfo();
    }

    public static function update(int $id, \Request $req): bool
    {
        return \Business::editUser($id,$req);
    }

    public static function delete(int $id): bool
    {
        return \Business::deleteUser($id);
    }
}