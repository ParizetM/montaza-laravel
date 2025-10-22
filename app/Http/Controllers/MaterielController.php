<?php

namespace App\Http\Controllers;
use Illuminate\View\View;

use Illuminate\Http\Request;

class MaterielController extends Controller
{
    public function index()
    {
        return view('materiel.index');
    }
}
