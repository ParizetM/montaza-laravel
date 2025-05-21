<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Media;
use App\Models\Cde;
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
    public $qrCode = null;

    protected $rules = [
        'files.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf',
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
            $filePath = $file->storeAs('public/' . $path, $filename);

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

        // Generate QR code
        $this->qrCode = QrCode::size(200)->generate($signedUrl);
    }

    public function render()
    {
        return view('livewire.media-sidebar', [
            'qrCode' => $this->qrCode,
            'mediaList' => $this->mediaList,
        ]);
    }
}
