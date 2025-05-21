<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Téléchargement de documents</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 p-4">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4 text-center">Téléchargement de documents</h1>

        <div class="mb-6">
            <p class="text-center">
                Ajoutez des documents à
                <strong>{{ $model }} #{{ $id }}</strong>
                @if(isset($entity->reference))
                : {{ $entity->reference }}
                @endif
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('media.upload', ['model' => $model, 'id' => $id, 'token' => $token]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="files" class="block mb-2 text-sm font-medium">Sélectionnez des fichiers</label>
                <input id="files" type="file" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-sm border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none" required>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, PDF (max. 10MB)</p>
            </div>

            <div id="preview" class="grid grid-cols-3 gap-2 mt-4"></div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Télécharger
            </button>
        </form>
    </div>

    <script>
        document.getElementById('files').addEventListener('change', function() {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';

            if (this.files) {
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex items-center justify-center';

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
                        icon.innerHTML = '<svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                        div.appendChild(icon);
                    }

                    const caption = document.createElement('div');
                    caption.className = 'absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate';
                    caption.textContent = file.name;
                    div.appendChild(caption);

                    preview.appendChild(div);
                });
            }
        });
    </script>
</body>
</html>
