<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Media;
use App\Models\Cde;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class MediaSidebar extends Component
{
    use WithFileUploads;

    public $model;
    public $modelId;
    public $files = [];
    public $mediaList = [];
    public $qrCode = null;

    // Définir une taille maximale plus petite pour les fichiers
    protected $rules = [
        'files.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf', // 5MB max
    ];

    public function mount($model, $modelId)
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->refreshMediaList();
    }

    public function refreshMediaList()
    {
        $entity = $this->getEntity();

        if ($entity && method_exists($entity, 'media')) {
            $this->mediaList = $entity->media()->with('user')->get();
        } else {
            $this->mediaList = collect();
        }
    }

    protected function getEntity()
    {
        switch ($this->model) {
            case 'cde':
                return Cde::find($this->modelId);
            // Ajoutez d'autres cas pour différents types d'entités
            default:
                return null;
        }
    }

    public function updatedFiles()
    {
        try {
            $this->validate();

            $entity = $this->getEntity();

            if (!$entity) {
                return;
            }

            foreach ($this->files as $file) {
                // Vérifier que le fichier est valide
                if (!$file || !$file->isValid()) {
                    Log::error('Fichier invalide lors de l\'upload', [
                        'filename' => $file ? $file->getClientOriginalName() : 'unknown',
                    ]);
                    continue;
                }

                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = 'media/' . $this->model . '/' . date('Y/m/d');

                // Vérifier que le répertoire de destination existe
                $fullPath = 'public/' . $path;
                if (!Storage::exists($fullPath)) {
                    Storage::makeDirectory($fullPath);
                }

                // Store the file
                $filePath = $file->storeAs($fullPath, $filename);

                if (!$filePath) {
                    Log::error('Échec du stockage du fichier', [
                        'original' => $originalFilename,
                        'path' => $fullPath,
                    ]);
                    continue;
                }

                // Create media record
                $media = new Media([
                    'filename' => $filename,
                    'original_filename' => $originalFilename,
                    'path' => str_replace('public/', '', $filePath),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);

                // Attach to entity
                $entity->media()->save($media);
            }

            // Reset the file input and refresh the list
            $this->files = [];
            $this->refreshMediaList();
        } catch (\Exception $e) {
            Log::error('Exception lors de l\'upload de fichier', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Une erreur est survenue lors de l\'upload: ' . $e->getMessage());
        }
    }

    public function downloadMedia($mediaId)
    {
        $media = Media::find($mediaId);

        if (!$media) {
            return;
        }

        // Generate a download response
        return response()->download(
            Storage::path('public/' . $media->path),
            $media->original_filename
        );
    }

    public function deleteMedia($mediaId)
    {
        $media = Media::find($mediaId);

        if (!$media) {
            return;
        }

        // Delete the file
        Storage::delete('public/' . $media->path);

        // Delete the record
        $media->delete();

        // Refresh the list
        $this->refreshMediaList();
    }

    public function generateQrCode()
    {
        try {
            // Generate a unique token
            $token = Str::random(32);

            // Create a signed URL that expires in 10 minutes
            $signedUrl = URL::temporarySignedRoute(
                'media.upload-form',
                now()->addMinutes(10),
                [
                    'model' => $this->model,
                    'id' => $this->modelId,
                    'token' => $token
                ]
            );

            // Generate QR code as SVG string instead of HTML
            $this->qrCode = QrCode::size(200)->generate($signedUrl);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du QR code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.media-sidebar');
    }
}
