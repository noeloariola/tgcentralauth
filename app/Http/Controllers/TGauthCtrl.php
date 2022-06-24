<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
// use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\JWT;
use Illuminate\Http\Request;

class TGauthCtrl extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     
    }
    public function get(Request $request) {
        return response()->json(['test' => 'TEST']);
    } 
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $user = User::where('email', $credentials['email'])->first();
        if(!$user) {
            return response()->json([
                'status' => "failed",
                'message' => "email address is not registered"
            ]);
        }
        if(Hash::check($credentials['password'], $user->password)) {
            $payload = [
                'email' => $user->email
            ];
            $jwt = JWT::generate(config('jwt.header'), $payload, env('SECRET_KEY'));
            return response()->json([
                'status' => "ok",
                'message' => "login successful",
                'jwt' => $jwt,
                'user' => $user
            ]); 
        }
        return response()->json(['status' => 'failed', "message" => 'password is incorrect!']);
    }
    public function isValidToken(Request $request) {
        return response()->json(['jwt' => JWT::isValidToken($request->tg_jwt)]);
    }
    public function info(Request $request) {
        return response()->json(['status' => "OK", "user" => User::find(1)]);
    }
}