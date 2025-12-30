@extends('layouts.dashboard')
@section('title', 'Edit Data Visi Sekolah - SMAN 6 Tangerang')
@section('meta-description', 'Edit data visi sekolah SMAN 6 Tangerang')
@section('meta-keywords', 'master data, edit visi sekolah, edit visi, visi, vision, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Edit Visi Sekolah</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Ubah data visi sekolah: {{ $vision->id ?? 'N/A' }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.admin.master-data.visions.index') }}"
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
                    <form action="{{ route('dashboard.admin.master-data.visions.update', $vision->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-floating mb-3">
                            <input type="text" name="content" class="form-control @error('content') is-invalid @enderror"
                                id="floatingInputContent" placeholder="Konten Visi" value="{{ old('content', $vision->content) }}"
                                required>
                            <label for="floatingInputContent">Konten Visi</label>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input @error('is_active') is-invalid @enderror"
                                type="checkbox"
                                name="is_active"
                                id="isActive"
                                value="1"
                                {{ old('is_active', $vision->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="isActive">
                                Jadikan visi ini sebagai visi aktif
                            </label>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="ti ti-device-floppy me-1"></i> Perbarui Visi
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
                    <a href="{{ route('dashboard.admin.master-data.visions.show', $vision->id) }}"
                        class="btn btn-warning w-100 mb-2">
                        <i class="ti ti-eye me-1"></i> Lihat Detail
                    </a>
                    <hr class="my-4">
                    <h4 class="card-title fw-semibold mb-3">Petunjuk Edit</h4>
                    <ul class="text-muted small ps-3">
                        <li>Konten visi wajib diisi dan mencerminkan arah serta tujuan sekolah.</li>
                        <li>Hanya satu visi yang dapat dijadikan visi aktif.</li>
                        <li>Mengaktifkan visi ini akan menonaktifkan visi aktif sebelumnya.</li>
                        <li>Perubahan akan langsung memengaruhi tampilan visi di halaman publik.</li>
                        <li>Pastikan konten sudah benar sebelum menyimpan perubahan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
