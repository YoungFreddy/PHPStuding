<?php

class UserModel extends Model
{
    public array $main = [
        'id' => null,
        'login' => null,
        'password' => null,
        'email' => null,
        'name' => null,
        'role' => 0,
        'is_deleted' => 0
    ];

    public function __construct(array $fetch)
    {
        foreach ($fetch as $key => $value) {
            $this->main[$key] = $value;
        }
    }

}