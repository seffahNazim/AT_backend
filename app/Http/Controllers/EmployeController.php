<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employe;
use App\Models\Pointing;
use App\Models\Justification;
use Carbon\Carbon;

class EmployeController extends Controller
{

    public function checkIn(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $pointing = Pointing::where('employe_id' , $user->employe->id)->where('date', today())->latest()->first();
        if ($pointing) {
            return response()->json(['error' => 'checkIn existe'], 400);
        }
        $date = Carbon::now()->format('Y-m-d');
        Pointing::create([
            'employe_id' => $user->employe->id,
            'check_in' => now(),
            'statut' => 'inProgress' ,
            'date' => $date
        ]);
        return response()->json(['error' => 'checkIn created successfuly'], 201);
    }

    public function checkOut(Request $request){
        $request->validate([
            'statut' => 'required|in:checkOut,emergency',
            'pointing_id' => 'required'
        ]);
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json(['error' => 'no user has been found'], 404);
        }
        $pointing = Pointing::where('employe_id' , $user->employe->id)->whereDate('created_at', today())->where('id' , $request->input('pointing_id'))->latest()->first();
        if (!$pointing) {
            return response()->json(['error' => 'you need to do checkIn first'], 400);
        }

        switch ($request->input('statut')) {
            case 'checkOut':
                $pointing->update([
                    'check_out' => now(),
                    'statut' => 'present',
                ]);
                break;
            case 'emergency':
                $pointing->update([
                    'check_out' => now(),
                    'statut' => 'emergency'
                ]);
                break;
            default:
                return response()->json(['error' => 'invalid data'], 400);
                break;
        }
        $pointing->save();
        return response()->json(['message' => 'checkOut created successfuly'], 201);
    }

    public function addJustification(Request $request){

        $request->validate([
            'text' => 'string',
            'file' => 'string',
            'pointing_id' => 'required|exists:pointings,id'
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        $justification = Justification::where('pointing_id' , $request->pointing_id)->first();

        if(!$user || !$user->employe){
            return response()->json(['error' => 'No user with this matricule'], 404);
        }

        if($justification){
            return response()->json(['error' => 'a justification already existe'], 400);
        }

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

        if ($justification) {
            return response()->json(['message' => 'Justification created successfully'], 201);
        }

    }
}
