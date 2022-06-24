<?php

namespace App\Services;
use App\Interfaces\AuthServiceInterface;
use Illuminate\Support\Arr;

class AuthService implements AuthServiceInterface
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected static $param;
    protected $applicant_id;

    public function __construct($param = null)
    {
        self::$param = $param;
    }
    public function param() {
        return self::$param;
    }
    public function init($param) {
        return new AuthServiceInterface($param);
    }
    public function getAll($arg) {

    }
    public function get($id) {
        return $id;
    }
    public function store($data = null) {
        $data = $data ?? $this->param();

        
        return ["success" => true];
    }

}
