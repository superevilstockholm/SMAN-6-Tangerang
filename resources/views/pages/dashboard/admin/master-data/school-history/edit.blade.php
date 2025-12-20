@extends('layouts.dashboard')
@section('title', 'Edit Data Sejarah Sekolah - SMAN 6 Tangerang')
@section('meta-description', 'Edit data sejarah sekolah SMAN 6 Tangerang')
@section('meta-keywords', 'master data, edit sejarah sekolah, edit sejarah, sejarah, history, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Edit Sejarah Sekolah</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Ubah data sejarah sekolah: {{ $school_history->title ?? 'N/A' }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.admin.master-data.school-histories.index') }}"
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
                    <form action="{{ route('dashboard.admin.master-data.school-histories.update', $school_history->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <p class="form-label">Gambar Pendukung Aktif</p>
                            <img class="w-100 object-fit-cover rounded" style="height: 250px; object-position: 50% 25%;" src="{{ $school_history->image_path_url }}" alt="{{ $school_history->title ?? '-' }}">
                            @if($school_history->image_path)
                                <div class="form-check mt-3 d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="delete_image"
                                        id="deleteImage" value="1">
                                    <label class="form-check-label text-danger p-0 m-0" for="deleteImage">
                                        <i class="ti ti-trash me-1 py-0 my-0 text-danger"></i> Hapus foto profil
                                    </label>
                                </div>
                            @endif
                        </div>
                        <div class="mb-4">
                            <label for="image" class="form-label">Gambar Pendukung Baru (Opsional)</label>
                            <input type="file" name="image" class="form-control form-control-sm @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Upload gambar baru jika ingin mengganti gambar lama. Format harus JPG, JPEG, PNG, atau WEBP. Maks 2MB.
                            </small>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                id="floatingInputTitle" placeholder="Judul" value="{{ old('title', $school_history->title) }}"
                                required>
                            <label for="floatingInputTitle">Judul</label>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                                id="floatingInputDescription" placeholder="Deskripsi" value="{{ old('description', $school_history->description) }}"
                                required>
                            <label for="floatingInputDescription">Deskripsi</label>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" name="start_year" class="form-control @error('start_year') is-invalid @enderror"
                                id="floatingInputStartYear" placeholder="Tahun Mulai" value="{{ old('start_year', $school_history->start_year) }}" required>
                            <label for="floatingInputStartYear">Tahun Mulai</label>
                            @error('start_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" name="end_year" class="form-control @error('end_year') is-invalid @enderror"
                                id="floatingInputEndYear" placeholder="Tahun Mulai" value="{{ old('end_year', $school_history->end_year) }}">
                            <label for="floatingInputEndYear">Tahun Selesai</label>
                            @error('end_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="ti ti-device-floppy me-1"></i> Perbarui Sejarah
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
                    <a href="{{ route('dashboard.admin.master-data.school-histories.show', $school_history->id) }}"
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
