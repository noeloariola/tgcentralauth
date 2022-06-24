<?php
namespace App\Services\Auth;

use App\Models\User;

class Idol {

    private $user;
    
    public function __construct(User $user) 
    {
        $this->user = $user;
    }
    public function auth() {
        return $this->user;
    }   
}