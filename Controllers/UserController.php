<?php

include_once 'Core\DB.php';
include_once 'model\UserModel.php';
include_once 'Core\BuisnessLogic.php';

class UsersController
{

    public static function get(?int $id): array
    {
        return Business::userInfo($id);
    }

    public static function list(): array
    {
        return Business::allUsersInfo();
    }

    public static function update(Request $req): bool
    {
       return Business::editUser($_SESSION['self_id'],$req);
    }
}
