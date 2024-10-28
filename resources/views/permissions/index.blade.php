<x-app-layout>
    <x-slot name="header_nav">
        @include('permissions.navigation')
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class=" p-6 text-gray-900 dark:text-gray-100 ">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        {{ __('Poste') }}
                    </h2>
                    <form action="{{ route('permissions.edit') }}" method="post">
                        @csrf
                        @method('PUT')
                        @isset($role)
                            <x-select_id_role :selected="$role->id" :entites="$entites" class="max-w-md select" />
                        @else
                            <x-select_id_role :entites="$entites" class="max-w-md select" />
                        @endisset


                        <div class="mt-6">

                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                {{ __('Permissions') }}
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($permissions as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="permission-{{ $permission->id }}"
                                            value="{{ $permission->id }}" id="permission-{{ $permission->id }}"
                                            class="mr-2"
                                            @php
                                                if (isset($role)) {
                                                    foreach ($role->permissions as $role_permission) {
                                                        if ($role_permission->id == $permission->id) {
                                                            echo 'checked';
                                                        }
                                                    }
                                                } @endphp>
                                        <label for="permission-{{ $permission->id }}"
                                            class="text-gray-900 dark:text-gray-100">{{ str_replace('_', ' ', $permission->name) }}
                                            <small class="text-gray-500 dark:text-gray-400"><br/>{{ $permission->description }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn">
                                    Mettre à jour les permissions
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        document.querySelector('#role_id').addEventListener('change', function() {
            const roleId = this.value;
            const newUrl = `${window.location.origin}/permissions/${roleId}`;
            window.location.href = newUrl;
        });
    </script>
</x-app-layout>
