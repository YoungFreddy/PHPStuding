<?php

class DirectoriesController
{
    public static function add(Request $req): Response
    {
        return DirDomain::addDirectory($req);
    }
    public static function rename(int $id, Request $req): Response
    {
        return DirDomain::editDirName($id, $req);
    }

    public static function delete(int $id): Response
    {
        return DirDomain::deleteFolder($id);
    }

    public static function get(int $id): Response
    {
        return DirDomain::folderInfo($id);
    }

    public static function list(): Response
    {
        return DirDomain::allFolderInfo();

    }



}