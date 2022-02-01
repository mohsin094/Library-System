<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::post("login",[AdminController::class,'login']);
Route::post("add_rack",[AdminController::class,'addRack'])->middleware('UserAuthentication');
Route::post("remove_rack",[AdminController::class,'removeRack'])->middleware('UserAuthentication');
Route::post("add_book",[AdminController::class,'addBook'])->middleware('UserAuthentication');
