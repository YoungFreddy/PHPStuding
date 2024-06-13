<?php

class UserRepository extends Repository
{

    public static $TABLE_NAME = '`users`';
    public static $MODEL_NAME = 'UserModel';


    public static function delete($id): bool
    {

        $query = "DELETE FROM `users` WHERE id = :id ";
        var_dump($query);
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->bindParam('id', $id);
        $sth->execute();
        return true;
    }
}