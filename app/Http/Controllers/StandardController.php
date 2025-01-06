<?php

namespace App\Http\Controllers;

use App\Models\DossierStandard;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class StandardController extends Controller
{
    public function index() {
        $folders = DossierStandard::all()->sortBy('nom');

        return view('standards.index', compact('folders'));
    }
    public function show($standard) {
        $stockagePath = Storage::path('standards');

        $directories = File::directories($stockagePath);

        foreach ($directories as $directory) {
            $pdfFiles = File::files($directory);

            foreach ($pdfFiles as $file) {
                if ($file->getFilename() === $standard) {
                    return response()->file($file->getPathname());
                }
            }
        }
            return response()->json(['error' => 'File not found.'], 404);
    }

}
