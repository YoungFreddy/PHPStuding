<?php


abstract class Repository
{

    public static function findAll()
    {
    }

    public static function findOneBy(array $criteria)
    {
    }

}

class UserRepository extends Repository
{
    private static array $adminPermission;


    public static function findOneBy(array $criteria): UserModel
    {
        foreach (array_keys($criteria) as $key) {
            $send[] = '`' . $key . '` = :' . $key;
        }
        $query = 'SELECT * FROM `users` where ' . implode(' && ', $send);
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->execute($criteria);
        return new UserModel($sth->fetch(PDO::FETCH_NAMED));
    }

    public static function findAll(?Request $req = null): array
    {
        $query = 'SELECT * FROM `users`';
        $sth = DB::getInstance()->getConnection()->prepare($query);
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
        $query = "UPDATE `users` SET " . implode(' , ', $send) . " WHERE `users`.`id` = :id ;";
        var_dump($query);
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->bindParam('id', $user->main['id']);
        $sth->execute($user->main);
        return true;
    }

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
class FilesRepository extends Repository
{

    public static function findOneBy(array $criteria): FileModel
    {
        foreach (array_keys($criteria) as $key) {
            $send[] = '`' . $key . '` = :' . $key;
        }
        $query = 'SELECT * FROM `files` where ' . implode(' && ', $send);
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->execute($criteria);
        return new FileModel($sth->fetch(PDO::FETCH_NAMED));
    }

    public static function findAll(?Request $req = null): array
    {
        $query = 'SELECT * FROM `files`';
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->execute();
        foreach ($sth->fetchAll(PDO::FETCH_NAMED) as $row) {
            $filesArray[] = new FileModel($row);
        }
        return $filesArray;
    }

    public static function update(FileModel $user): bool
    {
        foreach (array_keys($user->main) as $key) {
            $send[] = '`' . $key . '` = :' . $key;
        }
        $query = "UPDATE `users` SET " . implode(' , ', $send) . " WHERE `users`.`id` = :id ;";
        var_dump($query);
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->bindParam('id', $user->main['id']);
        $sth->execute($user->main);
        return true;
    }

    public static function delete($id): bool
    {

        $query = "DELETE FROM `users` WHERE id = :id ";
        var_dump($query);
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->bindParam('id', $id);
        $sth->execute();
        return true;
    }


    public static function insert(FileModel $file): bool
    {
        foreach (array_keys($file->main) as $key) {
        $send1[] = '`' . $key . '`';
        $send2[] = ':' . $key;
      }
        $query = "INSERT INTO `files` (" . implode(' , ', $send1) .") VALUES (". implode(',', $send2).")";
        $stmt = DB::getInstance()->getConnection()->prepare($query);
        $stmt->execute($file->main);
        return true;

    }
}