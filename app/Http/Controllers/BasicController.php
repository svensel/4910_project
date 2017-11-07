<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BasicController extends Controller
{
    public function dashboard(){
        return view('pages.dashboard');
    }
}
