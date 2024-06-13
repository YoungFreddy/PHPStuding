<?php

class FilesRepository extends Repository
{
    public static $TABLE_NAME = 'files';
    public static $MODEL_NAME = 'FileModel';

    public static function deleteSubFiles(int $owner_id): bool
    {
        $query = 'UPDATE ' . static::$TABLE_NAME . ' JOIN ' . DirectoryDB::$TABLE_NAME . '  ON ' . static::$TABLE_NAME . '.dir_id = '
            . DirectoryDB::$TABLE_NAME . '.id SET ' . static::$TABLE_NAME . '.is_deleted = 1 
         WHERE ' . DirectoryDB::$TABLE_NAME . '.is_deleted = 1 AND '.static::$TABLE_NAME.'.owner_id = ' . $owner_id;
        $sth = DB::getInstance()->getConnection()->prepare($query);
        $sth->execute();
        return true;
    }


}
