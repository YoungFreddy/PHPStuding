<?php

class Auth
{

    public static function regUser(Request $req): Response
    {
        $request = $req->getData();
        if (\Secondary::check( $request['login'])) return new Response(false,'Incorrect login ');
        if (!array_key_exists("login", $request) && !array_key_exists("password", $request)) {
            return new Response(false, 'Incorrect request: required field login and password!');
        }
        $login = $request['login'];
        $password = $request['password'];
        if (is_null($login) || is_null($password)) {
            return new Response(false, 'Incorrect request: required not empty login and password!');
        }
        $user = UserRepository::findOneBy(['login' => $login]);
        if ($user) return new Response(false, 'This login already exists');
        $userDescr = [
            'id' => null,
            'login' => $login,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => ($request['email'] ?? null),
            'name' => ($request['name'] ?? null),
            'role' => 0,
            'is_deleted' => 0
        ];
        $root = [
            'id'=>null,
            'path'=>'root',
            'parent_id'=>0,
            'is_deleted'=>0
        ];
        $newUser = new UserModel($userDescr);
        UserRepository::insert($newUser);
        $root['owner_id'] =DB::getInstance()->getConnection()->lastInsertId();
        $rootFolder = new DirectoryModel($root);
        DirectoryDB::insert($rootFolder);

        return new Response(true, 'Ok');
    }


    public static function login(Request $req): Response
    {
        if (isset($_SESSION['self_id'])) return new Response(false, 'You are already authorized');
        $request = $req->getData();
        if (!array_key_exists("login", $request) || !array_key_exists("password", $request)) {
            return new Response(false, 'Incorrect request: required field login and password!');
        }
        $login = $request['login'];
        $password = $request['password'];
        if (is_null($login) || is_null($password)) {
            return new Response(false, 'Incorrect request: required not empty login and password!');
        }

        $user = UserRepository::findOneBy(['login' => $login]);
        if (!isset($user)) return new Response(false, 'This login doesnt exists');
        if (password_verify($password, $user->main['password'])) {
            $_SESSION['auth'] = $user->main['role'];
            $_SESSION['self_id'] = $user->main['id'];
            return new Response(true, 'You are authorized');
        }
        return new Response(false, 'Wrong password');

    }

    public static function logout(): bool
    {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        echo "Logout Success";
        return true;
    }
}