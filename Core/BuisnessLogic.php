<?php

class AccessLeveling
{
    public static function setUserModel(UserModel $userModel):array
    {
        // if ( $adminPermissiom == true)
        //return $userModel->main;
         return array($userModel->main['login'],$userModel->main['email'],$userModel->main['name']);
    }

    public static function userInfo(int $id):array|null
    {
        return self::setUserModel(UserRepository::findOneBy(['id' => $id]));
    }

    public static function allUsersInfo():array|null
    {
        $users = UserRepository::findAll();
        $users_array=[];
        foreach ($users as $user) {
            $users_array[] = self::setUserModel($user);
        }
        return $users_array;
    }

    public static function editUser(Request $req):array
    {
        $id = explode("/", $req->getPathInfo());
        // if ($_SESSION['Auth']==0) {
        //   $id=$_SESSION['id'
        $requestPar= $req->getData();
        $user = UserRepository::findOneBy(['id' => $id]);
        foreach ($requestPar as $key=> $value ) {
            $user->main[$key] = $value;
        }
        //$updateQuery = array_replace(self::get($id), $req->getData());
        return UserRepository::update($user);

    }


}