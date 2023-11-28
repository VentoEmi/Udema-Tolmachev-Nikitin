<?php

namespace Src\middleware;

use Closure;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        if (isset($_SESSION['user_id'])){
            return $next($request);
        }
        return new RedirectResponse('/login');
    }
}