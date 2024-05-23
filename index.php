<?php

include_once "Controllers\UserController.php";
include_once "Controllers\FilesController.php";
include_once "Core\Request.php";
include_once 'Services\Auth.php';
include_once "Repositories\Repository.php";
session_start();

class App
{
    private array $routeList = [];

    public static function ControllerCall(): string
    {
        $auth=1;

        //var_dump($_FILES);
        $request = Request::getRequest();
       if (isset($request)) {
           $contr = $request->contrName;
           $act = $request->actionName;
           $par = $request->parName;
           if (isset($par))
           var_dump((new $contr)->$act($par, $request));
           else var_dump((new $contr)->$act($request));
       }
           // var_dump(admin\UsersController::list());

        /*
         * Вызов контроллера
         *
         *
         *
         */

        return 'All is correct';
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
App::ControllerCall();
//var_dump(App::ControllerCall($request));

//} else echo 'You are not authorized';

//Auth::logout();
//Auth::login('User3','333333');
//var_dump($req->getRoute());

/*if (key_exists($req->getRoute(), $routeList)) {
    var_dump($routeList[$req->getRoute()][$req->getMethod()]);

}*/