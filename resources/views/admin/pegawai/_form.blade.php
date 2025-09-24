@csrf
<div class="space-y-4">
    {{-- Input Username dan Nama Pegawai --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                value="{{ old('username', $pegawai->user->username ?? '') }}" required>
        </div>
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pegawai</label>
            <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('nama', $pegawai->nama ?? '') }}" required>
        </div>
    </div>

    {{-- Input Password --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Kosongkan jika tidak diubah">
            @if ($pegawai->exists)
            <p class="mt-1 text-xs text-gray-500">Isi untuk mengganti password lama.</p>
            @endif
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>
    </div>

    {{-- Input Email dan No HP --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('email', $pegawai->email ?? '') }}" required>
        </div>
        <div>
            <label for="telepon" class="block text-sm font-medium text-gray-700">No HP</label>
            <input type="text" name="telepon" id="telepon" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('telepon', $pegawai->no_hp ?? '') }}" required>
        </div>
    </div>

    {{-- Input Alamat --}}
    <div>
        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
        <textarea name="alamat" id="alamat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('alamat', $pegawai->alamat ?? '') }}</textarea>
    </div>

    {{-- Input Jabatan dan Tim/Divisi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
            <select name="jabatan" id="jabatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                <option value="">-- Pilih Jabatan --</option>
                @foreach($jabatan as $j)
                    <option value="{{ $j->id }}"
                        {{ old('jabatan', $pegawai->jabatan_id ?? '') == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_jabatan }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="tim_id" class="block text-sm font-medium text-gray-700">Tim / Divisi</label>
            <select name="tim_id" id="tim_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">-- Tidak Masuk Tim --</option>
                @foreach($divisis as $divisi)
                    <optgroup label="{{ $divisi->nama_divisi }}">
                        @foreach($divisi->tims as $tim)
                            <option value="{{ $tim->id }}"
                                {{ old('tim_id', $pegawai->tim_id ?? '') == $tim->id ? 'selected' : '' }}>
                                {{ $tim->nama_tim }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Input Tanggal Masuk dan Gaji Pokok --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                value="{{ old('tanggal_masuk', $pegawai->tanggal_masuk ?? '') }}" required>
        </div>
        <div>
            <label for="gaji" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
            <input type="number" name="gaji" id="gaji" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                value="{{ old('gaji', $pegawai->gaji_pokok ?? '') }}" required>
        </div>
    </div>
</div>