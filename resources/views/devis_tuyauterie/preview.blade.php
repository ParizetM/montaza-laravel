<x-app-layout>
    @section('title', 'Prévisualisation Devis - ' . ($devis->reference_projet ?? $devis->id))
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('devis_tuyauterie.index') }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Devis</a>
                >>
                <a href="{{ route('devis_tuyauterie.show', $devis->id) }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Détail Devis</a>
                >> Prévisualisation
            </h2>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center flex-wrap mb-6">
                <h1 class="text-3xl font-bold text-left mr-2">Devis {{ $devis->reference_projet ?? $devis->id }}</h1>
                <div class="flex gap-2">
                    <a href="{{ route('devis_tuyauterie.download_pdf', $devis->id) }}" class="btn inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Télécharger le PDF
                    </a>
                    <a href="{{ route('devis_tuyauterie.show', $devis->id) }}" class="btn inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Retour
                    </a>
                </div>
            </div>

            <div class="w-full h-screen">
                <object data="{{ route('devis_tuyauterie.pdf', $devis->id) }}" type="application/pdf" width="100%" height="100%">
                    <p>Il semble que vous n'ayez pas de plugin PDF pour ce navigateur. Pas de problème... vous pouvez <a href="{{ route('devis_tuyauterie.download_pdf', $devis->id) }}">cliquer ici pour télécharger le fichier PDF.</a></p>
                </object>
            </div>
        </div>
    </div>
</x-app-layout>
