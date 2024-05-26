<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    function manageEmploye(User $user , $payload){
        $admin = $user->admin ;
        if ($admin->isSuper) {
            return true;
        }else{
            return $admin->permission->manage_employe ? true : false ;
        }
    }

    function sendNotification(User $user , $payload){
        $admin = $user->admin ;
        if ($admin->isSuper) {
            return true;
        }else{
            return $admin->permission->send_notification ? true : false ;
        }
    }

    function manageJustification(User $user , $payload){
        $admin = $user->admin ;
        if ($admin->isSuper) {
            return true;
        }else{
            return $admin->permission->manage_justification ? true : false ;
        }
    }

    function managePointing(User $user , $payload){
        $admin = $user->admin ;
        if ($admin->isSuper) {
            return true;
        }else{
            return $admin->permission->manage_pointing ? true : false ;
        }
    }
}
