<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/first", function (Request $request) {
    // Storage::disk("local")->put("example.txt", "heelo world");
    // return response()->json([
    //     "msg" => "all things are okay"
    // ]);
    return response()->download("storage/example.txt");
    // return asset("storage/example.txt");
});
