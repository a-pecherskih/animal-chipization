<?php

namespace App\Http\Middleware;

use App\Exceptions\ForbiddenException;
use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CustomBasicAuth
{
    /**
     * The guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @param string|null $field
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException|\App\Exceptions\ForbiddenException
     */
    public function handle(Request $request, Closure $next, $guard = null, $field = null)
    {
        /**
         * Пропускаем авторизацию для роутов 0 уровня
         */
        if (!$request->hasHeader('Authorization') && in_array($request->route()->getName(), config('auth.stage_0_routes'))) {
            return $next($request);
        }

        /**
         * Если регистрация под авторизационным акком, то недопускаем
         */
        if ($request->hasHeader('Authorization') && $request->route()->getName() == 'registration') {
            try {
                $this->auth->guard($guard)->basic($field ?: 'email');

                throw new ForbiddenException();
            } catch (UnauthorizedHttpException $e) {
                return $next($request);
            }
        }

        $this->auth->guard($guard)->basic($field ?: 'email');

        return $next($request);
    }
}
