<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Employe;
use Tymon\JWTAuth\Facades\JWTAuth;

class IsEmploye
{
    public function handle(Request $request, Closure $next): Response
    {
        if(! $payload = JWTAuth::checkOrFail()){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('matricule' , $payload['matricule'])->first();


        if(!$user || !$user->Employe) {
            return response()->json(['error' => 'no user has been found'], 404);
        }

        if($user->role != 'employe'){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
