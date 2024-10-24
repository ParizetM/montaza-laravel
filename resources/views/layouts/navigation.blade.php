<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-8xl mx-auto px-4 sm:px-6 ">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                        <x-application-logo class="block h-14 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </x-nav-link>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if (Auth::check())

                    <div class="relative">
                        <button x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'notifications-modal')"
                            class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <x-icon type="bell" :size="1.5" class="icons" />
                            @if ($_notifications->count() > 0)
                                <span
                                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notifications_count }}</span>
                            @endif
                        </button>

                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>
                                    @if (Auth::check())
                                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                    @endif
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit', ['id' => Auth::user()->id])">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                            @if (Auth::user()->hasPermission('gerer_les_utilisateurs'))
                                <x-dropdown-link :href="route('profile.index')">
                                    {{ __('utilisateurs') }}
                                </x-dropdown-link>
                            @endif
                            @if (Auth::user()->hasPermission('gerer_les_permissions') || Auth::user()->hasPermission('gerer_les_postes'))
                                <x-dropdown-link :href="route('permissions')">
                                    {{ __('Permissions et Postes') }}
                                </x-dropdown-link>
                            @endif

                        </x-slot>
                    </x-dropdown>
                    <x-modals.notifications />
                @else
                    <a href="{{ route('login') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        {{ __('Log in') }}
                    </a>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Navigation Links -->
            @if (Auth::check())
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                @if (Auth::check())
                    <div class="grid-cols-2 grid">
                        <div>
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ Auth::user()->first_name }}
                                {{ Auth::user()->last_name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <div>
                            <div class="float-right">
                                <a href="{{ route('notifications.index') }}"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">

                                    <x-icon :size="1.5" type="bell" class="icons " />
                                    @if ($_notifications->count() > 0)
                                        <span id="notifications-count"
                                            class="relative bottom-3 right-4 inline-flex items-center justify-center px-1.5 py-1 text-xs font-semibold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notifications->count() }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                @if (Auth::check())
                    <x-responsive-nav-link :href="route('profile.edit', ['id' => Auth::user()->id])">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                    @if (Auth::user()->hasPermission('gerer_les_utilisateurs'))
                        <x-responsive-nav-link :href="route('profile.index')">
                            {{ __('utilisateurs') }}
                        </x-responsive-nav-link>
                    @endif
                    @if (Auth::user()->hasPermission('gerer_les_permissions') && Auth::user()->hasPermission('gerer_les_postes'))
                        <x-responsive-nav-link :href="route('permissions')">
                            {{ __('Permissions et Postes') }}
                        </x-responsive-nav-link>
                    @else
                        @if (Auth::user()->hasPermission('gerer_les_permissions'))
                            <x-responsive-nav-link :href="route('permissions')">
                                {{ __('Permissions') }}
                            </x-responsive-nav-link>
                        @endif
                        @if (Auth::user()->hasPermission('gerer_les_postes'))
                            <x-responsive-nav-link :href="route('postes')">
                                {{ __('Postes') }}
                            </x-responsive-nav-link>
                        @endif
                    @endif
                @else
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                @endif

            </div>
        </div>
    </div>
</nav>
<script>
    function marquerCommeLu(notificationId) {

        const notificationCountElements = document.querySelectorAll('#notifications-count');
        notificationCountElements.forEach(notificationCountElement => {
            let count = parseInt(notificationCountElement.textContent);
            if (!isNaN(count) && count > 0) {
            notificationCountElement.textContent = count - 1;
            }
        });
        const notificationElement = document.getElementById(`notification-${notificationId}`);
        if (notificationElement && notificationElement.classList.contains('system')) {
            const notificationSystemCountElements = document.querySelectorAll('#notifications-system-count');
            notificationSystemCountElements.forEach(notificationSystemCountElement => {
                let count = parseInt(notificationSystemCountElement.textContent);
                if (!isNaN(count) && count > 0) {
                    notificationSystemCountElement.textContent = count - 1;
                }
            });
        }
        while (document.getElementById(`notification-${notificationId}`)) {
            document.getElementById(`notification-${notificationId}`).remove();
        }
        fetch(`/notifications/${notificationId}/lu`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                // Optionally, you can update the UI to reflect the notification as read
                console.log('Notification marked as read');

            } else {
                console.error('Failed to mark notification as read');
            }
        }).catch(error => {
            console.error('Error:', error);
        });

    }
    function marquerCommeNonLu(notificationId) {
        document.getElementById(`notification-${notificationId}`).remove();
        fetch(`/notifications/${notificationId}/non-lu`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                // Optionally, you can update the UI to reflect the notification as read
                console.log('Notification marked as unread');

            } else {
                console.error('Failed to mark notification as unread');
            }
        }).catch(error => {
            console.error('Error:', error);
        });

    }
</script>
