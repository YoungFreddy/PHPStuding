<?php
include_once 'Controllers\admin\UserController.php';

class Request
{

    private array $url;
    public string $contrName;
    public string $actionName;
    public string $parName;
    private array $storage; // переменная хранящая данные GET и POST

    public function __construct()
    {
        if ($this->getMethod() === 'PUT') {
            parse_str(file_get_contents('php://input'), $put);
            $this->storage = $put;
        } else   $this->storage = $_REQUEST;
        $this->url = explode("/", $this->getPathInfo());
        $this->contrName = $this->controller();
        $this->actionName = $this->action($this->contrName);
        $this->parName = $this->parameter($this->contrName);

    }

    public function controller(): string|bool
    {   //var_dump($this->url[1].'\\'.ucfirst($this->url[2])."Controller");
        if (class_exists($this->url[1] . '\\' . ucfirst($this->url[2]) . "Controller")) {
            $name = $this->url[1] . '\\' . ucfirst($this->url[2]) . "Controller";
        } else $name = ucfirst($this->url[1]) . "Controller";

        return $name;
    }

    public function action(string $contr): string|bool
    {
        if (stripos($contr, '\\')) {
            $action = $this->url[3];
        } else $action = $this->url[2];

        return $action;
    }

    public function parameter(string $contr): int|bool
    {
        if (stripos($contr, '\\')) {
            $par = $this->url[4];
        } else $par = $this->url[3];
        return $par;
    }

    /*  public function checkRequest(): string
      {
          $adminRepository=false;


          if ($req[1]=='admin') {
              unset($req[1]);
              $req=array_values($req);
              $adminRepository = true;
          }
          $count = count($req);
          if (2 < $count && $count < 5) {
              $this->controller = ucfirst($req[1]) . 'Controller';
              $this->action = $req[2];
              $this->parameter = ($count == 4) ? $req[3] : null;
          } else {
              return 'Некорректный Api';
          }
          if (!class_exists($contr)) return 'Controller is incorrect';
          if (!self::actionCheck($contr, $action)) return 'Action is incorrect';
          if (self::paramActionCheck($contr, $action) == 0 && $count == 4) return 'This parameter is not supported by action';
          if (!is_null($par) && !is_numeric($par)) return 'Parameter is incorrect';
          if ($auth == 0 && $adminRepository) return  'You dont have permission to access this page';
          UserRepository::setAdminPermission();
      }*/

    private static function actionCheck($class, $action): bool
    {
        $q = new ReflectionClass($class);
        return $q->hasMethod($action);
    }

    private static function paramActionCheck($class, $action)
    {
        $r = new ReflectionMethod($class, $action);
        $params = $r->getParameters();
        return count($params);
    }

    public function getData(): array
    {
        return $this->storage;
    }


    public function getPathInfo(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public
    function getRoute(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

}
//$request = new Request();
//$request->getPathInfo();
//var_dump($request->getMethod());
//var_dump($request->getRoute());
