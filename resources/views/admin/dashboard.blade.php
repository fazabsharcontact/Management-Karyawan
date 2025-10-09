<x-app-layout>
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                Dashboard Admin
            </h2>

            <!-- === GRID DASHBOARD === -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card Total Pegawai -->
                <div class="bg-gray-100 rounded-xl shadow p-6 border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 text-center">Active employee</h3>
                    <p class="mt-2 text-[50px] font-bold text-gray-900 text-center">{{ $totalPegawai }}</p>
                </div>

                <!-- Card Total Gaji -->
                <div class="bg-gray-100 rounded-xl shadow p-6 border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 text-center">Total salary for the final period</h3>
                    <p class="mt-6 text-[30px] font-bold text-gray-900 text-center">
                        Rp {{ number_format($totalGaji, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Card Chart Jabatan -->
                <div class="bg-gray-100 rounded-xl shadow p-6 border border-gray-100 flex flex-col items-center justify-center">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Employees per position</h3>
                    <div class="h-24 w-24 flex items-center justify-center">
                        <canvas id="jabatanChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- === ROW BAWAH: ACTIVITY + ANNOUNCEMENT === -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Aktivitas -->
                <div class="bg-gray-100 rounded-xl shadow p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">New Activity</h3>
                    <ul class="space-y-4">
                        @forelse ($aktivitas as $item)
                            <li class="flex items-start space-x-3 pb-3 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition duration-150 rounded-lg p-2">
                                <!-- Icon user -->
                                <div class="flex-shrink-0 pt-1">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2a4 4 0 100 8 4 4 0 000-8zm-6 14a6 6 0 1112 0H4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Detail aktivitas -->
                                <div>
                                    <p class="text-sm text-gray-900 font-medium leading-snug">{!! $item->keterangan !!}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($item->waktu)->diffForHumans() }}
                                    </p>
                                </div>
                            </li>
                        @empty
                            <li>
                                <p class="text-center text-gray-500 py-4 italic">Tidak ada aktivitas terbaru dalam 7 hari terakhir.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Pengumuman -->
                <div class="bg-gray-100 rounded-xl shadow p-6 border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-700">Announcement</h3>
                        <a href="{{ route('admin.pengumuman.create') }}" class="inline-block bg-gray-900 font-bold hover:bg-gray-800 text-white px-4 py-2 rounded-full shadow text-sm">
                            Add New Announcement
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($pengumumans as $pengumuman)
                        <div class="border rounded-lg p-4 bg-gray-200">
                            <div class="flex justify-between items-start">
                                <div clas> 
                                    <h4 class="font-bold text-lg text-gray-800">{{ $pengumuman->judul }}</h4>
                                    <p class="text-xs text-gray-500">
                                        Dipublikasikan oleh {{ $pengumuman->pembuat->username ?? 'N/A' }} pada {{ $pengumuman->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">&times; Hapus</button>
                                </form>
                            </div>
                            <div class="prose max-w-none mt-2 text-gray-700">
                                {!! $pengumuman->isi !!}
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>Belum ada pengumuman yang dibuat.</p>
                        </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $pengumumans->links('vendor.pagination.tailwind') }}
                    </div>
                </div>

                <!-- CARD MEETING -->
                <div class="bg-gray-100 rounded-xl shadow p-6 border border-gray-100 w-full col-span-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-700">Meeting Schedule</h3>
                        <a href="{{ route('admin.meeting.create') }}" 
                        class="inline-block bg-gray-900 font-bold hover:bg-gray-800 text-white px-4 py-2 rounded-full shadow text-sm">
                            Add New Meeting
                        </a>
                    </div>

                    <!-- Notifikasi -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Tabel -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="border-b px-4 py-2 text-left">Judul Meeting</th>
                                    <th class="border-b px-4 py-2 text-left">Jadwal</th>
                                    <th class="border-b px-4 py-2 text-left">Lokasi</th>
                                    <th class="border-b px-4 py-2 text-left">Dibuat Oleh</th>
                                    <th class="border-b px-4 py-2 text-center">Jumlah Peserta</th>
                                    <th class="border-b px-4 py-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meetings as $meeting)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border-b px-4 py-2 font-medium">{{ $meeting->judul }}</td>
                                        <td class="border-b px-4 py-2 text-sm">
                                            {{ \Carbon\Carbon::parse($meeting->waktu_mulai)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="border-b px-4 py-2">{{ $meeting->lokasi }}</td>
                                        <td class="border-b px-4 py-2">{{ $meeting->pembuat->nama ?? 'N/A' }}</td>
                                        <td class="border-b px-4 py-2 text-center">{{ $meeting->pesertas_count }}</td>
                                        <td class="border-b px-4 py-2 text-center" style="width: 150px;">
                                            <a href="{{ route('admin.meeting.edit', $meeting->id) }}" 
                                            class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.meeting.destroy', $meeting->id) }}" 
                                                method="POST" 
                                                class="inline" 
                                                onsubmit="return confirm('Hapus meeting ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-gray-500">
                                            Belum ada meeting yang dijadwalkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $meetings->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let jabatanLabels = {!! json_encode(array_keys($jabatanData)) !!};
            let jabatanValues = {!! json_encode(array_values($jabatanData)) !!};

            if (jabatanLabels.length > 0) {
                new Chart(document.getElementById("jabatanChart"), {
                    type: "pie",
                    data: {
                        labels: jabatanLabels,
                        datasets: [{
                            data: jabatanValues,
                            backgroundColor: [
                                "#9CA3AF", 
                                "#6B7280", 
                                "#111827" 
                            ],
                            borderWidth: 2,
                            borderColor: "#fff"
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>