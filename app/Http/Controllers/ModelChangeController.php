<?php

namespace App\Http\Controllers;

use App\Models\ModelChange;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModelChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $nombre = $request->input('nombre') ?? 50;
        if (!is_numeric($nombre)) {
            $nombre = 50;
        }
        if ($search || $start_date || $end_date) {
            $modelChanges = ModelChange::query();

            if ($search) {
                $modelChanges->whereHas('user', function ($query) use ($search) {
                    $query->where('first_name', 'ILIKE', "%{$search}%")
                        ->orWhere('last_name', 'ILIKE', "%{$search}%");
                })
                    ->orWhere('model_type', 'ILIKE', "%{$search}%")
                    ->orWhere('before', 'ILIKE', "%{$search}%")
                    ->orWhere('after', 'ILIKE', "%{$search}%")
                    ->orWhere('event', 'ILIKE', "%{$search}%");
            }

            if ($start_date) {
                $modelChanges->where('created_at', '>=', $start_date);
            }

            if ($end_date) {
                $modelChanges->where('created_at', '<=', $end_date);
            }

            $modelChanges = $modelChanges->orderBy('created_at', 'desc')
                ->paginate($nombre);

        } else {
            $modelChanges = ModelChange::orderBy('created_at', 'desc')
                ->paginate($nombre);

        }

        return view('model_changes.index', ['modelChanges' => $modelChanges]);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(ModelChange $modelChange)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(ModelChange $modelChange)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, ModelChange $modelChange)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(ModelChange $modelChange)
    // {
    //     //
    // }
}
