<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\notifications1;
use App\Notifications\notifications2;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Policies\AdminPolicy;
use App\Models\Employe;
use App\Models\User ;
use App\Models\Admin ;

class AdminController extends Controller
{
    function sendNotification(Request $request){
        try {
            $payload = JWTAuth::getPayload();
            $user = User::where('email', $payload->email)->first();

            if(!AdminPolicy::sendNotification($user)){
               return response()->json(['error' => 'unauthorized'], 401);
            }

            $request->validate([
                'message' => 'required|string|min:4',
                'matricule' => 'required',
            ]);

            $message = $request->input('message');
            $receiver = $request->input('matricule');
            $employe->notify(new notifications2($message , $receiver));
            return response()->json(['message' => 'the employe has been notified successfuly'], 200);
        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function addEmploye(Request $request){
        try {
            // $payload = JWTAuth::getPayload();
            // $user = User::where('email', $payload->email)->first();

            // if(!AdminPolicy::manageEmploye($user)){
            //    return response()->json(['error' => 'unauthorized'], 401);
            // }

            $request->validate([
                'matricule' => 'required|string|max:255|unique:users,matricule',
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'phone' => 'required|string|max:15',
                'birthday' => 'required|date',
                'sexe' => 'required|string|in:homme,femme',
            ]);

            $user = User::create([
                'email' => $request->input('email'),
                'matricule' => $request->input('matricule'),
                'password' => bcrypt($request->input('password')),
                'role' => 'employe',
                'device_id' =>  $request->input('device_id'),
            ]);

            if($user){
                $employe = Employe::create([
                    'user_id' => $user->id,
                    'sexe' => $request->input('sexe'),
                    'full_name' => $request->input('full_name'),
                    'birthday' => $request->input('birthday'),
                ]);
                return response()->json(['message' => 'user has been created'], 201);
            }else {
                return response()->json(['error' => 'failed to create user'], 500);
            }

        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }

    }

    public function deleteEmploye(Request $request){
        try {
            // $payload = JWTAuth::getPayload();
            // $user = User::where('email', $payload->email)->first();

            // if(!AdminPolicy::manageEmploye($user)){
            //    return response()->json(['error' => 'unauthorized'], 401);
            // }

            $request->validate([
                'user_id' => 'required',
            ]);

            $user = User::find($request->input('user_id'));

            if (!$user) {
                return response()->json(['error' => 'no user has been found with this id'], 404);
            }else{
                $user->delete();
                return response()->json(['message' => 'user deleted'], 201);
            }
        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function updateEmplaye(Request $request){
        try {
            $payload = JWTAuth::getPayload();
            $user = User::where('email', $payload->email)->first();

            if(!AdminPolicy::manageEmploye($user)){
               return response()->json(['error' => 'unauthorized'], 401);
            }

            $request->validate([
                'matricule' => 'required',
                'email' => 'email|unique',
                'password' => '',
                'sexe' => 'boolean',
                'birthday' => 'date',
            ]);

            $user = User::where('matricule', $request->matricule)->first();

            if(!$user){
                return response()->json(['error' => 'no user has been found'], 404);
            }else{
                if ($request->has('email')) {
                    $user->email = $request->email;
                } elseif ($request->has('password')) {
                    $user->password = $request->password;
                } elseif ($request->has('matricule')) {
                    $user->matricule = $request->matricule;
                }

                $user->save();

                $employe = Employe::where('user_id', $user->id)->first();

                if(!$employe){
                    return response()->json(['error' => 'no employe has been found'], 404);
                }else {
                    if ($request->has('sexe')) {
                        $employee->sexe = $request->sexe;
                    } elseif ($request->has('birthday')) {
                        $employee->birthday = $request->birthday;
                    } elseif ($request->has('photo')) {
                        $employee->photo = $request->photo;
                    }
                    $employee->save();
                }
            }
        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function addAdmin(Request $request){
        try {
            $payload = JWTAuth::getPayload();
            $user = User::where('email', $payload->email)->first();

            if(!AdminPolicy::manageAdmin($user)){
               return response()->json(['error' => 'unauthorized'], 401);
            }

            $request->validate([
                'matricule' => 'required|unique:user',
                'email' => 'required|email|unique',
                'password' => 'required',
            ]);

            if ($utilisateur = User::where('email', $request->input('email'))->whereNull('deleted_at')->first()) {
                return response()->json(['error' => 'user with the same maticule has been found'], 400);
            }else {
                $user = User::create([
                    'email' => $request->input('email'),
                    'matricule' => 'a' . $request->input('matricule'), // Concaténation avec 'a' pour savoir quel employe avec cet maticule est un admin
                    'password' => bcrypt($request->input('password')),
                    'role' => 'admin',
                ]);

                if($user){
                    return response()->json(['error' => 'failed to create admin'], 500);
                }else {
                    $admin = Admin::create([
                        'user_id' => $user->id,
                        'is_super_admin' => false,
                    ]);
                    $admin->permissions()->sync($request->actions);
                    return response()->json(['message' => 'admin has been created'], 201);
                }
            }
        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function deleteAdmin(Request $request){
        try {
            $payload = JWTAuth::getPayload();
            $user = User::where('email', $payload->email)->first();

            if(!AdminPolicy::manageAdmin($user)){
               return response()->json(['error' => 'unauthorized'], 401);
            }

            $request->validate([
                'matricule' => 'required',
            ]);

            $user = User::find('matricule' , $request->input('matricule'))->first();

            if (!$user) {
                return response()->json(['error' => 'no user has been found with this matricule'], 404);
            }else{
                $user->delete();
                return response()->json(['message' => 'user deleted'], 201);
            }
        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function addPointing(Request $request){
        try {
            $payload = JWTAuth::getPayload();
            $user = User::where('email', $payload->email)->first();

            if(!AdminPolicy::managePointing($user)){
               return response()->json(['error' => 'unauthorized'], 401);
            }

            $request->validate([
                'matricule' => 'required',
                'check' => 'required|in:checkIn,checkOut,emergency'
            ]);

            if (! $user = User::where('matricule', $request->input('matricule'))->whereNull('deleted_at')->first()) {
                return response()->json(['error' => 'user with the same maticule has been found'], 400);
            }else {
                $employe = $user->Employe;
                if($employe){
                    switch ($request->input('check')) {
                        case 'checkIn':
                            $pointing = Pointing::create([
                                'employe_id' => $employe->id,
                                'check_in' => now(),
                            ]);
                            break;

                        case 'checkOut':
                            $lastPointing = Pointing::where('employe_id' , $employe->id)->whereDate('created_at', today())->latest()->first();
                            if (!$lastPointing || $lastPointing->check_in==null) {
                                return response()->json(['error' => 'Check-in required before check-out'], 400);
                            }else {
                                $lastPointing->update([
                                    'check_out' => now(),
                                    'type' => 'normal',
                                ]);
                            }
                            break;

                        case 'emergency':
                            $lastPointing = Pointing::where('employe_id' , $employe->id)->whereDate('created_at', today())->latest()->first();
                            if (!$lastPointing || $lastPointing->check_in==null) {
                                return response()->json(['error' => 'Check-in required before check-out'], 400);
                            }else {
                                $lastPointing->update([
                                    'check_out' => now(),
                                    'type' => 'emergency',
                                ]);
                            }
                            break;

                        default:
                            return response()->json(['error' => 'Invalid data'], 400);
                            break;
                    }
                    return response()->json(['message' => 'pointing for the given employe has been done'], 201);
                }else {
                    return response()->json(['error' => 'failed to do pointing'], 500);
                }
            }
        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function updatePointing(Request $request){
        try {
            $payload = JWTAuth::getPayload();
            $user = User::where('email', $payload->email)->first();

            if(!AdminPolicy::managePointing($user)){
               return response()->json(['error' => 'unauthorized'], 401);
            }

            $request->validate([
                'check_id' => 'required',
                'type' => 'required|in:checkIn,checkOut,emergency',
                'check' => 'date_format:H:i:s'
            ]);

            $pointing = Pointing::where('id', $request->input('id'))->whereNull('deleted_at')->first();

            if (!$pointing){
                return response()->json(['error' => 'no pointing has been found with the given id'], 404);
            }else {
                switch ($request->input('check')) {
                    case 'checkIn':
                        $pointing->update([
                            'check_in' => $request->input('check')
                        ]);
                        break;

                    case 'checkOut':
                        $pointing->update([
                            'check_out' => $request->input('check'),
                            'type' => $request->input('type')
                        ]);
                        break;

                    case 'emergency':
                        $pointing->update([
                            'check_out' => $request->input('check'),
                            'type' => $request->input('type')
                        ]);
                        break;

                    default:
                        return response()->json(['error' => 'invalid data'], 400);
                        break;
                }
                return response()->json(['error' => 'the pointing has been deleted'], 201);
            }

        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function addJustification(Request $request){
        try {
            $payload = JWTAuth::getPayload();
            $user = User::where('email', $payload->email)->first();

            if(!AdminPolicy::manageJustification($user)){
               return response()->json(['error' => 'unauthorized'], 401);
            }

            $request->validate([
                'matricule' => 'required',
                'pointing_id' => 'required'
            ]);

            $data = [
                'pointing_id' => $request->input('pointing_id'),
            ];

            if ($request->hasFile('file')) {
                $data['file'] = $request->file('file')->store('justifications');
            }

            if ($request->filled('text')) {
                $data['text'] = $request->input('text');
            }

            $justification = Justification::create($data);


            if (!$justifications) {
                return response()->json(['error' => 'error while creating justification'], 201);
            }else{
                return response()->json(['message' => 'Justification created successfully'], 201);
            }

        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function updateJustification(Request $request){
        try {
            $payload = JWTAuth::getPayload();
            $user = User::where('email', $payload->email)->first();

            if(!AdminPolicy::manageJustification($user)){
               return response()->json(['error' => 'unauthorized'], 401);
            }

            $request->validate([
                'id' => 'required',
            ]);

            if ($request->hasFile('file')) {
                $data['file'] = $request->file('file')->store('justifications');
            }

            if ($request->filled('text')) {
                $data['text'] = $request->input('text');
            }

            $justification = Justification::where('id', $request->input('id'))->whereNull('deleted_at')->first();

            if(!$justification){
                return response()->json(['error' => 'error while updating justification'], 400);
            }else{
                $justification->update($data);
                return response()->json(['message' => 'updating justification successfuly'], 201);
            }

        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function deleteJustification(Request $request){
        try {
            $payload = JWTAuth::getPayload();
            $user = User::where('email', $payload->email)->first();

            if(!AdminPolicy::manageJustification($user)){
               return response()->json(['error' => 'unauthorized'], 401);
            }

            $request->validate([
                'id' => 'required',
            ]);

            $justification = Justification::where('id', $request->input('id'))->whereNull('deleted_at')->first();

            if(!$justification){
                return response()->json(['error' => 'no justification has been found with the given id'], 400);
            }else{
                $justification->delete();
                return response()->json(['message' => 'updating justification successfuly'], 201);
            }

        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function managePermission(Request $request){
        try {
            // $payload = JWTAuth::getPayload();
            // $user = User::where('email', $payload->email)->first();

            // if(!AdminPolicy::manageJustification($user)){
            //    return response()->json(['error' => 'unauthorized'], 401);
            // }

            $request->validate([
                'user_id' => 'required',
                'permission' => 'required'
            ]);

            $user = User::find($request->input('user_id'));

            if(!$user){
                return response()->json(['error' => 'no user has been found'], 404);
            }

            $permissions = Permission::all();

            foreach ($permissions as $permission ) {
                if (condition) {
                    # code...
                }
            }


            if(!$justification){
                return response()->json(['error' => 'no justification has been found with the given id'], 400);
            }else{
                $justification->delete();
                return response()->json(['message' => 'updating justification successfuly'], 201);
            }

        } catch (JWTException $e){
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }
}
