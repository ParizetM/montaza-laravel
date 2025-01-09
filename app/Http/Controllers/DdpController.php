<?php

namespace App\Http\Controllers;

use App\Models\Ddp;
use App\Models\Famille;
use Illuminate\Http\Request;

class DdpController extends Controller
{
    public function indexDdp_cde()
    {

        return view('ddp_cde.index');
    }
    public function indexColDdp()
    {
        $ddps = Ddp::whereIn('ddp_cde_statut_id', [1, 2])->orderBy('ddp_cde_statut_id', 'asc')->take(7)->get();
        return view('ddp_cde.ddp.index_col', compact('ddps'));
    }
    public function show($id)
    {
        $ddp = Ddp::findOrFail($id);
        return view('ddp_cde.ddp.show', compact('ddp'));
    }
    public function create()
    {
        $familles = Famille::all();
        return view('ddp_cde.ddp.create', compact('familles'));
    }
}
