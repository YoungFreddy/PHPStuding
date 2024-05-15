<?php

include_once 'Core\DB.php';
include_once 'model\UserModel.php';
include_once 'Core\BuisnessLogic.php';

class UsersController
{

    public static function get(?int $id): array
    {
        return Вusiness::userInfo($id);
    }

    public static function list(): array
    {
        return Вusiness::allUsersInfo();
    }

    public static function update(?int $id, Request $req): bool
    {
       return Вusiness::editUser($req);
    }
}
