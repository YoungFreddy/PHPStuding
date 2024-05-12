<?php


abstract class Repository
{
    public static function findAll()
    {

    }

    public static function findOneBy(array $criteria): UserModel
    {
        return new UserModel($criteria);
    }

}

class UserRepository extends Repository
{
    private static array $adminPermission ;
     public static function checkAdminPermission():void
     {
        // if ($_SESSION['auth']==1)
         self::$adminPermission = [
             'findOneBy'=>'SELECT * FROM `users` where ',
             'findAll'=>"SELECT `login`,`name`,`email` FROM `users`",
             'update'=>'UPDATE `users` set ',
         ];


     }




    public static function findOneBy(array $criteria): UserModel
    {
        foreach (array_keys($criteria) as $key) {
            $send[] = '`' . $key . '` = :' . $key;
        }
        $query = self::$adminPermission[__FUNCTION__] . implode(' && ', $send);
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->execute($criteria);
        return new UserModel($sth->fetch(PDO::FETCH_NAMED));
    }

    public static  function findAll( ?Request $req= null):array
    {
        /*if ($_SESSION['Auth']==1) {
            $query = "SELECT * FROM `users`";
        }else  */ $query =self::$adminPermission[__FUNCTION__];
        $sth = DB::getInstance()->getConnection()-> prepare($query);
        $sth->execute();
        foreach ($sth->fetchAll(PDO::FETCH_NAMED) as $row) {
            $usersArray[] = new UserModel($row);
        }
        return $usersArray;
    }

    public static function update(UserModel $user): bool
    {
        foreach (array_keys($user->main) as $key) {
            $send[] = '`' . $key . '` = :' . $key;
        }
        $query = "UPDATE `users` SET " . implode(' , ', $send)." WHERE `users`.`id` = :id ;";
        var_dump($query);
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->bindParam('id',$user->main['id']);
        $sth->execute($user->main);
        return true;
    }
}