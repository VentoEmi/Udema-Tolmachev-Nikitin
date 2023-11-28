<?php

namespace Src\Controllers;

use Laminas\Diactoros\ServerRequest;
use ORM;

class UserController
{

    public function updateUserInfo(ServerRequest $request)
    {
        $params = $request->getParsedBody();
        $user = ORM::forTable('users')->find_one($_SESSION['user_id']);


    }

}