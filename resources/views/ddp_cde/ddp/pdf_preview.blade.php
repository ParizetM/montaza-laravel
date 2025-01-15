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

            <ul>
                @foreach ($ddp->ddpligne as $ligne)
                    <li>
                        <strong>{{ $ligne->matiere->designation }}</strong>
                        <ul class="ml-12">
                            @foreach ($ligne->ddpLigneFournisseur as $fournisseur)
                                <li>{{ $fournisseur->societe->raison_sociale }}</li>
                                <li>{{ $fournisseur->societe_contact_id }}</li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
            <a href="{{ route('ddp.pdf', $ddp->id) }}" class="btn"> TEST</a>

            <div class="flex space-x-4">
                {{-- @dd($pdfs) --}}
                @foreach ($pdfs as $pdf)
                    @php
                        $ddpannee = explode('-', $ddp->code)[1];
                    @endphp

                    <object data="{{ route('ddp.pdfshow', ['ddp' => $ddp, 'annee' => $ddpannee, 'nom' => $pdf]) }}"
                         type="application/pdf" height="848px" width="600px">
                        <p>Il semble que vous n'ayez pas de plugin PDF pour ce navigateur. Pas de problème... vous pouvez <a href="{{ route('ddp.pdfshow', ['ddp' => $ddp, 'annee' => $ddpannee, 'nom' => $pdf]) }}">cliquer ici pour télécharger le fichier PDF.</a></p>
                    </object>
                @endforeach
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn">{{ __('Valider') }}</button>
            </div>
        </div>
    </div>
    <script></script>


</x-app-layout>
