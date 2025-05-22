<!-- filepath: c:\Users\prepaetude\Homestead\code\montaza\resources\views\media\upload-form.blade.php -->
<x-guest-layout>
    <div class="py-2">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 text-center">
            Téléchargement de documents
        </h2>

        <div class="mb-6 text-center">
            <p class="text-gray-700 dark:text-gray-300">
                Ajoutez des documents à
                <strong class="font-medium">{{ $model }} #{{ $id }}</strong>
                @if(isset($entity->reference))
                : <span class="font-medium">{{ $entity->reference }}</span>
                @endif
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 p-3 rounded-md">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/media/upload/'.$model.'/'.$id.'/'.$token.'?signature='.request()->query('signature').'&expires='.request()->query('expires')) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf

            <div>
                <x-input-label for="files" :value="__('Sélectionnez des fichiers')" />
                <div class="mt-1">
                    <input id="files"
                           type="file"
                           name="files[]"
                           multiple
                           accept=".jpg,.jpeg,.png,.pdf"
                           class="input-file w-full"
                           required>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, PDF (max. 5MB)</p>
            </div>

            <div id="preview" class="grid grid-cols-3 gap-2 mt-4"></div>

            <div class="flex items-center justify-center mt-6">
                <button type="submit" class="btn">
                    Télécharger
                </button>
            </div>
        </form>
    </div>

    <script>
        // Fonction pour formater la taille du fichier
        function formatFileSize(bytes) {
            if (bytes < 1024) {
                return bytes + ' B';
            } else if (bytes < 1024 * 1024) {
                return (bytes / 1024).toFixed(1) + ' KB';
            } else {
                return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
            }
        }

        document.getElementById('files').addEventListener('change', function() {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';

            if (this.files) {
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square bg-gray-100 dark:bg-gray-750 rounded-lg overflow-hidden flex items-center justify-center';

                    if (file.type.startsWith('image/')) {
                        reader.onload = e => {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'h-full w-full object-cover';
                            div.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        const icon = document.createElement('div');
                        icon.innerHTML = '<svg class="w-8 h-8 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                        div.appendChild(icon);
                    }

                    const caption = document.createElement('div');
                    caption.className = 'absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 ';
                    caption.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
                    div.appendChild(caption);

                    preview.appendChild(div);
                });
            }
        });
    </script>
</x-guest-layout>
