<?php


include_once 'Core\DB.php';
include_once 'model\UserModel.php';
include_once 'Core\BuisnessLogic.php';

class FilesController
{

    public static function get(int $id): array
    {
        return Business::fileInfo($id);
    }

    public static function list(): array
    {
        return Business::allFilesInfo();
    }

    public static function rename(Request $req): bool
    {
        //  return Business::editFile($_SESSION['self_id'], $req)
        return Business::editName(1, $req);
    }

    public static function add(Request $req): bool
    {
        return Business::addFile($req);
    }
    public static function remove(int $id): bool
    {
        return Business::deleteFile($id);
    }


}

class DirectoriesController
{
    public static function add(Request $req): bool
    {
        return Business::addDirectory($req);
    }
    public static function rename(Request $req): bool
    {
        //  return Business::editFile($_SESSION['self_id'], $req)
        return Business::editDirName(2, $req);
    }

    public static function delete(int $id): bool
    {
        //  return Business::editFile($_SESSION['self_id'], $req)
        return Business::deleteFolder(2);
    }

    public static function get(int $id): array|bool
    {
        //  return Business::editFile($_SESSION['self_id'], $req)
        return Business::folderInfo(2);
    }

}
