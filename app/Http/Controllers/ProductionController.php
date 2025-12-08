<?php

namespace App\Http\Controllers;

use App\Models\Affaire;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionController extends Controller
{
    public function index(Request $request): View
    {
        $affaires = Affaire::orderBy('updated_at', 'desc')->get();
        return view('production.index', compact('affaires'));
    }

    public function show(Affaire $affaire): View
    {
        $affaire->load(['cdes', 'ddps', 'materiels', 'reparations']);
        return view('production.show', compact('affaire'));
    }
}
