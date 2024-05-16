<?php
include_once 'Controllers\admin\UserController.php';

class Request
{
    public string $contrName;
    public string $actionName;
    public ?string $parName;
    private array $storage; // переменная хранящая данные GET и POST

    public function __construct(string $controller, string $actionName, ?string $parName)
    {
        if ($this->getMethod() === 'PUT') {
            parse_str(file_get_contents('php://input'), $put);
            $this->storage = $put;
        } else   $this->storage = $_REQUEST;
        $this->contrName = $controller;
        $this->actionName = $actionName;
        $this->parName = $parName;

    }

    public static function getRequest(): self|null
    {
        $url = explode("/", $_SERVER['REQUEST_URI']);
        if (count($url) < 2 or count($url) > 5) return null; // проверка длины запроса
        if (class_exists($url[1] . '\\' . ucfirst($url[2]) . "Controller")) { // проверка наличия контроллера вида Namespace\Controller\Action
            $name = $url[1] . '\\' . ucfirst($url[2]) . "Controller";
            $action = $url[3];
            $par = (count($url) == 5) ? $url[4] : null;
        } elseif (ucfirst($url[1]) . "Controller") { // проверка наличия контроллера вида \Controller\Action
            $name = ucfirst($url[1]) . "Controller";
            $action = $url[2];
            $par = (count($url) == 4) ? $url[3] : null;
        } else return null;// возврашаем ноль, т.к. контроллера не существует, запрос некорректный
        if (!self::actionCheck($name, $action)) return null; // экшна не существует
        if (!self::paramActionCheck($name, $action) == isset($par)) return null; //  не установлен параметр в экшн, или стои там где его не дб
        if (isset($par) && !is_numeric($par)) return null; //  параметр не числовой, все экшны принимают число
        return new Request($name, $action, $par);

    }


    private static function actionCheck($class, $action): bool
    {
        $q = new ReflectionClass($class);
        return $q->hasMethod($action);
    }

    private static function paramActionCheck($class, $action)
    {
        $r = new ReflectionMethod($class, $action);
        $params = $r->getParameters();
        var_dump(count($params));
        if (count($params) > 0) {
            if ($params[0]->getName() == 'id') return true;
        }
        return false;
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

