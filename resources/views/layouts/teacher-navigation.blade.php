<nav class="relative">
    <div class="fixed top-6 left-6 z-50">
        <button @click="sidebarOpen = !sidebarOpen"
            class="inline-flex items-center justify-center h-10 w-10 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
            <span class="sr-only">Open navigation panel</span>
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <aside x-show="sidebarOpen"
        x-transition:enter="transition transform duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-0 bottom-0 left-0 z-50 w-72 max-w-full overflow-y-auto bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 shadow-xl"
        @click.outside="sidebarOpen = false">
        <div class="flex h-full flex-col py-8">
            <div class="px-5 flex items-center justify-between">

                <button @click="sidebarOpen = false" class="inline-flex items-center justify-center h-9 w-9 rounded-md text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="sr-only">Close navigation panel</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="mt-6 px-5 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                

                <x-responsive-nav-link :href="route('classroom.index')" :active="request()->routeIs('classroom.index')">
                    {{ __('Classroom') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.index')">
                    {{ __('Classes') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('assignments.index')" :active="request()->routeIs('assignments.index')">
                    {{ __('Assignments') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('videoclass.index')" :active="request()->routeIs('videoclass.index')">
                    {{ __('Video Class') }}
                </x-responsive-nav-link>

            </nav>

            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 px-4 pt-5">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>

                <div class="mt-4 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>
</nav>
