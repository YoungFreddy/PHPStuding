<?php

class Request
{

    private array $storage; // переменная хранящая данные GET и POST

    public function __construct()
    {
        if ($this->getMethod() === 'PUT') {
            parse_str(file_get_contents('php://input'), $put);
            $this->storage = $put;
        } else   $this->storage = $_REQUEST;
    }

    public function getData(): array
    {
        return $this->storage;
    }

    /*    public function __get($name)
        {
            if (isset($this->storage[$name])) {
                return $this->storage[$name];
                    } else return null;
        }
    */


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
