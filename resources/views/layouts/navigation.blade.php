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
                    @auth
                        <!-- Dashboard sesuai role -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <i class="bi bi-speedometer2 mr-2"></i> Dashboard
                        </x-nav-link>

                        <!-- Menu khusus Supervisor -->
                        @if (auth()->user()->role === 'supervisor')
                            <x-nav-link :href="route('karyawan.index')" :active="request()->routeIs('karyawan.*')">
                                <i class="bi bi-people mr-2"></i> Karyawan
                            </x-nav-link>
                        @endif

                        <!-- Menu untuk Pemilik dan Supervisor -->
                        @if (in_array(auth()->user()->role, ['pemilik', 'supervisor']))
                            <x-nav-link href="#" :active="false">
                                <i class="bi bi-file-earmark-text mr-2"></i> Laporan
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                <div class="mr-2">
                                    @php
                                        $user = auth()->user();
                                        $badgeColor = '';
                                        if ($user->role === 'pemilik') {
                                            $badgeColor = 'bg-yellow-100 text-yellow-800';
                                        } elseif ($user->role === 'supervisor') {
                                            $badgeColor = 'bg-blue-100 text-blue-800';
                                        } else {
                                            $badgeColor = 'bg-gray-100 text-gray-800';
                                        }
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                                <div>{{ $user->name }}</div>
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
                        <div class="px-4 py-2 text-xs text-gray-500">
                            Logged in as: <span class="font-semibold">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                        <div class="border-t border-gray-100"></div>

                        @if (auth()->user()->role === 'supervisor')
                            <x-dropdown-link :href="route('karyawan.index')">
                                <i class="bi bi-people mr-2"></i> Kelola Karyawan
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="bi bi-person-circle mr-2"></i> Profile
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div class="border-t border-gray-100"></div>
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right mr-2"></i> {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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
            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="bi bi-speedometer2 mr-2"></i> Dashboard
            </x-responsive-nav-link>

            <!-- Menu khusus Supervisor (Mobile) -->
            @if (auth()->user()->role === 'supervisor')
                <x-responsive-nav-link :href="route('karyawan.index')" :active="request()->routeIs('karyawan.*')">
                    <i class="bi bi-people mr-2"></i> Karyawan
                </x-responsive-nav-link>
            @endif

            <!-- Menu untuk Pemilik dan Supervisor (Mobile) -->
            @if (in_array(auth()->user()->role, ['pemilik', 'supervisor']))
                <x-responsive-nav-link href="#">
                    <i class="bi bi-file-earmark-text mr-2"></i> Laporan
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="flex items-center">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="ml-2">
                        @php
                            $user = auth()->user();
                            $badgeColor = '';
                            if ($user->role === 'pemilik') {
                                $badgeColor = 'bg-yellow-100 text-yellow-800';
                            } elseif ($user->role === 'supervisor') {
                                $badgeColor = 'bg-blue-100 text-blue-800';
                            } else {
                                $badgeColor = 'bg-gray-100 text-gray-800';
                            }
                        @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if (auth()->user()->role === 'supervisor')
                    <x-responsive-nav-link :href="route('karyawan.index')">
                        <i class="bi bi-people mr-2"></i> Kelola Karyawan
                    </x-responsive-nav-link>
                @endif

                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="bi bi-person-circle mr-2"></i> Profile
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right mr-2"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
