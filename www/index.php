<?php

use Laminas\Diactoros\Response\RedirectResponse;
use MiladRahimi\PhpRouter\Router;
use Src\Controllers\AuthController;
use Src\Controllers\CourseController;
use Src\Controllers\MainController;
use Src\Controllers\UserController;
use Src\Middleware\AdminMiddleware;
use Src\Middleware\AuthMiddleware;

require_once 'vendor/autoload.php';

ORM::configure('mysql:host=database;dbname=docker');
ORM::configure('username', 'docker');
ORM::configure('password', 'docker');
session_start();

$router = Router::create();
$router->setupView('Views');

$router->get('/',[MainController::class,'mainPage']);
$router->get('/register',[AuthMiddleware::class,'registrationPage']);
$router->get('/login',[AuthController::class,'loginPage']);
$router->post('/register',[AuthController::class,'registration']);
$router->post('/login',[AuthController::class,'login']);
$router->get('/courses_list',[CourseController::class,'coursesPage']);
$router->get('/course_detail/{course_id}',[CourseController::class,'course_detailPage']);
$router->post('/courses_list',[CourseController::class,'coursesPage']);

$router->group(['middleware' =>[AuthMiddleware::class]],
    function (Router $router) {
        $router->post('/comment/{course_id}',[UserController::class,'comment']);
        $router->get('/user/profile', [UserController::class, 'userProfilePage']);
        $router->post('/user/update', [UserController::class, 'updateUserInfo']);

        $router->group(['middleware' => [AdminMiddleware::class]],
            function (Router $router) {

            });
        $router->get('/session-clear', [UserController::class, 'session_clear']);

    }
);


$router->dispatch();