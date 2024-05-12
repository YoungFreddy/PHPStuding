<?php

include_once "Controllers\UserController.php";
include_once "Core\Request.php";
include_once 'Services\Auth.php';
include_once "Repositories\Repository.php";
session_start();

class App
{
    private array $routeList = [];

    public static function ControllerCall(Request $request): string
    {

        $req = explode("/", $request->getPathInfo());
        $count = count($req);

        if (2 < $count && $count < 5) {
            $contr = ucfirst($req[1]) . 'Controller';
            $action = $req[2];
            $par = ($count == 4) ? $req[3] : null;
        } else {
            return 'Некорректный Api';
        }
        if (!class_exists($contr)) return 'Controller is incorrect';
        if (!self::actionCheck($contr, $action)) return 'Action is incorrect';
        if (self::paramActionCheck($contr, $action) == 0 && $count == 4) return 'This parameter is not supported by action';
        if (!is_null($par) && !is_numeric($par)) return 'Parameter is incorrect';
        UserRepository::checkAdminPermission();
       var_dump((new $contr)->$action($par,$request));
        /*
         * Вызов контроллера
         *
         *
         *
         */

        return 'All is correct';
    }

    public static function actionCheck($class, $action): bool
    {
        $q = new ReflectionClass($class);
        return $q->hasMethod($action);
    }

    public static function paramActionCheck($class, $action)
    {
        $r = new ReflectionMethod($class, $action);
        $params = $r->getParameters();
        return count($params);
    }
}

/* формируем запрос из массива
 * $criteria=['login'=>'Admin','email'=> 'admin@gmail.com', 'password'=> '123456'];
foreach (array_keys($criteria) as $key) {
    $send[]='`'.$key.'` = :'.$key;
}
$query = "SELECT * FROM `users` where ".implode(' && ',$send);
*/

//$sth =DB::getInstance()->getConnection()->prepare("SELECT * FROM `users` WHERE `login` = :login && `password` = :password");
//$sth->execute($criteria);
//var_dump( $query);


/*
 список полей в БД
$sth = DB::getInstance()->getConnection()->prepare("SHOW COLUMNS FROM `users`");
$sth->execute();
$array = $sth->fetchAll(PDO::FETCH_ASSOC);
print_r(array_column($array,'Field'));
*/
//if (isset($_SESSION['Auth'])) {
   // echo 'you are authorized';
    $request = new Request();
  var_dump(App::ControllerCall($request));

//} else echo 'You are not authorized';

//Auth::logout();
//Auth::login('User3','333333');
//var_dump($req->getRoute());

/*if (key_exists($req->getRoute(), $routeList)) {
    var_dump($routeList[$req->getRoute()][$req->getMethod()]);

}*/