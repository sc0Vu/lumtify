<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Index.
     * 
     * @return view
     */
    public function index()
    {
        return view('layouts.app');
    }
}
