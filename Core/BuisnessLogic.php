<?php

class Business
{
    public static function shortUser(UserModel $userModel): array
    {
        // if ( $adminPermissiom == true)
        //return $userModel->main;
        return array($userModel->main['login'], $userModel->main['email'], $userModel->main['name']);
    }

    public static function userInfo(int $id): array|null
    {
        return self::shortUser(UserRepository::findOneBy(['id' => $id]));
    }

    public static function allUsersInfo(): array|null
    {
        $users = UserRepository::findAll();
        $users_array = [];
        foreach ($users as $user) {
            $users_array[] = self::shortUser($user);
        }
        return $users_array;
    }

    public static function editUser(int $id,Request $req): bool
    {
        $id = 0; //$_SESSION['self_id']; пока вручную, будем получать из сессии
        $requestPar = $req->getData();
        $user = UserRepository::findOneBy(['id' => $id]);
        foreach ($requestPar as $key => $value) {
            $user->main[$key] = $value;
        }
        return UserRepository::update($user);

    }

    public static function deleteUser(int $id): bool
    {
        return UserRepository::delete($id);
    }
}