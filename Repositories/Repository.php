<?php


abstract class Repository
{
    static public $TABLE_NAME = "";
    static public $MODEL_NAME = "";

    public static function findAll(?array $criteria = null): array|null
    {
        if ($criteria === null) {
            $query = 'SELECT * FROM ' . static::$TABLE_NAME . ' where `is_deleted` = 0';
        } else {
            foreach (array_keys($criteria) as $key) {
                $send[] = '`' . $key . '` = :' . $key;
            }
            $query = 'SELECT * FROM ' . static::$TABLE_NAME . ' where ' . implode(' && ', $send) . '&& `is_deleted` = 0';
        }

        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->execute($criteria);
        $list = $sth->fetchAll(PDO::FETCH_NAMED);
        foreach ($list as $row) {
            $Array[] = new (static::$MODEL_NAME)($row);
        }
        if (isset($Array)) return $Array;
        return null;
    }

    public static function findOneBy(array $criteria): Model|null
    {
        foreach (array_keys($criteria) as $key) {
            $send[] = '`' . $key . '` = :' . $key;
        }
        $query = 'SELECT * FROM ' . static::$TABLE_NAME . ' where ' . implode(' && ', $send) . ' && `is_deleted` = 0';
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->execute($criteria);
        $row = $sth->fetch(PDO::FETCH_NAMED);
        if ($row) return new  (static::$MODEL_NAME) ($row);

        return null;
    }

    public static function update(Model $model): bool
    {
        foreach (array_keys($model->main) as $key) {
            $send[] = '`' . $key . '` = :' . $key;
        }
        $query = "UPDATE " . static::$TABLE_NAME . " SET " . implode(' , ', $send) . " WHERE " . static::$TABLE_NAME . ".`id` = :id ;";
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->bindParam('id', $model->main['id']);
        return  $sth->execute($model->main);
    }


    public static function insert(Model $model): bool
    {
        foreach (array_keys($model->main) as $key) {
            $send1[] = '`' . $key . '`';
            $send2[] = ':' . $key;
        }
        $query = "INSERT INTO " . static::$TABLE_NAME . "(" . implode(' , ', $send1) . ") VALUES (" . implode(',', $send2) . ")";
        $stmt = DB::getInstance()->getConnection()->prepare($query);
        $stmt->execute($model->main);
        return true;
    }

}




