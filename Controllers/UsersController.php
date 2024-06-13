<?php

use Domain\UserDomain;

include_once 'Core\DB.php';
include_once 'model\Model.php';

class UsersController
{

    public static function get(int $id): Response
    {
        return UserDomain::UserInfo($id);
    }

    public static function list(): Response
    {
        return UserDomain::allUsersInfo();
    }

    public static function update(Request $req): Response
    {
       return  UserDomain::editUser($_SESSION['self_id'],$req);
    }

    public static function login(Request $req): Response
    {
       return Auth::login($req);
    }

    public static function logout(): void
    {
        Auth::logout();
    }

    public static function reg(Request $req): Response
    {
        return Auth::regUser($req);
    }


}
