<?php

use App\Http\Controllers\Auth\TGauthCtrl;
use App\Http\Controllers\Auth\CbtCtrl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

$router->post('/login', [TGauthCtrl::class, 'login']);
$router->get('/auth/refresh_token', [TGauthCtrl::class, 'getDetails']);

$router->post('/user/create', [TGauthCtrl::class, 'createUser']);


$router->post('/cbt/login', [CbtCtrl::class, 'login']);
$router->post('/cbt/register', [CbtCtrl::class, 'createUser']);



$router->get('/get', function(Request $request) {
    return response()->json(['users' => User::all()]);
});