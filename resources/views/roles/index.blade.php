<x-app-layout>
    <x-slot name="header_nav">
        @include('permissions.navigation')
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class=" p-6 text-gray-900 dark:text-gray-100 ">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        {{ __('Poste') }}
                    </h2>
                    <form action="{{ route('permissions.edit', ['role' => $role->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        @isset($role)
                            <x-select_id_role :selected="$role->id" :entites="$entites" class="max-w-md select" />
                        @else
                            <x-select_id_role :entites="$entites" class="max-w-md select" />
                        @endisset

                        <div class="mt-4 m-w-3/4">
                            <x-input-label for="name" :value="__('Nom')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $role->name ?? '')" required autofocus />
                        </div>

                        <button class="btn mt-4">
                            {{ __('Modifier') }}
                        </button>


                    </form>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class=" p-6 text-gray-900 dark:text-gray-100 ">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ $users->count() }}
                        {{ __('Utilisateurs') }}
                    </h2>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <th scope="col">Prénom</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Action</th>
                        </thead>
                        <tbody
                            class="{{ request()->get('show_deleted') ? 'bg-gray-100 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' }} divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($users as $user)
                                <tr>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                        {{ $user->first_name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                        {{ $user->last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('profile.update_admin') }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center">
                                                <input type="hidden" name="id" value="{{ $user->id }}">
                                                <x-select_id_role placeholder="changer le poste" :entites="$entites"
                                                    class="block max-w-md select-left" />
                                                <button type="submit" class="btn-select-right">
                                                    <x-icon type="send" size="1" class=" icons-no_hover" />
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        document.querySelector('#role_id').addEventListener('change', function() {
            const roleId = this.value;
            const newUrl = `${window.location.origin}/postes/${roleId}`;
            window.location.href = newUrl;
        });
    </script>
</x-app-layout>
