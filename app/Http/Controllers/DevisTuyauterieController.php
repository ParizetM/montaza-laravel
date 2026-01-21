<?php

namespace App\Http\Controllers;

use App\Models\DevisTuyauterie;
use App\Models\Entite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DevisTuyauterieController extends Controller
{
    public function index()
    {
        // Récupérer les devis triés par date d'émission décroissante
        $devis = DevisTuyauterie::orderBy('date_emission', 'desc')->get();
        return view('devis_tuyauterie.index', compact('devis'));
    }

    public function indexColDevisTuyauterieSmall()
    {
        $devis = DevisTuyauterie::orderBy('date_emission', 'desc')->take(7)->get();
        $isSmall = true;
        return view('devis_tuyauterie.index_col', compact('devis', 'isSmall'));
    }

    public function create()
    {
        return view('devis_tuyauterie.create');
    }

    public function show($id)
    {
        $devis = DevisTuyauterie::with(['sections.lignes'])->findOrFail($id);
        return view('devis_tuyauterie.show', compact('devis'));
    }

    public function pdf($id)
    {
        $devis = DevisTuyauterie::with(['sections.lignes'])->findOrFail($id);
        $entite = Entite::first();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('devis_tuyauterie.pdf', compact('devis', 'entite'));
        $pdf->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

        $fileName = 'Devis_' . ($devis->reference_projet ?? $devis->id) . '.pdf';

        // Save to storage
        $year = $devis->created_at->format('Y');
        Storage::put('DevisTuyauterie/' . $year . '/' . $fileName, $pdf->output());

        return $pdf->stream($fileName);
    }

    public function preview($id)
    {
        $devis = DevisTuyauterie::findOrFail($id);
        return view('devis_tuyauterie.preview', compact('devis'));
    }

    public function downloadPdf($id)
    {
        $devis = DevisTuyauterie::findOrFail($id);
        $fileName = 'Devis_' . ($devis->reference_projet ?? $devis->id) . '.pdf';
        $year = $devis->created_at->format('Y');
        $path = 'DevisTuyauterie/' . $year . '/' . $fileName;

        if (!Storage::exists($path)) {
            // Generate if not exists
            return $this->pdf($id);
        }

        return Storage::download($path, $fileName);
    }
}
