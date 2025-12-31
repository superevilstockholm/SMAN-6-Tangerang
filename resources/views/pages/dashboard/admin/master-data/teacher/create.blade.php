@extends('layouts.dashboard')
@section('title', 'Tambah Data Guru - SMAN 6 Tangerang')
@section('meta-description', 'Tambah data guru SMAN 6 Tangerang')
@section('meta-keywords', 'master data, tambah guru, tambah teachers, guru, teachers, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Tambah Guru</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Formulir untuk memasukkan data guru baru.</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.admin.master-data.teachers.index') }}"
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
                    <form action="{{ route('dashboard.admin.master-data.teachers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="floatingInputName" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            <label for="floatingInputName">Nama Lengkap</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                                id="floatingInputNIP" placeholder="NIP" value="{{ old('nip') }}" minlength="18" maxlength="18" required>
                            <label for="floatingInputNIP">NIP</label>
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror"
                                id="floatingInputDOB" placeholder="DOB" value="{{ old('dob') }}" required>
                            <label for="floatingInputDOB">Tanggal Lahir</label>
                            @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input type="hidden" name="with_user" value="0">
                            <input class="form-check-input @error('with_user') is-invalid @enderror"
                                type="checkbox"
                                name="with_user"
                                id="withUser"
                                value="1"
                                {{ old('with_user') ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="withUser">
                                Buat User Untuk Guru Ini
                            </label>
                            @error('with_user')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="userFields" style="display: {{ old('with_user') ? 'block' : 'none' }};">
                            {{-- Foto Profil --}}
                            <div class="mb-4">
                                <label for="profile_picture_image" class="form-label">
                                    Foto Profil (Opsional)
                                </label>
                                <input type="file"
                                    name="profile_picture_image"
                                    id="profile_picture_image"
                                    class="form-control form-control-sm @error('profile_picture_image') is-invalid @enderror">
                                @error('profile_picture_image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Email --}}
                            <div class="form-floating mb-3">
                                <input type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="floatingInputEmail"
                                    placeholder="Alamat Email"
                                    value="{{ old('email') }}">
                                <label for="floatingInputEmail">Alamat Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Password --}}
                            <div class="form-floating mb-4">
                                <input type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="floatingInputPassword"
                                    placeholder="Kata Sandi">
                                <label for="floatingInputPassword">Kata Sandi</label>
                                <p class="text-muted ms-1 mt-2">
                                    Password minimal 8 karakter dan harus mengandung huruf besar,
                                    huruf kecil, angka, dan karakter khusus.
                                </p>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Guru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const withUserCheckbox = document.getElementById('withUser');
            const userFields = document.getElementById('userFields');
            function toggleUserFields() {
                userFields.style.display = withUserCheckbox.checked ? 'block' : 'none';
            }
            toggleUserFields();
            withUserCheckbox.addEventListener('change', toggleUserFields);
        });
    </script>
@endsection
