<?php

class Model
{



}


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