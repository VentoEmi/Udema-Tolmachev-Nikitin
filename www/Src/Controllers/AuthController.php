<?php

namespace Src\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use ORM;

class AuthController
{
    public function registrationPage(View $view)
    {
        return $view->make('Auth.register');
    }
    public function loginPage(View $view)
    {
        return $view->make('Auth.login');
    }
    public function registration(ServerRequest $request)
    {
        $params = $request->getParsedBody();
        if($params['password'] == $params['confirm_password']){
            ORM::forTable('users')->create(['name'=>$params['name'],
                'last_name'=>$params['last_name'],
                'mail'=>$params['mail'],
                'is_admin'=>false,
                'password'=>md5($params['password'])])->save();
            return new RedirectResponse('/');
        }
        else return new RedirectResponse('/register');
    }

    public function login(ServerRequest  $request)
    {
        $params = $request->getParsedBody();
        $user = ORM::forTable('users')->where('mail',$params['mail'])
            ->where('password',md5($params['password']))->find_one();
        if ($user && $user->is_admin == 1){
            $_SESSION['user_id'] = $user->id;
            return new RedirectResponse('/admin');
        }
        else if ($user && $user->is_admin == 0){
            $_SESSION['user_id'] = $user->id;
            return new RedirectResponse('/');
        }
        else return new RedirectResponse('/login');
    }
}