<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="(auth()->check() && auth()->user()->is_admin) ? route('admin.bookings.index') : route('user.bookings.index')" :active="request()->routeIs('user.bookings.*') || request()->routeIs('admin.bookings.*')">
                        {{ __('Mis reservas') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="(auth()->user()->is_admin) ? route('admin.translations.index') : route('user.translations.index')" :active="request()->routeIs('user.translations.*') || request()->routeIs('admin.translations.*')">
                            {{ __('Mis traducciones') }}
                        </x-nav-link>
                    @endauth

                    <x-nav-link :href="(auth()->check() && auth()->user()->is_admin) ? route('admin.index') : route('contact.create')" :active="request()->routeIs('contact.create') || request()->routeIs('admin.index')">
                        {{ __('Contacto') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.index')">
                        {{ __('Servicios') }}
                    </x-nav-link>
                    
                </div>
            </div>

            <!-- Right side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- User dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ auth()->user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 me-4">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">Registrarse</a>
                @endguest
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="(auth()->user()->is_admin) ? route('admin.bookings.index') : route('user.bookings.index')" :active="request()->routeIs('user.bookings.*') || request()->routeIs('admin.bookings.*')">
                    {{ __('Mis reservas') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="(auth()->user()->is_admin) ? route('admin.translations.index') : route('user.translations.index')" :active="request()->routeIs('user.translations.*') || request()->routeIs('admin.translations.*')">
                    {{ __('Mis traducciones') }}
                </x-responsive-nav-link>
            @endauth

            <x-responsive-nav-link :href="(auth()->check() && auth()->user()->is_admin) ? route('admin.index') : route('contact.create')" :active="request()->routeIs('contact.create') || request()->routeIs('admin.index')">
                {{ __('Contacto') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.index')">
                {{ __('Servicios') }}
            </x-responsive-nav-link>
            @auth
                @if(auth()->user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.availability.index')" :active="request()->routeIs('admin.availability.*')">
                        {{ __('Disponibilidad') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.bookings.index')" :active="request()->routeIs('admin.bookings.*')">
                        {{ __('Reservas (admin)') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.translations.index')" :active="request()->routeIs('admin.translations.*')">
                        {{ __('Traducciones (admin)') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                @auth
                    <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                @else
                    <div class="font-medium text-base text-gray-800">Invitado</div>
                    <div class="font-medium text-sm text-gray-500">No has iniciado sesión</div>
                @endauth
            </div>

            <div class="mt-3 space-y-1">
                @auth
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endauth
            </div>
        </div>
    </div>
</nav>
