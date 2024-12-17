<div x-data="{ activeTab: 'tab1' }">
    <!-- Your modal code -->
    <x-modal name="notifications-modal" :show="session()->has('notification') && Route::currentRouteName() != 'notifications.index' ? true : false">
        <div class="p-4">
            <a x-on:click="$dispatch('close')">
                <x-icons.close  class="float-right mb-1 icons" size="1.5" unfocus/>
                </a>
            <div class="flex">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Notifications') }}
                </h2>

            </div>
            <div class="mt-4">
                @if (session('notification'))
                    <div class="bg-green-500 text-white p-2 rounded mb-4">
                        {{ session('notification') }}
                    </div>
                @endif
                <div class="flex border-b justify-between">
                <ul class="flex">
                    <li class="mr-1">
                        <a @click.prevent="activeTab = 'tab1'"
                            :class="activeTab === 'tab1' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'"
                            class="inline-block py-2 px-4" href="#">Tout
                            @if ($_notifications_count > 0)
                                <span id="notifications-count"
                                    class="relative bottom-2 right-4  inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notifications_count }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="mr-1">
                        <a @click.prevent="activeTab = 'tab2'"
                            :class="activeTab === 'tab2' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'"
                            class="inline-block py-2 px-4" href="#">Système
                            @if ($_notificationsSystem_count > 0)

                            <span id="notifications-system-count"
                                class="relative bottom-2 right-4  inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notificationsSystem_count }}</span>
                            @endif
                            </a>
                    </li>
                    {{-- <li class="mr-1">
                        <a @click.prevent="activeTab = 'tab3'"
                            :class="activeTab === 'tab3' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'"
                            class="inline-block py-2 px-4" href="#">Lu</a>
                    </li> --}}
                </ul>
                <a href="{{ route('notifications.index') }}" class="btn float-right p-2 py-1 my-1">
                    Voir tout
                </a>
            </div>
                <div>
                    <div x-show="activeTab === 'tab1'" id="notif-tab1">
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    fetch('{{ route('notifications.fetch',['type'=> 'all','specifyType' => true]) }}')
                                        .then(response => response.text())
                                        .then(html => {
                                            document.getElementById('notif-tab1').innerHTML = html;
                                        })
                                        .catch(error => console.error('Error fetching notifications:', error));
                                });
                            </script>
                    </div>
                    <div x-show="activeTab === 'tab2'" id="notif-tab2">
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                fetch('{{ route('notifications.fetch',['type'=> 'system']) }}')
                                    .then(response => response.text())
                                    .then(html => {
                                        document.getElementById('notif-tab2').innerHTML = html;
                                    })
                                    .catch(error => console.error('Error fetching notifications:', error));
                            });
                        </script>
                    </div>

                </div>
            </div>
        </div>
    </x-modal>
</div>
