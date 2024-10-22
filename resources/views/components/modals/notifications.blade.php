<div x-data="{ activeTab: 'tab1' }">
    <!-- Your modal code -->
    <x-modal name="notifications-modal" :show="session()->has('notification')"
     focusable>
        <div class="p-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Notifications') }}
            </h2>
            <div class="mt-4">
                @if (session('notification'))
                    <div class="bg-green-500 text-white p-2 rounded mb-4">
                        {{ session('notification') }}
                    </div>
                @endif
                <ul class="flex border-b">
                    <li class="mr-1">
                        <a @click.prevent="activeTab = 'tab1'"
                            :class="activeTab === 'tab1' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'"
                            class="inline-block py-2 px-4" href="#">Tout
                            <span class=" inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $notifications->count() }}</span>
                        </a>
                    </li>
                    <li class="mr-1">
                        <a @click.prevent="activeTab = 'tab2'"
                            :class="activeTab === 'tab2' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'"
                            class="inline-block py-2 px-4" href="#">Système
                            <span class=" inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $notificationsSystem->count() }}</span>
                        </a>
                    </li>
                    <li class="mr-1">
                        <a @click.prevent="activeTab = 'tab3'"
                            :class="activeTab === 'tab3' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'"
                            class="inline-block py-2 px-4" href="#">Lu</a>
                    </li>
                </ul>
                <div>
                    <div x-show="activeTab === 'tab1'">
                        <x-table-notifications :notifications="$notifications" :specifyType="true" />
                    </div>
                    <div x-show="activeTab === 'tab2'">
                        <x-table-notifications :notifications="$notificationsSystem" />
                    </div>
                    <div x-show="activeTab === 'tab3'">
                        TEST 3
                    </div>
                </div>
            </div>
        </div>
    </x-modal>
</div>
