@extends('layouts.dashboard')
@section('title', 'Edit Data Misi Sekolah - SMAN 6 Tangerang')
@section('meta-description', 'Edit data misi sekolah SMAN 6 Tangerang')
@section('meta-keywords', 'master data, edit misi sekolah, edit misi, misi, mission, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Edit Misi Sekolah</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Ubah data misi sekolah: {{ $mission->id ?? 'N/A' }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.admin.master-data.missions.index') }}"
                            class="btn btn-sm btn-primary px-4 rounded-pill m-0">
                            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card my-0">
                <div class="card-body">
                    <form action="{{ route('dashboard.admin.master-data.missions.update', $mission->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-floating mb-3">
                            <input type="text" name="content" class="form-control @error('content') is-invalid @enderror"
                                id="floatingInputContent" placeholder="Konten Misi" value="{{ old('content', $mission->content) }}"
                                required>
                            <label for="floatingInputContent">Konten Misi</label>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <select name="item_order"
                                class="form-select @error('item_order') is-invalid @enderror"
                                id="floatingSelectOrder"
                                required>
                                @foreach ($orders as $order)
                                    <option value="{{ $order }}"
                                        {{ old('item_order', $mission->item_order) == $order ? 'selected' : '' }}>
                                        Urutan {{ $order }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="floatingSelectOrder">Urutan Misi</label>
                            @error('item_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="ti ti-device-floppy me-1"></i> Perbarui Misi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card my-0">
                <div class="card-body">
                    <h4 class="card-title fw-semibold mb-3">Aksi Cepat</h4>
                    <a href="{{ route('dashboard.admin.master-data.missions.show', $mission->id) }}"
                        class="btn btn-warning w-100 mb-2">
                        <i class="ti ti-eye me-1"></i> Lihat Detail
                    </a>
                    <hr class="my-4">
                    <h4 class="card-title fw-semibold mb-3">Petunjuk Edit</h4>
                    <ul class="text-muted small ps-3">
                        <li>Nama dan Email wajib diisi.</li>
                        <li>Email harus unik (tidak boleh sama dengan email pengguna lain, kecuali email pengguna ini
                            sendiri).</li>
                        <li>Kolom Kata Sandi Baru bersifat opsional. Jika dikosongkan, password lama tidak akan diubah.</li>
                        <li>Validasi password yang ketat tetap berlaku jika kolom password diisi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
