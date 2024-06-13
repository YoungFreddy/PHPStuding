<?php

namespace Domain;

use DirectoryDB;
use FileModel;
use FilesRepository;
use Request;
use Response;
use UserRepository;

class FileDomain
{
    public static function addFile(Request $req): Response
    {
        $self_id = $_SESSION['self_id'];
        if ($req->getMethod() != 'POST' && !$_FILES) return new Response(false, 'Метод передачи не соответсвует или отсутсвует файл');
        $file = array_values($_FILES);
        if ($file[0]["error"] != UPLOAD_ERR_OK) return new Response(false, 'Возникла ошибка: ' . $file[0]['error']);
        if ($file[0]['size'] > 1024 * 1024 * 200) return new Response(false, 'Размер файла превышает 200 Мб');
        $name = $file[0]["name"] . '-' . time();
        $fullName = ".\Repositories\Upload\\" . $name;
        move_uploaded_file($file[0]["tmp_name"], $fullName);

        $info = [
            'id' => null,
            'file_name' => $name,
            'owner_name' => UserRepository::findOneBy(['id' => $self_id])->main['name'],
            'owner_id' => $self_id,
            'date' => date("Y-m-d H:i:s"),
            'shared_for' => null,
            'is_deleted' => false
        ];

        if (isset($req->getData()['dir_id'])) {
            $folder = DirectoryDB::findOneBy(['id' => $req->getData()['dir_id'], 'owner_id' => $self_id]);
            if (!$folder) {
                return new Response(false, 'Directory not found');
            } else $info['dir_id'] = $folder->main['id'];
        } else $info ['dir_id'] = DirectoryDB::findOneBy(['parent_id' => 0, 'owner_id' => $self_id])->main['id'];

        return new Response(FilesRepository::insert(new FileModel($info)), 'Файл загружен');
    }

    public
    static function allFilesInfo(): Response
    {
        $files = FilesRepository::findAll(['owner_id' => $_SESSION['self_id']]);
        if (is_null($files)) return new Response(false, 'Files repository is empty');
        $files_array = [];
        foreach ($files as $file) {
            $files_array[] = self::shortFile($file);
        }
        return new Response(true, 'Success', $files_array);
    }

    private
    static function fakeFilename(string $name): string
    {
        return substr($name, 0, strripos($name, '-'));
    }

    public
    static function shortFile(FileModel $file): array
    {
        $name = $file->main['file_name'];
        return array('id' => $file->main['id'], 'name' => self::fakeFilename($name), 'owner' => $file->main['owner_name'], 'created' => $file->main['date'],
            'path' => DirectoryDB::findOneBy(['id' => $file->main['dir_id']])->main['path']);
    }

    public
    static function fileInfo(int $id): Response
    {
        $file = FilesRepository::findOneBy(['id' => $id]);
        if (!$file) return new Response(false, 'File not found');
        if ($file->main['is_deleted']) return new Response(false, 'File is deleted');
        return new Response(true, 'Success', self::shortFile($file));
    }

    public
    static function editName(int $id, Request $req): Response
    {
        $userId = $_SESSION['self_id'];
        $requestPar = $req->getData();
        if (\Secondary::check($requestPar['file_name'])) return new Response(false, 'Incorrect file name');
        $file = FilesRepository::findOneBy(['id' => $id]);
        if (!$file) return new Response(false, 'File not found');
        if ($file->main['owner_id'] != $userId) return new Response(false, 'You dont have permissions');
        rename(".\Repositories\Upload\\" . $file->main['file_name'], ".\Repositories\Upload\\" . $requestPar['file_name'] . '-' . time());
        $file->main['file_name'] = $requestPar['file_name'] . '-' . time();;
        return new Response(FilesRepository::update($file), 'Success');
    }


    public
    static function deleteFile(int $id): Response
    {
        $file = FilesRepository::findOneBy(['id' => $id]);
        if (!$file) return new Response(false, 'File not found');
        $file->main['is_deleted'] = true;
        return new Response(FilesRepository::update($file), 'Success');
    }

    public static function shareOperations(int $id, Request $req, int $user_id = 0): Response
    {
        $file = FilesRepository::findOneBy(['id' => $id, 'owner_id' => $_SESSION['self_id']]);
        $method = $req->getMethod();
        if (!$file) return new Response(false, 'File not found');
        switch ($method) {
            case'GET':
                $userList = [];
                $list = $file->main['shared_for'];
                if (is_null($list)) $shareList = [];
                else $shareList = unserialize($list);
                foreach ($shareList as $share) {
                    $userList[$share] = (UserRepository::findOneBy(['id' => $share]))->main['id'] . " - " .
                        (UserRepository::findOneBy(['id' => $share]))->main['name'];
                }
                if (count($userList) == 0) return new Response(true, 'Users list is empty');
                return new Response(true, 'Success', $userList);

            case 'PUT':
                if ($user_id == 0) return new Response(false, 'Не указан id пользователя');
                if (!UserRepository::findOneBy(['id' => $user_id])) return new Response(true, 'User not found');
                $arr = $file->main['shared_for'];
                if (isset($arr)) $shareList = unserialize($arr);
                else $shareList = [];
                if (in_array($user_id, $shareList)) return new Response(false, 'File already shared for this user');
                $shareList[] = $user_id;
                $file->main['shared_for'] = serialize($shareList);
                return new Response(FilesRepository::update($file), 'Success');

            case 'DELETE':
                if ($user_id == 0) return new Response(false, 'Не указан id пользователя');
                $serList = $file->main['shared_for'];
                if (is_null($serList)) return new Response(false, 'List of users is empty');
                $list = unserialize($serList);
                if (in_array($user_id, $list)) {
                    unset($list[array_search($user_id, $list)]);
                    $shareList = array_values($list);
                    $file->main['shared_for'] = serialize($shareList);
                    return new Response(FilesRepository::update($file), 'Succes');
                } else return new Response(false, 'File was not shared for this user');
            default:
                break;
        }
        return new Response(false, 'Wrong http-method');
    }

    public static function downloadFile(int $fileId, Request $req): Response
    {
        $file = FilesRepository::findOneBy(['id' => $fileId]);
        if (!$file) return new Response(false, 'File not found');
        $list = $file->main['shared_for'];
        if (is_null($list)) $shareList = [];
        else $shareList = unserialize($list);
        if ((is_null($shareList) || !in_array($_SESSION['self_id'], $shareList))
            && $file->main['owner_id'] != $_SESSION['self_id']) return new Response(false, 'You dont have permissions');
        readfile(".\Repositories\Upload\\" . $file->main['file_name']);
        return new Response(true, 'Download is completed');
    }

}
