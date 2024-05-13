<?php

include_once 'Core\DB.php';
include_once 'model\UserModel.php';
include_once 'Core\BuisnessLogic.php';

class UsersController
{

    public static function get(?int $id): array
    {
        return AccessLeveling::userInfo($id);
    }

    public function list(): array
    {
        return AccessLeveling::allUsersInfo();
    }

    public function update(?int $id, Request $req): array
    {
       return AccessLeveling::editUser($req);
    }
}
