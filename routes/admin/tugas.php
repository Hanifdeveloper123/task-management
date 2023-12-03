<?php


use App\Http\Controllers\Tugas\TugasManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tugas\TugasController;


Route::prefix('tugas')->name('tugas.')->group(function () {
    Route::controller(TugasController::class)->prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/', 'tugasSettingIndex')->name('index');
            Route::controller(TugasManagementController::class)->group(function () {
                Route::get('get-data', 'getTugasList')->name('getdata');
                Route::get('add-tugas', 'createTugasPage')->name('createpage');
                Route::get('{id}/edit-tugas', 'editTugasPage')->name('editpage');
                Route::post('add-tugas', 'storeNewTugas')->name('storetugas');
                Route::put('{id}/update-tugas', 'updateTugas')->name('updatetugas');
                Route::delete('{id}/delete-tugas', 'deleteTugas')->name('deletetugas');
        });
    }); 
});

