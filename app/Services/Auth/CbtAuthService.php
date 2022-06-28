<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Hash;
use Ramsey\Collection\Map\AssociativeArrayMap;

class CbtAuthService {
    public $credentials = [];
    private $user;

    public function __construct($email, $password)
    {
        $this->credentials['email'] = $email;
        $this->credentials['password'] = $password;
        $this->user = User::where('email', $this->credentials['email'])->first();
    }
    public function checkEmail() {
        if($this->user) {
            $this->user->makeHidden(['created_at', 'updated_at', 'email_verified_at']);
            return $this->user;
        } 
        return false;
    }
    public function passwordCheck() {
        return Hash::check($this->credentials['password'], $this->user->password);
    }
    public function attempLogin() {
        if($this->passwordCheck()) {
            return $this->getAccess();
        }
        return [
            'failed' => true,
            'errors' => ['Incorrect password!'],
            'data' => []
        ];
    }
    public function register() {
        
    }
    private function getAccess() {
        $payload = [
            'email' => $this->user->email
        ];
        $jwt = JWT::generate(config('jwt.header'), $payload, env('CBT_APP_ACCESS_KEY'));
        $user = $this->user->makeHidden(['created_at', 'updated_at']);
        $user->role = [];
        return [
            'failed' => false,
            'errors' => [],
            'data' => [
                'token' => $jwt,
                'user' => $user
            ]
        ];
    }
}