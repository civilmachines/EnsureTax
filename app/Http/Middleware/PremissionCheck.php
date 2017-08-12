<?php

namespace App\Http\Middleware;

use App\Helpers\LeaseHelper;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class PremissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $view, $requiredAll = false)
    {
        if ($this->auth->guest()) {
            if ($request->ajax())
                return LeaseHelper::response(false, 401, 'Unauthorized Access');
            else
                return redirect()->guest('/');
        }
        $requiredAll = $requiredAll == "true" ? true : false;
        if (!LeaseHelper::checkPermission($view, $this->auth->user(), $requiredAll))
            return LeaseHelper::response(false, 401, 'Unauthorized Access');
//            if ($request->ajax())
//                return LeaseHelper::response(false, 401, 'Unauthorized Access');
//            else
//                return redirect()->guest('/');

        return $next($request);
    }
}
