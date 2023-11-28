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
        if (md5($params['old_password']) == $user['password'] && $params['old_password'] !== $params['new_password']){
            if ($params['new_password'] == $params['confirm_password']){
                if (!empty($params['new_password'])){
                    $user->set('password', md5($params['new_password']));
                }
                else $user->set('password', $user['password']);
//                if ($params[''])
            }
        }

    }

}