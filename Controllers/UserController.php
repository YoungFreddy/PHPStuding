<?php

include_once 'Core\DB.php';
include_once 'model\UserModel.php';

class UsersController
{

    public static function get(?int $id): UserModel
    {
        $userInfo = UserRepository::findOneBy(['id' => $id]);
        if (!$userInfo) {
            return ['Такого пользователя нет'];
        } else return $userInfo;
    }

    public function list(): array
    {
        return UserRepository::findAll();
    }

    public function update(?int $id, Request $req): bool
    {
        // if ($_SESSION['Auth']==0) {
        //   $id=$_SESSION['id'
        $requestPar= $req->getData();
        $user = self::get($id);
        foreach ($requestPar as $key=> $value ) {
                $user->main[$key] = $value;
        }
        //$updateQuery = array_replace(self::get($id), $req->getData());
        return UserRepository::update($user);
    }


}


class Admin
{


}

/* session_start();
var_dump(session_id());
$a = DB::getInstance();
$u = new UserController();
var_dump(__DIR__);

?>
    <!DOCTYPE html>
    <html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Input Form</title>
</head>
<body>
<form action="UserController.php" method="POST">
    <?php
    if (!isset($_SESSION['auth'])) {
        if (isset($_POST['login'])) {
            $u->login($_POST['login'], $_POST['password']);
        }
        ?>


        <label for="name">Имя</label>
        <input id="name" name="login"/>
        <hr/>
        <label for="name">Пароль</label>
        <input id="name" name="password"/>
        <hr/>
        <input type="submit" name="Send" value="Войти"/>
        <?php
    }
    ?>
    <input type="submit" name="logout" value="Вsйти"/>
</form>
</body>

<?php
if (isset($_POST['logout']) && $_SESSION['auth']) {
    echo 'Кнопка была нажата';
    $u->logout();
    header('Location:UserController.php');
} */