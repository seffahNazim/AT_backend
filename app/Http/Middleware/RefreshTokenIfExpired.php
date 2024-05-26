<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;


class RefreshTokenIfExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        // Vérifiez si le header 'Authorization' est présent et commence par 'Bearer '
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return response()->json(['message' => 'Token is required or invalid'], 400);
        }

        // Extraire le token en retirant 'Bearer '
        $token = substr($token, 7);

        try {
            // Valider le token
            if (!JWTAuth::setToken($token)->check()) {
                return response()->json(['message' => 'Token is invalid'], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token validation failed'], 401);
        }

        return $next($request);
    }

}
