<?php

namespace Domain;
use Request;
use Response;
use UserModel;
use UserRepository;

class UserDomain
{
    public static function shortUser(UserModel $userModel): array
    {
        return ['id' => $userModel->main['id'],
            'login' => $userModel->main['login'],
            'email' => $userModel->main['email'],
            'name' => $userModel->main['name']];
    }

    public static function userInfo(int $id, bool $full = false): Response
    {
        $fullUser = UserRepository::findOneBy(['id' => $id]);
        if (is_null($fullUser)) {
            return new Response(false, 'User not found', null);
        }
        if ($full) return new Response(true, 'User was found', $fullUser->main);
        return new Response(true, 'User was found', self::shortUser($fullUser));

    }

    public static function allUsersInfo(bool $full = false): Response
    {
        $users = UserRepository::findAll();
        if ($full) return new Response(true, 'Полный список пользователей получен', $users);
        $users_array = [];
        foreach ($users as $user) {
            $users_array[] = self::shortUser($user);
        }
        return new Response(true, 'Полный список пользователей получен', $users_array);
    }

    public static function editUser(int $id, Request $req): Response
    {
        $requestPar = $req->getData();
        $user = UserRepository::findOneBy(['id' => $id]);
        if (is_null($user)) {
            return new Response(false, 'User not found', null);
        }
        foreach ($requestPar as $key => $value) {
            if (!array_key_exists($key, $user->main)) return new Response(false, 'Поля ' . $key . ' не существует');
            if ($_SESSION['auth'] == 0) {
                if ($key == 'id' || $key == 'login' || $key == 'password' || $key == 'role' || $key == 'is_deleted') return new Response(false, 'Неизменяемые поля');
            } else {
                if ($key == 'id') return new Response(false, 'Неизменяемое поле');
                if ($key == 'password') $value = password_hash($value, PASSWORD_DEFAULT);
            }
            $user->main[$key] = $value;
        }
        return new Response(UserRepository::update($user), 'Профиль пользователя изменен', null);

    }

    public static function deleteUser(int $id): Response
    {
        $user = UserRepository::findOneBy(['id' => $id]);
        if (is_null($user)) {
            return new Response(false, 'User not found', null);
        }
        $user->main['is_deleted'] = true;
        return new Response(UserRepository::update($user), 'Профиль пользователя удален', null);
    }
}
