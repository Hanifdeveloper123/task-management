<?php


use App\Http\Controllers\User\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;


Route::prefix('user')->name('user.')->group(function () {
    Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
            Route::get('/', 'userSettingIndex')->name('index');
            Route::controller(UserManagementController::class)->group(function () {
                Route::get('get-data', 'getUserList')->name('getdata');
                Route::get('add-user', 'createUserPage')->name('createpage');
                Route::get('{id}/edit-user', 'editUserPage')->name('editpage');
                Route::put('{id}/update-user', 'updateUser')->name('updateuser');
                Route::delete('{id}/delete-user', 'deleteUser')->name('deleteuser');
                Route::post('add-user', 'storeNewUser')->name('storeuser');
        });
    }); 
});

