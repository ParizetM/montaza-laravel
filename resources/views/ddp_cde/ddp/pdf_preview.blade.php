<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                >>
                <a href="{{ route('ddp.show', $ddp->id) }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">{!! __('Créer une demande de prix') !!}</a>
                >> Validation
            </h2>
        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold mb-6 text-left">{{ $ddp->nom }} - Récapitulatif</h1>
                <a href="{{ route('ddp.pdfs.download', $ddp) }}" class="btn">Télécharger tous les PDF</a>

            </div>
            <div class="flex flex-wrap gap-4">
                {{-- @dd($pdfs) --}}
                @foreach ($pdfs as $pdf)
                    @php
                        $ddpannee = explode('-', $ddp->code)[1];
                    @endphp
                    <div class="flex flex-col gap-2 bg-gray-100 dark:bg-gray-700 p-4 rounded-md hover:scale-105 cursor-pointer transition-all relative"
                        id="pdf-{{ $pdf }}" title="Ouvrir le PDF">
                        <h2
                            class="text-xl font-semibold text-gray-700 dark:text-gray-200  border border-gray-300 dark:border-gray-700 pb-2">
                            {{ explode('_', $pdf)[count(explode('_', $pdf)) - 1] }}</h2>
                        <div style="background-color: rgba(0,0,0,0); height: 424px; width: 300px;"
                            class="absolute bottom-4"></div>
                        <object data="{{ route('ddp.pdfshow', ['ddp' => $ddp, 'annee' => $ddpannee, 'nom' => $pdf]) }}"
                            type="application/pdf" height="424px" width="300px">
                            <p>Il semble que vous n'ayez pas de plugin PDF pour ce navigateur. Pas de problème... vous
                                pouvez <a
                                    href="{{ route('ddp.pdfshow', ['ddp' => $ddp, 'annee' => $ddpannee, 'nom' => $pdf]) }}">cliquer
                                    ici pour télécharger le fichier PDF.</a></p>
                        </object>
                    </div>
                @endforeach
            </div>
            <div>
                <div class="flex justify-between items-center border-b border-gray-300 dark:border-gray-700 mt-6 mb-4">
                    <h1 class="text-3xl font-bold mb-6 text-left">Mails</h1>
                    <a href="{{ route('ddp.pdfs.download', $ddp) }}" class="btn">Passer cette étape</a>
                </div>
                <div>
                    <div class="mb-4">
                        <x-input-label for="email_subject" :value="__('Objet du mail')" />
                        <x-text-input id="email_subject" class="block mt-1 w-full" type="text" name="email_subject" required autofocus />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="email_body" :value="__('Contenu du mail')" />
                        <textarea id="email_body" name="email_body" rows="10" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 dark:text-gray-200"></textarea>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <script>
        document.querySelectorAll('[id^="pdf-"]').forEach(function(element) {
            element.addEventListener('click', function() {
                const pdfUrl = element.querySelector('object').data;
                window.open(pdfUrl, '_blank');
            });
        });
    </script>


</x-app-layout>
