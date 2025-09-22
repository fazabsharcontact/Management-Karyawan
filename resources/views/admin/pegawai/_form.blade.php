@csrf
<div class="form-group mb-3">
    <label for="username">Username</label>
    <input type="text" name="username" class="form-control"
        value="{{ old('username', $pegawai->user->username ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label for="password">Password</label>
    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
</div>

<div class="form-group mb-3">
    <label for="password_confirmation">Konfirmasi Password</label>
    <input type="password" name="password_confirmation" class="form-control">
</div>

<div class="form-group mb-3">
    <label for="nama">Nama Pegawai</label>
    <input type="text" name="nama" class="form-control" value="{{ old('nama', $pegawai->nama ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label for="email">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $pegawai->email ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label for="telepon">No HP</label>
    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $pegawai->no_hp ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label for="alamat">Alamat</label>
    <textarea name="alamat" class="form-control" required>{{ old('alamat', $pegawai->alamat ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="jabatan">Jabatan</label>
    <select name="jabatan" class="form-control" required>
        <option value="">-- Pilih Jabatan --</option>
        @foreach($jabatan as $j)
            <option value="{{ $j->id_jabatan }}" 
                {{ old('jabatan', $pegawai->id_jabatan ?? '') == $j->id_jabatan ? 'selected' : '' }}>
                {{ $j->nama_jabatan }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label for="tanggal_masuk">Tanggal Masuk</label>
    <input type="date" name="tanggal_masuk" class="form-control" 
        value="{{ old('tanggal_masuk', $pegawai->tanggal_masuk ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label for="gaji">Gaji</label>
    <input type="number" name="gaji" class="form-control" 
        value="{{ old('gaji', $pegawai->gaji ?? '') }}" required>
</div>