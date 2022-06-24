<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Services\Auth\JWT;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class CbtCtrl extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     
    }
    public function login(Request $request)
    {
        if(!JWT::verify($request->app_access_token, config('secret_keys.cbt_app_key'))) {
            return response()->json(['errors' => ['invalid app key!']]);
        }
        $authService = new AuthService($request->email, $request->password);
        if($authService->checkEmail()) {
            return response()->json($authService->attempLogin());
        }
        return response()->json(["errors" => ['Invalid email address!']]);
    }
    public function isValidToken(Request $request) {
        return response()->json(['jwt' => JWT::isValidToken($request->tg_jwt)]);
    }
    public function info(Request $request) {
        return response()->json(['status' => "OK", "user" => User::find(1)]);
    }
    public function createUser(Request $request) {
        try {
            if(!JWT::verify($request->app_access_token, config('secret_keys.cbt_app_key'))) {
                return response()->json(['errors' => ['invalid app key!']]);
            }
            if(User::where('email', $request->email)->first()) {
                return response()->json(['errors' => ['email aldress already in use']]);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
    
            $payload = [
                'email' => $user->email
            ];
            $jwt = JWT::generate(config('jwt.header'), $payload, env('SECRET_KEY'));
            return response()->json([
                'failed' => false,
                'errors' => [],
                'data' => [
                    'token' => $jwt,
                    'user' => $user->makeHidden(['created_at', 'updated_at'])
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage()
            ]);
        }
    }
}