<?php

namespace App\Http\Middleware\Api\V1;

use App\Http\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminMiddleware
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();
        if (!$user || !$user->admin || (!empty($roles) && !in_array($user->admin->role, $roles))) {
            $msg = "User not authorized to be here!";
            return $this->apiError($msg, 403);
        }
        return $next($request);
    }
}