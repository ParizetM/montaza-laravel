<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DdpController extends Controller
{
    public function indexDdp_cde()
    {
        return view('ddp_cde.index');
    }
    public function indexColDdp()
    {
        return view('ddp_cde.ddp.index_col');
    }
}
