<?php

class Auth
{

    public static function regUser($login, $password):void
    {
        // Проверим, не занято ли имя пользователя
        $stmt = DB::getInstance()->getConnection()->prepare("SELECT * FROM `users` WHERE `login` = :login");
        $stmt->execute(['login' => $login]);
        if ($stmt->rowCount() > 0) {
            echo ('Это имя пользователя уже занято.');
           // header('Location: /'); // Возврат на форму регистрации
            return;
        }

// Добавим пользователя в базу
        $stmt = DB::getInstance()->getConnection()->prepare("INSERT INTO `users` (`login`, `password`) VALUES (:login, :password)");
        $stmt->execute([
            'login' => $login,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        header('Location: login.php');
    }


    public static function login($login, $password):void
    {
        // проверяем наличие пользователя с указанным юзернеймом
        $stmt = DB::getInstance()->getConnection()->prepare("SELECT * FROM `users` WHERE `login` = :login");
        $stmt->execute(['login' => $login]);
        if (!$stmt->rowCount()) {
            echo('Пользователь с такими данными не зарегистрирован');
           // header('Location: login.php')
            return;
        }else {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
// проверяем пароль
        if (password_verify($password, $user['password'])) {
            // Проверяем, не нужно ли использовать более новый алгоритм
            // или другую алгоритмическую стоимость
            // Например, если вы поменяете опции хеширования
            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = DB::getInstance()->getConnection()->prepare('UPDATE `users` SET `password` = :password WHERE `login` = :login');
                $stmt->execute([
                    'login' => $login,
                    'password' => $newHash,
                ]);
            }
            $_SESSION['Auth'] = $user['role'];
            $_SESSION['id'] = $user['id'];
           // header('Location: /');
            echo 'success!!!!';
           // var_dump(($_SESSION['Auth']));
            //die;
        }else {

            echo('Пароль неверен');
            //header('Location: login.php');
        }
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