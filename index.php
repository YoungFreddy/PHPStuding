<?php

session_start();
spl_autoload_register('autoload');

function autoload($class): void
{
    $folderList = ['Controllers', 'Core', 'Core\\Domain', 'model', 'Repositories', 'Services'];
    foreach ($folderList as $folder) {

        $file = __DIR__ . '\\' . $folder . '\\' . $class . ".php";
        //var_dump($file);
        if (file_exists($file)) {
            require_once $file;
        }
    }
}

class App
{
    private static array $adminControllers = ['admin\UsersController'];

    public static function ControllerCall(): string
    {

        $request = Request::getRequest();
        if (is_null($request->actionName)) return $request->message;

        if (!isset($_SESSION['self_id']) && $request->getPathInfo() != '/users/login' && $request->getPathInfo() != '/users/reg')
            return 'Авторизуйтесь при помощи users/login или зарегистрируйтесь при помощи users/reg';
        if (isset($_SESSION['auth']) && $_SESSION['auth'] == 0 && in_array($request->contrName, self::$adminControllers)) return 'Нет права доступа';

        $contr = $request->contrName;
        $act = $request->actionName;
        $par = $request->par;

        if (isset($par) && count($par) == 1)
            var_dump((new $contr)->$act($par[0], $request));
        elseif (isset($par) && count($par) == 2) var_dump((new $contr)->$act($par[0], $request, $par[1]));
        else var_dump((new $contr)->$act($request));

        return 'All is correct';
    }

}
try {
    var_dump(App::ControllerCall());
} catch (Exception $e) {
    echo 'PHP перехватил исключение: ',  $e->getMessage(), "\n";
}


