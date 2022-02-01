<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::post("registration",[ClientController::class,'registration']);
// Route::post("add_rack",[AdminController::class,'addRack'])->middleware('UserAuthentication');
// Route::post("remove_rack",[AdminController::class,'removeRack'])->middleware('UserAuthentication');
 Route::get("list_racks",[ClientController::class,'listRacks'])->middleware('UserAuthentication');
 Route::get("rack_books",[ClientController::class,'checkRackBooks'])->middleware('UserAuthentication');
 Route::get("search_books",[ClientController::class,'searchBook'])->middleware('UserAuthentication');
