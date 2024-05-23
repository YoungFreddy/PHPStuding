<?php


class UserModel
{

    public $main = [
        'id'=>null,
        'login'=>null,
        'password'=>null,
        'email'=>null,
        'name'=>null,
        'role'=>null
    ];

    public function __construct(array $fetch)
    {
        foreach ($fetch as $key => $value) {
            $this->main[$key] = $value;
        }
    }

}


class FileModel
{

    public $main = [
        'id'=>null,
        'file_name'=>null,
        'owner_name'=>null,
        'owner_id'=>null,
        'date'=>null,
        'path'=>null,
        'is_deleted'=>null
    ];

    public function __construct(array $fetch)
    {
        foreach ($fetch as $key => $value) {
            $this->main[$key] = $value;
        }
    }

}

