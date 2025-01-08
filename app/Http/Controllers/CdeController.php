<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CdeController extends Controller
{
    public function indexColCde()
    {
        return view('ddp_cde.cde.index_col');
    }
}
