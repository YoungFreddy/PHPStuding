<?php

class DirDomain
{
    public static function addDirectory(Request $req): Response
    {
        $request = $req->getData();
        if (isset($request['name'])) $name = $request['name'];
        else return new Response(false, 'Directory name is not set');
        if (\Secondary::check( $request['name'])) return new Response(false,'Incorrect directory name');
        if (isset($request['parent_id'])) {
            $parentFolder = $request['parent_id'];
            if (is_numeric($parentFolder)) {
                $parent = DirectoryDB::findOneBy(['id' => $parentFolder, 'owner_id' => $_SESSION['self_id']]);
                if (!$parent) return new Response(false, 'Parent Directory does not exist');
                else $parentDir = $parent->main['path'];
            }
        } else {
            $parentFolder = 0;
            $parentDir = 'root';
        }
        $dir = DirectoryDB::findOneBy(['path' => $parentDir . DIRECTORY_SEPARATOR . $name, 'parent_id' => $parentFolder, 'owner_id' => $_SESSION['self_id']]);
        if ($dir) return new Response(false, 'Directory already exists');
        $arg = [
            'id' => null,
            'path' => $parentDir . DIRECTORY_SEPARATOR . $name,
            'owner_id' => $_SESSION['self_id'],
            'parent_id' => $parentFolder,
            'is_deleted' => false
        ];
        $newDir = new DirectoryModel($arg);
        return new Response(DirectoryDB::insert($newDir), 'Success');
    }

    public
    static function editDirName(int $id, Request $req): Response
    {
        $userId = $_SESSION['self_id'];
        $requestPar = $req->getData();
        if (\Secondary::check( $requestPar['name'])) return new Response(false,'Incorrect directory name');
        $folder = DirectoryDB::findOneBy(['id' => $id, 'owner_id' => $userId]);
        if (!$folder) return new Response(false, 'Folder not found');
        $oldName = $folder->main['path'];
        if ($oldName == 'root') return new Response(false, 'Default directory cannot be changed');
        $parentDir = DirectoryDB::findOneBy(['id' => $folder->main['parent_id']])->main['path'];
        $newName = $parentDir . DIRECTORY_SEPARATOR . $requestPar['name'];
        if (DirectoryDB::findOneBy(['path' => $newName])) return new Response(false, 'Folder already exists');
        $folder->main['path'] = $newName;
        return new Response(DirectoryDB::updateAllPaths($oldName, $newName, $userId), 'Success');
    }

    public
    static function deleteFolder(int $id): Response
    {
        $self_id = $_SESSION['self_id'];
        $folder = DirectoryDB::findOneBy(['id' => $id, 'owner_id' => $self_id]);
        if (!$folder) return new Response(false, 'Directory does not exist');
        if ($folder->main['path'] == 'root') return new Response(false, 'Default directory cannot be deleted');
        $folder->main['is_deleted'] = true;
        DirectoryDB::update($folder);
        DirectoryDB::deleteSubFolders($folder->main['path'],$self_id);
        return new Response(FilesRepository::deleteSubFiles($self_id), 'Success');
    }

    public
    static function FolderInfo(int $id): Response
    {
        $folder = DirectoryDB::findOneBy(['id' => $id, 'owner_id' => $_SESSION['self_id']]);
        if (!$folder) return new Response(false, 'Directory does not exist');
        $result = FilesRepository::findAll(['dir_id' => $id]);
        $resultDir = DirectoryDB::findAll(['parent_id' => $id]);
        if (is_null($result) && is_null($resultDir)) return new Response(false, 'Directory is empty');
        return new Response(true, 'Success', ['files' => $result, 'subdirectories' => $resultDir]);

    }

    public
    static function allFolderInfo(): Response
    {
        $result = DirectoryDB::findAll(['owner_id' => $_SESSION['self_id']]);
        if ($result) return new Response(true, 'Success', $result);
        return new Response(false, 'Only root directory exist');
    }
}
