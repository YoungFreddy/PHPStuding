<?php


use Domain\DirDomain;
use Domain\FileDomain;

include_once 'Core\DB.php';
include_once 'model\Model.php';

class FilesController
{

    public static function get(int $id): Response
    {
        return FileDomain::fileInfo($id);
    }

    public static function list(): Response
    {
        return FileDomain::allFilesInfo();
    }

    public static function rename(int $id,Request $req): Response
    {
        return FileDomain::editName($id, $req);
    }

    public static function add(Request $req): Response
    {
        return FileDomain::addFile($req);
    }
    public static function remove(int $id): Response
    {
        return FileDomain::deleteFile($id);
    }
    public static function share(int $id, Request $req, int $user_id = 0):Response
    {

        return FileDomain::shareOperations($id,$req,$user_id);
    }

    public static function download(int $id, Request $req):Response
    {

        return FileDomain::downloadFile($id,$req);
    }
}


