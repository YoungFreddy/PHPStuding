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

    public static function update(Request $req): bool
    {
        return Business::editFile($_SESSION['self_id'], $req);
    }

    public static function add(Request $req): bool
    {
        return Business::addFile($req);
    }
}
