<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier un modèles de Mail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 w-full text-">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 text-gray-700 dark:text-gray-300">
                @if ($errors->any())
                    <div class="alert alert-danger text-red-500">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('mailtemplates.update', $mailtemplate->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <h1 class="text-3xl font-bold mb-6 text-left">{{ $mailtemplate->nom }}</h1>
                    <div class="w-1/2">
                        <x-input-label value="Sujet" />
                        <x-text-input id="sujet" class="block mt-1 w-full" type="text" name="sujet" required
                            autofocus value="{{ $mailtemplate->sujet }}" />
                    </div>
                    <div class="mt-4">
                        <x-input-label value="Contenu" />
                        <textarea id="content" name="contenu" rows="10"
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 dark:text-gray-200">{{ $mailtemplate->contenu }}</textarea>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('mailtemplates.index') }}" class="btn float-left">Annuler</a>
                        <button class="btn float-right" type="submit">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
