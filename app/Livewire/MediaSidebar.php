<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Media;
use App\Models\MediaType;
use App\Models\Cde;
use App\Models\Ddp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MediaSidebar extends Component
{
    use WithFileUploads;

    public $model;
    public $modelId;
    public $files = [];
    public $mediaList = [];
    public $mediaTypes = [];
    public $selectedMediaTypeId = null;
    public string|null $qrUrl = null;
    public int $qrDuration = 3600; // durée par défaut : 1 heure

    protected function rules()
    {
        $extensions = str_replace('.', '', implode(',', Media::AUTHORIZED_FILE_EXTENSIONS));

        return [
            'files.*' => "file|max:10240|mimes:{$extensions}",
        ];
    }

    protected $allowedModels = [
        'cde' => Cde::class,
        'ddp' => Ddp::class,
        // Ajoutez d'autres modèles selon vos besoins
    ];

    public function mount($model, $modelId)
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->mediaTypes = MediaType::all();
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
        if (isset($this->allowedModels[$this->model])) {
            $modelClass = $this->allowedModels[$this->model];
            return $modelClass::find($this->modelId);
        }
        return null;
    }

    public function updatedFiles()
    {
        $this->validate();

        $entity = $this->getEntity();

        if (!$entity) {
            return;
        }

        foreach ($this->files as $file) {
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $path = 'media/' . $this->model . '/' . date('Y/m/d');

            // Store the file
            $filePath = $file->storeAs($path, $filename);

            // Create media record
            $media = new Media([
                'filename' => $filename,
                'original_filename' => $originalFilename,
                'path' => str_replace('public/', '', $filePath),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => Auth::id(),
                'media_type_id' => $this->selectedMediaTypeId,
            ]);

            // Attach to entity
            $entity->media()->save($media);
        }

        // Reset the file input and refresh the list
        $this->files = [];
        $this->refreshMediaList();
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
            // Génère un token unique
            $token = Str::random(32);

            // Crée une URL signée temporaire avec la durée choisie
            $this->qrUrl = URL::temporarySignedRoute(
                'media.upload-form',
                now()->addSeconds($this->qrDuration),
                [
                    'model' => $this->model,
                    'id' => $this->modelId,
                    'token' => $token
                ]
            );
        } catch (\Exception $e) {
            \Log::error('QR Code URL generation failed: ' . $e->getMessage());
            $this->qrUrl = null;
        }
    }
    public function render()
    {
        return view('livewire.media-sidebar');
    }
}
