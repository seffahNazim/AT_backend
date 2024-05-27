<?php

namespace App\Http\Controllers\EmployeApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pointing;
use App\Models\Employe;
use App\Models\Justification;
use App\Models\notification2;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmployeApp extends Controller
{
    public function getPointing(Request $request) {

        $request->validate([
            'date' => 'required|date_format:Y-m',
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->employe){
            return response()->json(['error' => 'No user with this matricule'], 404);
        }

        list($year, $month) = explode('-', $request->input('date'));

        $pointings = Pointing::where('employe_id', $user->Employe->id)
                     ->whereYear('date', $year)
                     ->whereMonth('date', $month)
                     ->get();

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

    public function getNotification(Request $request){

        $user = JWTAuth::parseToken()->authenticate();
        if (!$user || !$user->Employe) {
            return response()->json(['error' => 'no user has been found'], 404);
        }

        $notifications = notification2::where('employe_id' , $user->employe->id)->whereNull('read_at')->get() ;

        $formatterNotifications = [] ;
        foreach ($notifications as $notification) {
            $formatterNotification[] = [
                'id' => $notification->id,
                'text' =>  $notification->text
            ];
            $formatterNotifications = $formatterNotification;
        }


        return response()->json(['notifications' => $formatterNotifications], 200);
    }

    public function getInfoEmploye(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user || !$user->Employe) {
            return response()->json(['error' => 'no user has been found'], 404);
        }

        $formatterUser = [
            'email' => $user->email,
            'matricule' => $user->matricule,
            'name' => $user->Employe->full_name,
            'photo' => $user->Employe->photo,
            'sexe' => $user->Employe->sexe,
            'birthday' => $user->Employe->birthday,
            'device_id' => $user->Employe->device_id,
        ];

        return response()->json($formatterUser, 200);
    }
}
