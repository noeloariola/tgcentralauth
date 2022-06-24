<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestCtrl extends Controller
{
    public function get(Request $request) {
        return response()->json(['test' => 'TEST']);
    } 
}
