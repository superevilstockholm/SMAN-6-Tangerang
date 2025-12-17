@extends('layouts.dashboard')
@section('title', 'Tambah Data Pengguna - SMAN 6 Tangerang')
@section('meta-description', 'Tambah data pengguna SMAN 6 Tangerang')
@section('meta-keywords', 'master data, tambah pengguna, tambah user, user, pengguna, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    @php
        use App\Enums\RoleEnum;
    @endphp
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Tambah Pengguna</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Formulir untuk memasukkan data pengguna baru.</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.admin.master-data.users.index') }}"
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
                    <form action="{{ route('dashboard.admin.master-data.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="profile_picture_image" class="form-label">Foto Profil (Opsional)</label>
                            <input type="file" name="profile_picture_image" class="form-control form-control-sm @error('profile_picture_image') is-invalid @enderror">
                            @error('profile_picture_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="floatingInputName" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            <label for="floatingInputName">Nama Lengkap</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="floatingInputEmail" placeholder="Alamat Email" value="{{ old('email') }}" required>
                            <label for="floatingInputEmail">Alamat Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <select name="role" id="floatingSelectRole"
                                class="form-select @error('role') is-invalid @enderror"
                                required>
                                <option value="" disabled>Pilih Role</option>
                                @foreach (RoleEnum::cases() as $role)
                                    <option value="{{ $role->value }}">
                                        {{ ucwords($role->value) }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="floatingSelectRole">Role</label>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" id="floatingInputPassword"
                                placeholder="Kata Sandi" required>
                            <label for="floatingInputPassword">Kata Sandi</label>
                            <p class="text-muted ms-1 mt-2">
                                Password minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, angka, dan
                                karakter khusus.
                            </p>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
