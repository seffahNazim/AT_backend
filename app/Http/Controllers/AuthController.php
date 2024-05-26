<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request){

        $credentials = $request->validate([
            'email' => 'required_without:matricule|email',
            'password' => 'required',
        ]);

        if($request->filled('email')){

            if(!Auth::attempt($credentials)){
                return response()->json([
                    'error' => 'no valide data',
                ], 401);
            }
            $user = User::where('email' , $request->email)->first();
            $customClaims = [
                'email' => $user->email,
                'password' => $user->password,
                'role' => $user->role,
            ];
            $token = JWTAuth::claims($customClaims)->fromUser($user);
            return $this->respondWithToken($token);

        }else if($request->filled('matricule')){

            $user = User::where('matricule' , $request->matricule)->first();
            if(!$user || !Hash::check($request->password , $user->password)){
                return response()->json(['error' => 'no valide data'], 401);
            }
            $customClaims = [
                'matricule' => $user->matricule,
                'password' => $user->password,
                'role' => $user->role,
            ];
            $token = JWTAuth::claims($customClaims)->fromUser($user);
            return $this->respondWithToken($token);

        }else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    }

    public function logout(){
        JWTAuth::invalidate();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function update(Request $request){

        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json(['error' => 'no user has been found'], 404);
        }

        if($user->role == 'admin'){
            $request->validate([
                'email' => 'email|unique:user',
                'password' => 'min:8',
            ]);
            if($request->filled('email')){
                $user->update([
                    'email' =>$request->input('email')
                ]);
            }
            if($request->filled['password']){
                $user->update([
                    'password' =>$request->input('password')
                ]);
            }
            $user->save();
            return response()->json(['message' => 'User updated successfully'] , 200);
        }else if($user->role == 'employe'){
            $request->validate([
                'email' => 'email|unique:users',
                'password' => 'min:8',
            ]);
            if($request->filled('email')){
                $user->update([
                    'email' =>$request->input('email')
                ]);
            }
            if($request->filled['password']){
                $user->update([
                    'password' =>$request->input('password')
                ]);
            }
            $user->save();
            return response()->json(['message' => 'User updated successfully'] , 200);
        }
        return response()->json(['error' => 'no valid data'] , 400);
    }

    public function refreshToken(Request $request){
        return $this->respondWithToken(JWTAuth::refresh());
    }

    protected function respondWithToken($token){

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);

    }

}

