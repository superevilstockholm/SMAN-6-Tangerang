@extends('layouts.dashboard')
@section('title', 'Edit Data Guru - SMAN 6 Tangerang')
@section('meta-description', 'Edit data guru SMAN 6 Tangerang')
@section('meta-keywords', 'master data, edit guru, edit teacher, guru, teacher, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Edit Guru</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Ubah data guru: {{ $teacher->name ?? 'N/A' }}</p>
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
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card my-0">
                <div class="card-body">
                    <form action="{{ route('dashboard.admin.master-data.teachers.update', $teacher->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="floatingInputName" placeholder="Nama Lengkap" value="{{ old('name', $teacher->name) }}"
                                required>
                            <label for="floatingInputName">Nama Lengkap</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                                id="floatingInputNIP" placeholder="NIP" value="{{ old('nip', $teacher->nip) }}"
                                minlength="18" maxlength="18" required>
                            <label for="floatingInputNIP">NIP</label>
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror"
                                id="floatingInputDOB" placeholder="DOB"
                                value="{{ old('dob', $teacher->dob->format('Y-m-d')) }}" required>
                            <label for="floatingInputDOB">Tanggal Lahir</label>
                            @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if (empty($teacher->user))
                            <div class="form-check mb-3">
                                <input type="hidden" name="with_user" value="0">
                                <input class="form-check-input @error('with_user') is-invalid @enderror" type="checkbox"
                                    name="with_user" id="withUser" value="1" {{ old('with_user') ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="withUser">
                                    Buat User Untuk Guru Ini
                                </label>
                                @error('with_user')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="userFields" style="display: {{ old('with_user', $teacher->user) ? 'block' : 'none' }};">
                                {{-- Foto Profil --}}
                                <div class="mb-4">
                                    <label for="profile_picture_image" class="form-label">
                                        Foto Profil (Opsional)
                                    </label>
                                    <input type="file" name="profile_picture_image" id="profile_picture_image"
                                        class="form-control form-control-sm @error('profile_picture_image') is-invalid @enderror">
                                    @error('profile_picture_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- Email --}}
                                <div class="form-floating mb-3">
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror" id="floatingInputEmail"
                                        placeholder="Alamat Email" value="{{ old('email') }}">
                                    <label for="floatingInputEmail">Alamat Email</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- Password --}}
                                <div class="form-floating mb-4">
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" id="floatingInputPassword"
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
                        @endif
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="ti ti-device-floppy me-1"></i> Perbarui Guru
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
                    <a href="{{ route('dashboard.admin.master-data.teachers.show', $teacher->id) }}"
                        class="btn btn-primary w-100 mb-2">
                        <i class="ti ti-eye me-1"></i> Lihat Detail Guru
                    </a>
                    @if (!empty($teacher->user))
                        <a href="{{ route('dashboard.admin.master-data.users.edit', $teacher->user->id) }}"
                            class="btn btn-warning w-100 mb-2">
                            <i class="ti ti-pencil me-1"></i> Edit Pengguna
                        </a>
                    @endif
                    <form id="form-delete-{{ $teacher->id }}" action="{{ route('dashboard.admin.master-data.teachers.destroy', $teacher->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger w-100 btn-delete" data-id="{{ $teacher->id }}" data-name="{{ $teacher->name }}">
                            <i class="ti ti-trash me-1"></i> Hapus Guru
                        </button>
                    </form>
                    <hr class="my-4">
                    <h4 class="card-title fw-semibold mb-3">Petunjuk Edit</h4>
                    <ul class="text-muted small ps-3">
                        <li class="mb-2"><strong>Nama, NIP, & DOB:</strong> Wajib diisi.</li>
                        @if ($teacher->user)
                            <li class="mb-2">Mengubah nama guru di sini akan <strong>otomatis mengupdate nama</strong> pada akun user-nya.</li>
                            <li class="mb-2">Email, password, dan foto profil hanya dapat diubah melalui menu "User".</li>
                        @else
                            <li class="mb-2">Anda dapat mencentang "Buat User" untuk memberikan hak akses login ke guru ini.</li>
                            <li class="mb-2">Email harus unik dan belum pernah terdaftar.</li>
                            <li class="mb-2">Password harus mengandung kombinasi huruf besar, kecil, angka, dan simbol.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-delete').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const teacherId = this.getAttribute('data-id');
                    const teacherName = this.getAttribute('data-name');
                    Swal.fire({
                        title: "Hapus Guru?",
                        text: "Apakah Anda yakin ingin menghapus \"" + teacherName + "\"? Aksi ini tidak dapat dibatalkan.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-delete-' + teacherId).submit();
                        }
                    });
                });
            });
        });
    </script>
    @if (empty($teacher->user))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const withUserCheckbox = document.getElementById('withUser');
                const userFields = document.getElementById('userFields');
                if (withUserCheckbox && userFields) {
                    function toggleUserFields() {
                        userFields.style.display = withUserCheckbox.checked ? 'block' : 'none';
                    }
                    toggleUserFields();
                    withUserCheckbox.addEventListener('change', toggleUserFields);
                }
            });
        </script>
    @endif
@endsection
