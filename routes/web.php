<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::put('admin/workspace/default', \App\Http\Controllers\Admin\SetDefaultWorkspaceController::class);
Route::get('facebook/connect/callback', [\App\Http\Controllers\FacebookPageController::class, 'handleFacebookCallback'])->name('facebook.callback')->middleware('auth');
