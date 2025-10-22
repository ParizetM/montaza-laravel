<?php

namespace App\Http\Controllers;
use Illuminate\View\View;

use Illuminate\Http\Request;

class ReparationController extends Controller
{
    public function index()
    {
        return view('reparation.index');
    }
}
