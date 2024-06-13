<?php
include_once 'Controllers\admin\UsersController.php';

class Request
{
    public ?string $contrName;
    public ?string $actionName;
    public ?array $par;

    public ?string $message;
    private array $storage; // переменная хранящая данные GET и POST

    public function __construct(?string $controller, ?string $actionName, ?array $par, ?string $message)
    {
        if ($this->getMethod() === 'PUT') {
            parse_str(file_get_contents('php://input'), $put);
            $this->storage = $put;
        } else $this->storage = $_REQUEST;
        $this->contrName = $controller;
        $this->actionName = $actionName;
        $this->par = $par;
        $this->message = $message;

    }

    public static function getRequest(): self
    {
        $req = new Request(null, null, null, null);
        $url = explode("/", $_SERVER['REQUEST_URI']);
        //if (count($url) < 2 or count($url) > 5) return null; // проверка длины запроса
        if (class_exists($url[1] . '\\' . ucfirst($url[2]) . "Controller")) { // проверка наличия контроллера вида Namespace\Controller\Action
            $name = $url[1] . '\\' . ucfirst($url[2]) . "Controller";
            var_dump($name);
            $action = $url[3];
            $par = array_slice($url, 4);
        } elseif (class_exists(ucfirst($url[1]) . "Controller")) { // проверка наличия контроллера вида \Controller\Action
            $name = ucfirst($url[1]) . "Controller";
            $action = $url[2];
            $par = array_slice($url, 3);
        } else {
            $req->message = 'Контроллер не существует';
            return $req;
        }// возврашаем ноль, т.к. контроллера не существует, запрос некорректный

        if (!self::actionCheck($name, $action)) {
            $req->message = 'Экшн не существует';
            return $req;
        } // экшна не существует
        $parCount = self::paramActionCheck($name, $action);
        if (!($parCount[0] == count($par) || $parCount[1] == count($par))) {  // проверяем соотвествеи передавемых id (обязательные и необязательные)
            $req->message = 'Несоответсвие передамаевого параметра';
            return $req;
        }; //  не установлен параметр в экшн, или стои там где его не дб

        if (count($par) && in_array('0', array_map('is_numeric', $par))) {
            $req->message = 'Поддерживается только числовые параметры';
            return $req;
        }; //  параметр не числовой, все экшны принимают число

        return new Request($name, $action, $par, 'Запрос корректен');

    }


    private static function actionCheck($class, $action): bool
    {
        $q = new ReflectionClass($class);
        return $q->hasMethod($action);
    }

    private static function paramActionCheck($class, $action): array
    {
        $r = new ReflectionMethod($class, $action);
        $params = $r->getParameters();
        $i = 0;
        $k = 0;
        if (count($params) > 0) {
            foreach ($params as $param) {
                if ($param->getType() == 'int') {
                    $i++;
                    if ($param->isOptional()) $k++;
                }
            }

        }
        return [$i, $i - $k];
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

