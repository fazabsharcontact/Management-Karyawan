<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- Navbar -->
    <nav class="bg-white shadow-md px-6 py-3 flex justify-between items-center">
        <div class="text-lg font-bold text-gray-700">
            Employee Management
        </div>
        <div class="flex items-center gap-4">
            <span class="text-gray-600">{{ Auth::user()->name ?? 'Guest' }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
            <h2 class="text-xl font-semibold mb-6">Menu</h2>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="block px-3 py-2 rounded hover:bg-gray-700">
                        📊 Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pegawai.index') }}" 
                       class="block px-3 py-2 rounded hover:bg-gray-700">
                        👔 Manajemen Pegawai
                    </a>
                </li>
                <li>
                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">
                        📑 Manajemen Gaji
                    </a>
                </li>
                <li>
                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">
                        🎖️ Manajemen Jabatan
                    </a>
                </li>
                <li>
                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">
                        🎖️ Manajemen Tunjangan
                    </a>
                </li>
<<<<<<< HEAD
                <!-- <li>
                    <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">
                        🎖️ Manajemen Tim & Divisi
                    </a>
                </li> -->
=======
>>>>>>> origin/backend-pegawai
            </ul>
        </aside>

        <!-- Content -->
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">@yield('title')</h1>
            
            @yield('content')
        </main>
    </div>

</body>
</html>