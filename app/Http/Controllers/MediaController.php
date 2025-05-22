<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Cde;
use App\Models\Ddp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    /**
     * Les classes de modèles autorisées pour l'attachement des médias
     */
    protected $allowedModels = [
        'cde' => Cde::class,
        'ddp' => Ddp::class,
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
            'files.*' => 'required|file|max:20480', // Augmenté à 20Mo
        ]);

        $entity = $this->getEntity($model, $id);

        if (!$entity) {
            return back()->withErrors(['Entité non trouvée']);
        }

        $successCount = 0;
        $errorFiles = [];

        foreach ($request->file('files') as $file) {
            try {
                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = 'media/' . $model . '/' . date('Y/m/d');

                // Utilisation de la méthode storeAs avec gestion de disque spécifique
                $filePath = $file->storeAs($path, $filename);

                $media = new Media([
                    'filename' => $filename,
                    'original_filename' => $originalFilename,
                    'path' => $filePath,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => Auth::getUser()->id,
                ]);

                $entity->media()->save($media);
                $successCount++;
            } catch (\Exception $e) {
                Log::error('Erreur lors du téléchargement du fichier', [
                    'file' => $originalFilename ?? 'inconnu',
                    'error' => $e->getMessage()
                ]);
                $errorFiles[] = $originalFilename ?? 'Un fichier';
            }
        }

        if (count($errorFiles) > 0) {
            return back()->with('warning', 'Certains fichiers n\'ont pas pu être téléchargés: ' . implode(', ', $errorFiles))
                        ->with('success', $successCount . ' fichier(s) téléchargé(s) avec succès');
        }

        return back()->with('success', 'Fichiers téléchargés avec succès');
    }

    /**
     * Affiche un média.
     */
    public function show($mediaid)
    {
        // Vérification des autorisations si nécessaire
        // Cette partie peut être adaptée selon vos règles d'autorisation

        $media = Media::find($mediaid);
        $path = $media->path;
        if (is_null($path)) {
            abort(404, 'Chemin du fichier non défini');
        }

        $path = Storage::path($path);

        if (!file_exists($path)) {
            abort(404, 'Fichier non trouvé');
        }
        $type = $media->mime_type ?? 'application/octet-stream';
        $fileContent = file_get_contents($path);

        $response = response($fileContent, 200);
        $response->header('Content-Type', $type);
        // Encode the filename for Content-Disposition header
        $encodedFilename = rawurlencode($media->original_filename);
        $response->header('Content-Disposition', "inline; filename=\"{$media->original_filename}\"; filename*=UTF-8''{$encodedFilename}");

        return $response;
    }

    /**
     * Télécharge un média.
     */
    public function download($mediaId)
    {
        // Vérification des autorisations si nécessaire
        // Cette partie peut être adaptée selon vos règles d'autorisation

        $media = Media::find($mediaId);
        if (!$media) {
            abort(404, 'Média non trouvé');
        }

        if (is_null($media->path)) {
            abort(404, 'Chemin du fichier non défini');
        }
        $path = Storage::path($media->path);
        return response()->download($path, $media->original_filename);
    }

    /**
     * Supprime un média.
     */
    public function destroy(Media $media)
    {
        Storage::delete($media->path);
        $media->delete();
        return back()->with('success', 'Fichier supprimé avec succès');
    }

    /**
     * Génère un lien signé pour l'upload via QR code.
     */
    public function generateQrLink($model, $id)
    {
        try {
            $token = Str::random(32);

            // Augmenter la durée de validité à 1 heures pour éviter les problèmes d'expiration
            $signedUrl = URL::temporarySignedRoute(
                'media.upload-form',
                now()->addHours(1),
                [
                    'model' => $model,
                    'id' => $id,
                    'token' => $token
                ]
            );

            $qrCodeSvg = QrCode::size(200)->generate($signedUrl);

            return response()->json([
                'success' => true,
                'qrCodeHtml' => $qrCodeSvg->toHtml()
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du QR code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du QR code: ' . $e->getMessage()
            ], 500);
        }
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
