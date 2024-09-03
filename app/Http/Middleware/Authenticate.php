<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Auth\AuthenticationException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    protected function authenticate($request, array $guards)
{
    if (empty($guards)) {
        $guards = [null];
    }

    foreach ($guards as $guard) {
        try {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        } catch (JWTException $e) {
            // Handle the JWT exception
            if ($e instanceof TokenInvalidException) {
                // Le jeton JWT est invalide
                throw new AuthenticationException('Invalid JWT token');
            } elseif ($e instanceof TokenExpiredException) {
                // Le jeton JWT a expiré
                throw new AuthenticationException('JWT token has expired');
            } else {
                // Une autre erreur liée au jeton JWT s'est produite
                throw new AuthenticationException('Error processing JWT token');
            }
        }
    }

    throw new AuthenticationException('Unauthenticated.');
}
}
