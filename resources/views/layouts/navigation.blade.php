<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg">
                        {{ __('Worktify.') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-8 absolute left-1/2 transform -translate-x-1/2 mt-3">
                    

                    {{-- PERBAIKAN: Menambahkan @auth untuk memastikan user sudah login --}}
                    @auth
                        {{-- Menu untuk Admin --}}
                        @if(Auth::user()->role == 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.pegawai.index')" :active="request()->routeIs('admin.pegawai.*')">
                                {{ __('Employee') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.gaji.index')" :active="request()->routeIs('admin.gaji.*')">
                                {{ __('Salary') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.jabatan.index')" :active="request()->routeIs('admin.jabatan.*')">
                                {{ __('Position') }}
                            </x-nav-link>
                            <div class="flex items-center relative">
                                <!-- Tombol Icon -->
                                <button @click="open = !open" class="p-2 text-gray-700 hover:text-gray-900">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div 
                                    x-show="open" 
                                    @click.away="open = false"
                                    class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                                >
                                    <a href="{{ route('admin.tim-divisi.index') }}"
                                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.tim-divisi.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                        Tim & Divisi
                                    </a>

                                    <a href="{{ route('admin.tunjangan-potongan.index') }}"
                                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.tunjangan-potongan.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                        Tunjangan & Potongan
                                    </a>

                                    <a href="{{ route('admin.meeting.index') }}"
                                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.meeting.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                        Meeting
                                    </a>

                                    <a href="{{ route('admin.cuti.index') }}"
                                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.cuti.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                        Manajemen Cuti
                                    </a>

                                    <a href="{{ route('admin.pengumuman.index') }}"
                                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.pengumuman.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                        Pengumuman
                                    </a>

                                    <a href="{{ route('admin.laporan.performa') }}"
                                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.laporan.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                        Laporan
                                    </a>

                                    <a href="{{ route('admin.tugas_pengumpulan.index') }}"
                                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.tugas_pengumpulan.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                        Pengumpulan Tugas
                                    </a>

                                    <a href="{{ route('admin.tugas.index') }}"
                                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.tugas.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                        Tugas
                                    </a>

                                    <a href="{{ route('admin.kehadiran.index') }}"
                                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.kehadiran.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                        Kehadiran Pegawai
                                    </a>
                                </div>
                            </div>

                        {{-- Menu untuk Pegawai --}}
                        @elseif(Auth::user()->role == 'pegawai')
                            <div class="hidden sm:flex space-x-8 absolute left-1/2 transform -translate-x-1/2">
                                <x-nav-link :href="route('pegawai.dashboard')" :active="request()->routeIs('pegawai.dashboard')">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('pegawai.gaji')" :active="request()->routeIs('pegawai.gaji*')">
                                    {{ __('Gaji') }}
                                </x-nav-link>
                                <x-nav-link :href="route('pegawai.kehadiran.index')" :active="request()->routeIs('pegawai.kehadiran.*')">
                                    {{ __('Kehadiran') }}
                                </x-nav-link>
                                <x-nav-link :href="route('pegawai.tugas.index')" :active="request()->routeIs('pegawai.tugas.*')">
                                    {{ __('Tugas') }}
                                </x-nav-link>

                                {{-- 🔽 Dropdown tambahan seperti admin --}}
                                <div class="flex items-center relative">
                                    <!-- Tombol Icon -->
                                    <button @click="open = !open" class="p-2 text-gray-700 hover:text-gray-900">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div 
                                        x-show="open" 
                                        @click.away="open = false"
                                        class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                                    >
                                        <a href="{{ route('pegawai.meeting.index') }}"
                                            class="block px-4 py-2 text-sm {{ request()->routeIs('pegawai.meeting.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                            Meeting
                                        </a>

                                        <a href="{{ route('pegawai.cuti.index') }}"
                                            class="block px-4 py-2 text-sm {{ request()->routeIs('pegawai.cuti.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                            Pengajuan Cuti
                                        </a>

                                        <a href="{{ route('pegawai.pengumuman.index') }}"
                                            class="block px-4 py-2 text-sm {{ request()->routeIs('pegawai.pengumuman.*') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                            Pengumuman
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                            <!-- Ikon User -->
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>

                            <!-- Info User -->
                            <div class="flex flex-col items-start leading-tight text-left">
                                <span class="font-semibold text-gray-800">{{ Auth::user()->username ?? 'Guest' }}</span>
                                <span class="text-xs text-gray-500 capitalize">{{ Auth::user()->role ?? 'Role' }}</span>
                            </div>

                            <!-- Panah Dropdown -->
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- <x-dropdown-link 
                            :href="route('profile.edit')" 
                            class="{{ request()->routeIs('profile.edit') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                            {{ __('Profile') }}
                        </x-dropdown-link> --}}

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link 
                                :href="route('logout')" 
                                onclick="event.preventDefault(); this.closest('form').submit();" 
                                class="{{ request()->routeIs('logout') ? 'bg-gray-300 border-l-4 border-gray-900 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-4 border-transparent' }}">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
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
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name ?? '' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email ?? '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
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
            </div>
        </div>
    </div>
</nav>

