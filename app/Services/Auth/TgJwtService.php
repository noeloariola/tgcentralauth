<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TgJwtService {
    public function isValidToken($token) {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/', $token
        ) === 1;
    }

    // return string
    public function generate(array $header, array $payload, string $secret, int $validity = 86400) {

        if($validity > 0) {
            $now = new \DateTime();
            $expiration = $now->getTimestamp() + $validity;
            $payload["iat"] = $now->getTimestamp();
            $payload["exp"] = $expiration;
        }
        
        $header = base64_encode(json_encode($header));
        $payload = base64_encode(json_encode($payload));
        $base64Header  = str_replace(["+","/","="], ["-","_",""], $header);
    
        $base64Payload = str_replace(["+","/","="], ["-","_",""], $payload);
    
        $secret = base64_encode($secret);
    
        $signature = hash_hmac("sha256", $base64Header . "." . $base64Payload, $secret, true);
    
        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(["+","/","="], ["-","_",""], $base64Signature);
    
        $jwt = $base64Header . "." . $base64Payload . "." . $base64Signature;
        return $jwt;
    }   
    //return boolean
    function verify(string $token, string $secret):bool {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        if($header === null || $payload === null) return false;

        $verifyToken = $this->generate($header, $payload, $secret, 0);
        return $token === $verifyToken;
    }
    // return false or User
    function verifyAndInfo(string $token, string $secret) {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);
        if($header === null || $payload === null) return false;

        $verifyToken = $this->generate($header, $payload, $secret, 0);
        if($token === $verifyToken) {
            return User::where('email', $payload['email'])->first();
        }
        return false;
    }
    // return false or User
    function provideAccess(string $token, string $secret) {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);
        
        if($header === null || $payload === null) return 'false';
        
        $verifyToken = $this->generate($header, $payload, $secret, 0);
        $user = User::where('email', $payload['email'])->first();
        if($token === $verifyToken) {
            return [
                'failed' => false,
                'errors' => [],
                'data' => [
                    'token' => $verifyToken,
                    'user' => $user
                ]
            ];
        }
        return false;
    }
    function userInfo(string $token) {
        $payload = $this->getPayload($token);
        return User::where('email', $payload['email'])->first();
    }
    public function getHeader(string $token) {
        $array = explode('.', $token);
        $header = json_decode(base64_decode($array[0]), true);
        return $header;
    }
    public function getPayload(string $token) {
        $array = explode('.', $token);
        $payload = json_decode(base64_decode($array[1]), true);
        return $payload;
    }
    
    public function revoke(string $token) {
        
    }
}