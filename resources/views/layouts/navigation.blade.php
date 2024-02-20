<aside id="sidebar"
    class="fixed top-0 z-20 flex flex-col flex-shrink-0 w-64 h-full font-normal right-full lg:left-0 transition-all"
    >
    <div
        class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        

        <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
            <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <div class="h-32">
                    <x-application-logo />
                </div>
                <ul class="pb-2 space-y-2">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon='bi bi-bar-chart-fill'>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('materiales.index')" :active="request()->routeIs('materiales.index')" icon='bi bi-backpack2'>
                        Materiales
                    </x-nav-link>
                </ul>

                <div class="pt-2 space-y-2">
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 justify-centerw-full p-4 space-x-4 bg-white dark:bg-gray-800"
            sidebar-bottom-menu>
            <!-- Dropdown -->
            <div class="sm:flex sm:items-center">
                <x-dropdown align="top" width="48">
                    <x-slot name="trigger">
                        <button
                            class="text-lg inline-flex items-center px-3 py-2 border border-transparent leading-4 rounded-md text-gray-100 bg-transparent hover:text-gray-400 focus:outline-none transition ease-in-out duration-150">
                            <i class="bi bi-person-circle mr-5"></i>{{Auth::user()->name}}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
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
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</aside>
