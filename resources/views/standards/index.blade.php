<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}" class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Matière</a>
                >>
                {!! __('Standards') !!}
            </h2>
        </div>

    </x-slot>

    <div class="py-8 text-gray-800 dark:text-gray-200 ">
        <div class="container mx-auto bg-white dark:bg-gray-800 p-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Standards') !!}
            </h2>
            <ul class=" pl-5 ">
                @foreach ($folders as $folder)
                    <li x-data="{ open: false }" class="">
                        <div @click="open = !open" class="cursor-pointer flex items-center hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">

                            <x-icons.folder show="!open" class="w-6 h-6" />
                            <x-icons.open-folder show="open" class="w-6 h-6" />
                            <strong class="text-lg ml-1"> {{ $folder->nom }}</strong>
                        </div>
                        <ul x-show="open"
                            class="list-inside pl-5 mt-2 transition-all duration-300 ease-in-out overflow-hidden">
                            @foreach ($folder->standards as $standard)
                                @foreach ($standard->versions as $version)
                                    <li class="text-gray-700 dark:text-gray-300 pl-8 flex border-l hover:bg-gray-100 hover:dark:bg-gray-700 rounded-r"> <x-icons.pdf
                                            class="w-6 h-6" /><a href="{{ $version }}" class="lien">
                                            {{ $version->standard->nom }} - {{ $version->version }}
                                        </a>
                                        <button class="ml-auto" wire:click="deleteVersion({{ $version->id }})">
                                            <x-icons.trash class="w-6 h-6" />
                                        </button>
                                    </li>



                                @endforeach

                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</x-app-layout>
