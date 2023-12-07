<?php

namespace Src\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use ORM;

class UserController
{
    public function session_clear (){
        unset($_SESSION['user_id']);
        return new RedirectResponse('/');
    }
    public function userProfilePage(View $view)
    {
        $user = ORM::forTable('users')->find_one($_SESSION['user_id']);
        return $view->make('udema.user-profile',['user' => $user]);
    }
    public function updateUserInfo(ServerRequest $request)
    {
        $params = $request->getParsedBody();
        $user = ORM::forTable('users')->find_one($_SESSION['user_id']);
        if ($params['old_email'] == $user['mail'] || !$params['old_email']){
            if ($params['new_email'] == $params['confirm_new_email']){
                if (empty($params['new_email'])){
                    $user->set(['mail'=>$user['mail']]);}
                else $user->set(['mail'=>$params['new_email']]);
                if (md5($params['old_password']) == $user['password'] || !$params['old_password']){
                    if ($params['new_password'] == $params['confirm_new_password']){
                        if (empty($params['new_password'])){
                            $user->set(['password'=>$user['password']]);}
                        else $user->set(['password'=>md5($params['new_password'])]);
                        $random = bin2hex(random_bytes(10));
                        $typeFile = explode('.',$request->getUploadedFiles()['photo']->getClientFilename());
                        $randomName = $typeFile[0].$random.time().'.'.$typeFile[1];
                        $request->getUploadedFiles()['photo']->moveTo("/var/www/html/img/". $randomName);
                        $user->set([
                            'name'      => $params['name'],
                            'last_name' => $params['lastname'],
                            'phone'     => $params['phone'],
                            'info'      => $params['info'],
                            'photo_url' => $randomName
                        ]);
                        $user->save();
                        $_SESSION['error'] = 'Successful';
                        return new RedirectResponse('/user/profile');
                    }
                    $_SESSION['error'] = 'Wrong new or confirm password';
                    return new RedirectResponse('/user/profile');
                }
                $_SESSION['error'] = 'Wrong old password';
                return new RedirectResponse('/user/profile');
            }
            $_SESSION['error'] = 'Wrong new or confirm email';
            return new RedirectResponse('/user/profile');
        }
        $_SESSION['error'] = 'Wrong old email';
        return new RedirectResponse('/user/profile');



    }

}