<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Admin;
use Tymon\JWTAuth\Facades\JWTAuth;

class IsAdmin
{

    public function handle(Request $request, Closure $next): Response
    {
        if(! $payload = JWTAuth::checkOrFail()){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('email' , $payload['email'])->first();
        $admin = $user->Admin;

        if(!$user || !$admin) {
            return response()->json(['error' => 'no user has been found'], 404);
        }

        if($user->role != 'admin'){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
