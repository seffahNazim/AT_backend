<?php

namespace App\Http\Controllers\AdminApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pointing;
use App\Models\Employe;
use App\Models\User;
use App\Models\Admin;
use App\Models\Justification;
use App\Models\Pemission;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Notification1;
use Illuminate\Validation\Rule;

class AdminApp extends Controller
{
    public function getAllEmployes(){

        $users = User::where('role','employe')->get();

        if(!$users){
            return response()->json(['error' => 'no user has been found'], 404);
        }

        $formattedEmployes = [];

        foreach ($users as $user) {
            $employe = $user->employe;
            if ($employe) {
                $formattedEmploye[] = [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'matricule' => $user->matricule,
                    'photo' => $employe->photo,
                    'name' => $employe->full_name,
                    'device_id' => $employe->device_id,
                    'sexe' => $employe->sexe,
                    'deleted' => $employe->deleted,
                    'birthday' => $employe->birthday,
                ];
            }
            $formattedEmployes['employes'] = $formattedEmploye;
        }

        return response()->json($formattedEmployes, 200);
    }

    public function getEmploye(Request $request, $id){

        $user = User::findOrFail($id);

        $employee = $user->Employe ;

        if (!$employee) {
            return response()->json(['error' => 'no employee has been found with this id'], 404);
        }

        $formattedUser = [
            'user_id' => $employee->user->id,
            'email' => $employee->user->email,
            'matricule' => $employee->user->matricule,
            'password' => $employee->user->password,
            'photo' => $employee->photo,
            'full_name' => $employee->full_name,
            'device_id' => $employee->device_id,
            'sexe' => $employee->sexe,
            'phone' => $employee->phone,
            'deleted' => $employee->deleted,
            'birthday' => $employee->birthday,
        ];

        return response()->json($formattedUser, 200);
    }

    public function getAllPointingsDayDashBoard(Request $request){

        $validatedData = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $pointings = Pointing::whereDate('date', $request->date)->get();

        $formattedPointings = [];

        foreach ($pointings as $pointing) {
            $employe = $pointing->employe;
            $formattedPointing = [
                'user_id' => $employe->user->id,
                'matricule' => $employe->user->matricule,
                'name' => $employe->full_name,
                'check' => [
                    'check_in' => $pointing->check_in,
                    'check_out' => $pointing->check_out,
                ],
                'statut' => $pointing->statut,
            ];

             if ($pointing->justification) {
                $formattedPointing['justification'] = [
                    'justification_text' => $pointing->justification->text,
                    'justification_file' => $pointing->justification->file,
                ];
            }
            $formattedPointings[] = $formattedPointing;
        }


        return response()->json(['pointings' => $formattedPointings]);
    }

    public function getAllPointingsMonthDashBoard(Request $request) {

        $request->validate([
            'date' => 'required|date_format:Y-m',
        ]);

        $date = $request->input('date');

        if (!strtotime($date)) {
            return response()->json(['error' => 'Date invalide'], 400);
        }

        list($year, $month) = explode('-', $date);

        $pointings = Pointing::whereYear('date', $year)->whereMonth('date', $month)->get();

        $formattedPointings = [];

        foreach ($pointings as $pointing) {
            $employe = $pointing->employe;

            if ($employe) {
                $formattedEmploye = [
                    'user_id' => $employe->user->id,
                    'name' => $employe->full_name,
                    'matricule' => $employe->user->matricule,
                    'statut' => $pointing->statut,
                ];

                $date = $pointing->date;

                if (!isset($formattedPointings[$date])) {
                    $formattedPointings[$date] = [];
                }

                $formattedPointings[$date][]     = $formattedEmploye;
            }
        }

        $result = [];
        foreach ($formattedPointings as $date => $employes) {
            $result[] = [
                'date' => $date,
                'employes' => $employes,
            ];
        }

        return response()->json(['pointings' => $result]);
    }

    public function getAllPointingsMonthEmploye(Request $request){

        $request->validate([
            'date' => 'required|date_format:Y-m',
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id) ;

        if(!$user){
            return response()->json(['error' => 'no user has been found with this id'], 404);
        }

        $employe = $user->Employe ;

        if(!$employe){
            return response()->json(['error' => 'no employe has been found with this id'], 404);
        }

        list($year, $month) = explode('-', $request->input('date') );

        $pointings = Pointing::where('employe_id', $employe->id)->whereYear('date', $year)->whereMonth('date', $month)->get();

        $formattedPointings=[];

        foreach ($pointings as $pointing) {
            $justification_text= null ;
            $justification_file= null ;
            if ($pointing->justification) {
                $justification = $pointing->justification ;
                if ($justification->text) {
                    $justification_text = $justification->text ;
                }
                if ($justification->file) {
                    $justification_file = $justification->file ;
                }

            }
            $formattedPointing=[
                'pointing_id'=> $pointing->id,
                'date'=> $pointing->date,
                'check_in'=> $pointing->check_in,
                'check_out'=> $pointing->check_out,
                'statut' => $pointing->statut,
                'justification_text' => $justification_text,
                'justification_file' => $justification_file
            ];
            $formattedPointings[] = $formattedPointing;
        }

        return response()->json(['pointings' => $formattedPointings]);
    }

    public function getAllAdmin(){
        $users = User::where('role','admin')->get();

        if(!$users){
            return response()->json(['error' => 'no user has been found'], 404);
        }

        $formattedUsers = [];

        foreach ($users as $user) {
            $admin = $user->admin;
            if ($admin) {
                $formattedUsers[] = [
                    'identifiant' => $user->id,
                    'email' => $user->email,
                    'matricule' => $user->matricule,
                    'deleted' => $admin->deleted,
                ];
            }
        }

        return response()->json($formattedUsers, 200);
    }

    public function getAdmins(Request $request){

        $admins = Admin::where('is_super' , 0)->get();

        $formattedUsers = [];

        foreach ($admins as $admin) {

            if ($admin) {
                $formattedUser = [
                    'id' => $admin->user->id,
                    'email' => $admin->user->email,
                    'matricule' => $admin->user->matricule,
                    'permission' => [
                        'manage_employe' => $admin->permission->manage_employe,
                        'manage_justification' => $admin->permission->manage_justification,
                        'manage_pointing' => $admin->permission->manage_pointing,
                        'send_notification' => $admin->permission->send_notification,
                    ],
                ];
            }
            $formattedUsers[] = $formattedUser;
        }

        return response()->json(['admins' => $formattedUsers], 200);
    }

    public function getAdminInfo(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::where('id', $request->input('user_id'))->first();

        if (!$user) {
            return response()->json(['error' => 'No user has been found'], 404);
        }

        $admin = $user->admin;

        if (!$admin) {
            return response()->json(['error' => 'No admin has been found'], 404);
        }

        $formattedUser = [
            'identifiant' => $user->id,
            'email' => $user->email,
            'matricule' => $user->matricule,
        ];

        return response()->json($formattedUser, 200);
    }

    public function getNotifications(Request $request){

        $notifications = Notification1::all();

        $formatterNotifications = [] ;
        foreach ($notifications as $notification) {
            $formatterNotification = [
                'identifiant' => $notification->id,
                'type' => $notification->type,
                'text' => $notification->text,
            ];
            $formatterNotifications[] = $formatterNotification;
        }
        return response()->json(['notifications' => $formatterNotifications], 200);
    }

    public function permission(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user || !$user->Admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $permission = [
            'manage_employe' => $user->Admin->permission->manage_employe,
            'manage_pointing' => $user->Admin->permission->manage_pointing,
            'manage_justification' => $user->Admin->permission->manage_justification,
            'send_notification' => $user->Admin->permission->send_notification
        ];

        return response()->json(['permission' => $permission], 200);
    }

    public function getAdminPermission(Request $request , $id){

        $user = User::findOrFail($id);

        if (!$user || !$user->Admin) {
            return response()->json(['error' => 'no user has been found'], 404);
        }

        $permission = [
            'manage_employe' => $user->Admin->permission->manage_employe,
            'manage_pointing' => $user->Admin->permission->manage_pointing,
            'manage_justification' => $user->Admin->permission->manage_justification,
            'send_notification' => $user->Admin->permission->send_notification
        ];

        return response()->json(['permission' => $permission], 200);
    }

}
