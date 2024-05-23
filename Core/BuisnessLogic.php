<?php

class Business
{
    public static function shortUser(UserModel $userModel): array
    {

        return array($userModel->main['login'], $userModel->main['email'], $userModel->main['name']);
    }

    public static function userInfo(int $id): array|null
    {
        return self::shortUser(UserRepository::findOneBy(['id' => $id]));
    }

    public static function allUsersInfo(): array|null
    {
        $users = UserRepository::findAll();
        $users_array = [];
        foreach ($users as $user) {
            $users_array[] = self::shortUser($user);
        }
        return $users_array;
    }

    public static function editUser(int $id, Request $req): bool
    {
        $id = 0; //$_SESSION['self_id']; пока вручную, будем получать из сессии
        $requestPar = $req->getData();
        $user = UserRepository::findOneBy(['id' => $id]);
        foreach ($requestPar as $key => $value) {
            $user->main[$key] = $value;
        }
        return UserRepository::update($user);

    }

    public static function deleteUser(int $id): bool
    {
        return UserRepository::delete($id);
    }

    public static function addFile(Request $req): bool
    {
        //$self_id = $_SESSION['self_id'];
        $self_id = 0;
        if ($req->getMethod() != 'POST' && !$_FILES) return false;
        $file = array_values($_FILES);
        if ($file[0]["error"] != UPLOAD_ERR_OK) return false;
        $name = ".\Repositories\Upload\\" . $file[0]["name"] . '-' . time();
        //print_r($name);
        move_uploaded_file($file[0]["tmp_name"], $name);

        $info = [
            'id' => null,
            'file_name' => $name,
            'owner_name' => UserRepository::findOneBy(['id' => $self_id])->main['name'],
            'owner_id' => $self_id,
            'date' => date("Y-m-d H:i:s"),
            'path' => 'root\\',
            'is_deleted' => false
        ];
        FilesRepository::insert(new FileModel($info));
        echo "Файл загружен";

        return true;
    }

    public static function allFilesInfo(): array|null
    {
        $files = FilesRepository::findAll();
        $files_array = [];
        foreach ($files as $file) {
            $files_array[] = self::shortFile($file);
        }
        return $files_array;
    }

    private static function fakeFilename(string $name): string
    {
        return substr($name,0,strripos($name,'-'));
    }

    public static function shortFile(FileModel $file): array
    {
        $name = $file->main['file_name'];
        return array($file->main['id'],self::fakeFilename($name), $file->main['owner_name'], $file->main['date'],$file->main['path']);
    }

    public static function fileInfo(int $id):array
    {
        $file = FilesRepository::findOneBy(['id' => $id]);
        if ($file->main['is_deleted']) return self::shortFile($file);
        else return ['Файл удален'];
    }

}