<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        // Trả về file: resources/views/pages/home.blade.php
        return view('pages.home');
    }

    public function temp()
    {
        // Trả về file: resources/views/pages/home.blade.php
        return view('pages.temp');
    }
}
