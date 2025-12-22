@extends('layouts.dashboard')
@section('title', 'Tambah Data Visi Sekolah - SMAN 6 Tangerang')
@section('meta-description', 'Tambah data visi sekolah SMAN 6 Tangerang')
@section('meta-keywords', 'master data, tambah visi sekolah, tambah visi, visi, vision, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Tambah Visi Sekolah</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Formulir untuk memasukkan data visi sekolah baru.</p>
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
        <div class="col">
            <div class="card my-0">
                <div class="card-body">
                    <form action="{{ route('dashboard.admin.master-data.visions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="content" class="form-control @error('content') is-invalid @enderror"
                                id="floatingInputContent" placeholder="Konten Visi" value="{{ old('content') }}" required>
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
                                {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="isActive">
                                Jadikan visi ini sebagai visi aktif
                            </label>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Visi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
