<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Cde;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MediaController extends Controller
{
    /**
     * Les classes de modèles autorisées pour l'attachement des médias
     */
    protected $allowedModels = [
        'cde' => \App\Models\Cde::class,
        'ddp' => \App\Models\Ddp::class,
        // Ajoutez d'autres modèles selon vos besoins
    ];

    /**
     * Affiche la liste des médias associés à une entité.
     */
    public function index($model, $id)
    {
        // Vérifier si l'entité existe
        $entity = $this->getEntity($model, $id);

        if (!$entity) {
            return abort(404);
        }

        return view('media.index', [
            'model' => $model,
            'modelId' => $id,
            'entity' => $entity
        ]);
    }

    /**
     * Enregistre un ou plusieurs fichiers associés à une entité.
     */
    public function store(Request $request, $model, $id)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $entity = $this->getEntity($model, $id);

        if (!$entity) {
            return back()->withErrors(['Entité non trouvée']);
        }

        foreach ($request->file('files') as $file) {
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $path = 'media/' . $model . '/' . date('Y/m/d');

            $filePath = $file->storeAs('public/' . $path, $filename);

            $media = new Media([
                'filename' => $filename,
                'original_filename' => $originalFilename,
                'path' => str_replace('public/', '', $filePath),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);

            $entity->media()->save($media);
        }

        return back()->with('success', 'Fichiers téléchargés avec succès');
    }

    /**
     * Télécharge un média.
     */
    public function download(Media $media)
    {
        $path = Storage::path('public/' . $media->path);
        return response()->download($path, $media->original_filename);
    }

    /**
     * Supprime un média.
     */
    public function destroy(Media $media)
    {
        Storage::delete('public/' . $media->path);
        $media->delete();
        return back()->with('success', 'Fichier supprimé avec succès');
    }

    /**
     * Génère un lien signé pour l'upload via QR code.
     */
    public function generateQrLink($model, $id)
    {
        $token = Str::random(32);

        $signedUrl = URL::temporarySignedRoute(
            'media.upload-form',
            now()->addMinutes(10),
            [
                'model' => $model,
                'id' => $id,
                'token' => $token
            ]
        );

        $qrCode = QrCode::size(200)->generate($signedUrl);

        return response()->json([
            'qrCode' => $qrCode->toHtml()
        ]);
    }

    /**
     * Récupère la classe du modèle à partir du nom fourni.
     */
    protected function getModelClass($model)
    {
        if (!isset($this->allowedModels[$model])) {
            abort(404, 'Type de modèle non autorisé');
        }

        return $this->allowedModels[$model];
    }

    protected function getEntity($model, $id)
    {
        switch ($model) {
            case 'cde':
                return Cde::find($id);
            // Ajoutez d'autres cas pour différents types d'entités
            default:
                return null;
        }
    }
}
