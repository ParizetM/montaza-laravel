<x-modal name="transfer-notif-modal" focusable :show="old('role_id')">
    <form method="POST" action="{{ route('notifications.transfer') }}" x-show="show" class="p-6">
        @csrf
        <div class="p-8">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Transférer les notifications') }}
            </h2>
            <div class="mt-4">
                <x-input-label for="role_id" :value="__('Sélectionner le rôle')"/>
                <<x-select_id_role :entites="$_entites" class="select" :selected_role="$role->id" />
                </select>
                <x-input-error :messages="$errors->get('role_id')" class="mt-2"/>
            </div>
            <div class="mt-4 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuler') }}
                </x-secondary-button>
                <x-primary-button class="ml-3">
                    {{ __('Transférer') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-modal>
