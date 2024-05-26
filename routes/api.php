<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RefreshTokenIfExpired;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\AdminApp\AdminApp;
use App\Http\Controllers\EmployeApp\EmployeApp;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsEmploye;





Route::post('login', [AuthController::class, 'login']);


Route::put('/managePermission', [AdminController::class, 'managePermission']);
Route::get('/getAdminInfo', [AdminApp::class, 'getAdminInfo']);



Route::middleware('RefreshTokenIfExpired')->group(function () {
    // admin
    Route::middleware('IsAdmin')->prefix('admin')->group(function () {
        Route::get('/getAllPointingsDayDashboard', [AdminApp::class , 'getAllPointingsDayDashboard']);
        Route::get('/getAllPointingsMonthDashboard', [AdminApp::class, 'getAllPointingsMonthDashboard']);
        Route::get('/getAllEmployes', [AdminApp::class , 'getAllEmployes']);
        Route::get('/getEmploye/{id}', [AdminApp::class, 'getEmploye']);
        Route::get('/getAllPointingsMonthEmploye', [AdminApp::class, 'getAllPointingsMonthEmploye']);
        Route::get('/getNotifications', [AdminApp::class, 'getNotifications']);
        Route::get('/getAdmins', [AdminApp::class, 'getAdmins']);
        Route::delete('/deleteEmploye', [AdminController::class, 'deleteEmploye']);
        Route::post('/addEmploye', [AdminController::class, 'addEmploye']);
    });

    // employe
    Route::middleware('IsEmploye')->prefix('employe')->group(function () {
        Route::get('/getInfoEmploye', [EmployeApp::class, 'getInfoEmploye']);
        Route::get('/getPointing', [EmployeApp::class, 'getPointing']);
        Route::get('/getNotification', [EmployeApp::class, 'getNotification']);
        Route::post('/checkIn', [EmployeController::class, 'checkIn']);
        Route::post('/checkOut', [EmployeController::class, 'checkOut']);
        Route::post('/addJustification', [EmployeController::class, 'addJustification']);
    });

    // both
    Route::get('/logout', [AuthController::class , 'logout']);
    Route::get('/update', [AuthController::class , 'update']);
    Route::get('/refreshToken', [AuthController::class , 'refreshToken']);

});


// Route::middleware('RefreshTokenIfExpired')->group(function () {
//     // admin
//     Route::middleware('isAdmin')->prefix('admin')->group(function () {
//         Route::post('/addJustification', [AdminController::class , 'addJustification']);
//         Route::post('/deleteJustification', [AdminController::class , 'deleteJustification']);
//         Route::post('/updateJustification', [AdminController::class , 'updateJustification']);

//         Route::post('addAdmin', [AdminController::class , 'addAdmin']);
//         Route::post('deleteAdmin', [AdminController::class , 'deleteAdmin']);

//         Route::post('sendNotification', [AdminController::class , 'sendNotification']);

//         Route::post('addPointing', [AdminController::class , 'addPointing']);
//         Route::post('deletePointing', [AdminController::class , 'deletePointing']);
//         Route::post('updatePointing', [AdminController::class , 'updatePointing']);

//         Route::get('pointings', [AdminController::class , 'getAllPointings']);
//         Route::get('pointing', [AdminController::class , 'getPointing']);
//         Route::get('employes', [AdminController::class , 'getAllEmployes']);

//     });

//     // employe
//     Route::middleware('isEmploye')->prefix('employe')->group(function () {
//         Route::get('information', [AdminController::class , 'getInformation']);
//         Route::get('pointing', [AdminController::class , 'getPointing']);
//         Route::post('addPointing', [AdminController::class , 'addPointing']);
//         Route::post('addJustification', [AdminController::class , 'addJustification']);
//     });

//     // both
//     Route::get('/logout', [AuthController::class , 'logout']);
//     Route::get('/refreshToken', [AuthController::class , 'refreshToken']);

// });
