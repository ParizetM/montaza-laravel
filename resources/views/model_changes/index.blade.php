@php
    $ommited_keys = [
        'created_at',
        'updated_at',
        'password',
        'remember_token',
        'email_verified_at',
        'id',
        'undeletable',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Historique') !!}
            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center ">

                <form method="GET" action="{!! route('model_changes.index') !!}"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                    <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">

                    <div class="flex items-center my-1">
                        <label for="start_date"
                            class="mx-4 text-gray-900 dark:text-gray-100">{!! __('Après le ') !!}</label>
                        <input type="date" name="start_date" onblur="updateDateInputs(this)" id="start_date"
                            value="{!! old('start_date', request('start_date')) !!}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center ml-4 my-1 ">
                        <label for="end_date"
                            class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Avant le') !!}</label>
                        <input type="date" name="end_date" onblur="updateDateInputs(this)" id="end_date"
                            value="{!! old('end_date', request('end_date')) !!}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center ml-4 my-1 ">
                        <label for="nombre"
                            class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                        <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 50)) !!}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="ml-2 btn sm:mt-0 md:mt-0 lg:mt-0">
                        {!! __('Rechercher') !!}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Utilisateur
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        type de modéle
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Changement
                                    </th>

                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Type de changement
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="{!! request()->get('show_deleted') ? 'bg-gray-100 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' !!} divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($modelChanges as $change)
                                    @php
                                        $before = $change->before;
                                        $after = $change->after;
                                        $event = $change->event;

                                        if (
                                            isset($after['first_name']) &&
                                            $event === 'updating' &&
                                            $before['deleted_at'] !== null &&
                                            $after['deleted_at'] == null
                                        ) {
                                            $event = 'restauré le ' . $change->created_at->format('d/m/Y à H:i');
                                            $before = 'custom';
                                            $after =
                                                'Compte ' .
                                                $after['first_name'] .
                                                ' ' .
                                                $after['last_name'] .
                                                ' restauré';
                                        }
                                        if (
                                            isset($after['name']) &&
                                            $event === 'updating' &&
                                            $before['deleted_at'] !== null &&
                                            $after['deleted_at'] == null
                                        ) {
                                            $event = 'restauré le ' . $change->created_at->format('d/m/Y à H:i');
                                            $before = 'custom';
                                            $after = $after['name'];
                                        }

                                        if (isset($before['role_id'])) {
                                            $before['role_id'] =
                                                \App\Models\Role::find($before['role_id'])->name ?? 'Unknown';
                                        }
                                        if (isset($after['role_id'])) {
                                            $after['role_id'] =
                                                \App\Models\Role::find($after['role_id'])->name ?? 'Unknown';
                                        }
                                        if (isset($before['entite_id'])) {
                                            $before['entite_id'] =
                                                \App\Models\Entite::find($before['entite_id'])->name ?? 'Unknown';
                                        }
                                        if (isset($after['entite_id'])) {
                                            $after['entite_id'] =
                                                \App\Models\Entite::find($after['entite_id'])->name ?? 'Unknown';
                                        }
                                        if ($event === 'creating') {
                                            $before = ' ';
                                            $event = 'Créé le ' . $change->created_at->format('d/m/Y à H:i') . '.';
                                        }
                                        if ($event === 'updating') {
                                            $event = 'Modifié le ' . $change->created_at->format('d/m/Y à H:i') . '.';
                                        }
                                        if ($event === 'deleting') {
                                            $before = ' ';
                                            unset($after['deleted_at']);
                                            $event = 'Supprimé le ' . $change->created_at->format('d/m/Y à H:i') . '.';
                                        }

                                        $change->event = $event;
                                        $change->before = $before;
                                        $change->after = $after;
                                    @endphp
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                            {!! $change->user->first_name ?? 'N/A' !!}
                                            {!! $change->user->last_name ?? '' !!}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {!! $change->model_type !!}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 flex">
                                            @if ($change->before == 'custom')
                                                {!! $change->after !!}
                                            @elseif (is_array($change->before) && is_array($change->after))
                                                <table>
                                                    @foreach ($change->before as $key => $value)
                                                        @if ($change->after[$key] != $value && !in_array($key, $ommited_keys))
                                                            @php
                                                                $value = $value === null ? 'null' : $value;
                                                            @endphp
                                                            <tr>
                                                                <td><strong>{!! $key !!} :</strong></td>
                                                                <td class="flex">{!! $value !!}<x-icon
                                                                        size="1" type="arrow_forward"
                                                                        class="icons-no_hover mt-1" />{!! $change->after[$key] == null ? 'N/A' : $change->after[$key] !!}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @elseif (is_array($change->before) && !is_array($change->after))
                                                <table>
                                                    @foreach ($change->before as $key => $value)
                                                        @if (!in_array($key, $ommited_keys))
                                                            @php
                                                                $value = $value === null ? 'null' : $value;
                                                            @endphp
                                                            <tr>
                                                                <td><strong>{!! $key !!} :</strong></td>
                                                                <td class="flex">{!! $value !!}<x-icon
                                                                        size="1" type="arrow_forward"
                                                                        class="icons-no_hover mt-1" />{!! $change->after == null ? 'N/A' : $change->after !!}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @elseif (!is_array($change->before) && is_array($change->after))
                                                <table>
                                                    @foreach ($change->after as $key => $value)
                                                        @if (!in_array($key, $ommited_keys))
                                                            @php
                                                                $value = $value === null ? 'null' : $value;
                                                            @endphp
                                                            <tr>
                                                                <td><strong>{!! $key !!} :</strong></td>
                                                                <td class="flex">
                                                                    {!! $change->before == null ? 'N/A' : $change->before !!}<x-icon size="1"
                                                                        type="arrow_forward"
                                                                        class="icons-no_hover mt-1" />{!! $value !!}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @else
                                                @if ($change->before === null && $change->after === null)
                                                    N/A
                                                @else
                                                    {!! $change->before === null ? 'N/A' : $change->before !!}
                                                    @if (!($change->before === null && $change->after === null))
                                                        <x-icon size="1" type="arrow_forward"
                                                            class="icons-no_hover mt-1" />
                                                    @endif
                                                    {!! $change->after === null ? 'N/A' : $change->after !!}
                                                @endif
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {!! $change->event !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-center items-center pb-3">
                        <div>
                            {{ $modelChanges->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
