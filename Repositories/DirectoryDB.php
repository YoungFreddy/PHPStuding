<?php


class DirectoryDB extends Repository
{
    public static $TABLE_NAME = 'directories';
    public static $MODEL_NAME = 'DirectoryModel';

    public static function updateAllPaths(string $oldName, string $newName, int $owner_id): bool
    {
        $query = 'UPDATE ' . static::$TABLE_NAME . ' SET path = REGEXP_REPLACE(path,\'^'.preg_quote(preg_quote($oldName)).'\\\\\\\\\',\''
            .preg_quote( preg_quote($newName.'\\')) . '\')' . ' WHERE owner_id = ' . $owner_id;
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->execute();

        $query1 = 'UPDATE ' . static::$TABLE_NAME . ' SET path = REGEXP_REPLACE(path,\'^'.preg_quote(preg_quote($oldName)).'$\',\''
            .preg_quote( preg_quote($newName)) . '\')' . ' WHERE owner_id = ' . $owner_id;
        $sth = DB::getInstance()->getConnection()->prepare($query1);
        $sth->execute();
        return true;
    }

    public static function deleteSubFolders(string $path, int $owner_id): bool
    {
        $query = 'UPDATE ' . static::$TABLE_NAME . ' SET is_deleted = 1 
         WHERE owner_id = ' . $owner_id . ' AND path REGEXP \'^'.preg_quote(preg_quote($path)).'\\\\\\\\\'';
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->execute();
        return true;
    }
}
