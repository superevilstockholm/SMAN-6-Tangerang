@extends('layouts.dashboard')
@section('title', 'Edit Data Pengguna - SMAN 6 Tangerang')
@section('meta-description', 'Edit data pengguna SMAN 6 Tangerang')
@section('meta-keywords', 'master data, edit pengguna, edit user, user, pengguna, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    @php
        use App\Enums\RoleEnum;
    @endphp
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Edit Pengguna</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Ubah data pengguna: {{ $user->name ?? 'N/A' }}</p>
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
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card my-0">
                <div class="card-body">
                    <form action="{{ route('dashboard.admin.master-data.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <p class="form-label">Foto Profil Aktif</p>
                            <img class="object-fit-cover rounded" style="height: 150px; width: 150px;" src="{{ $user->profile_picture_path_url }}" alt="{{ $user->name ?? '-' }}">
                            @if($user->profile_picture_path)
                                <div class="form-check mt-3 d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="delete_profile_picture"
                                        id="deleteProfilePicture" value="1">
                                    <label class="form-check-label text-danger p-0 m-0" for="deleteProfilePicture">
                                        <i class="ti ti-trash me-1 py-0 my-0 text-danger"></i> Hapus foto profil
                                    </label>
                                </div>
                            @endif
                        </div>
                        <div class="mb-4">
                            <label for="profile_picture_image" class="form-label">Foto Profil Baru (Opsional)</label>
                            <input type="file" name="profile_picture_image" class="form-control form-control-sm @error('profile_picture_image') is-invalid @enderror">
                            @error('profile_picture_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Upload gambar baru jika ingin mengganti gambar lama. Format harus JPG, JPEG, PNG, atau WEBP. Maks 2MB.
                            </small>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="floatingInputName" placeholder="Nama Lengkap" value="{{ old('name', $user->name) }}"
                                required>
                            <label for="floatingInputName">Nama Lengkap</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="floatingInputEmail" placeholder="Alamat Email" value="{{ old('email', $user->email) }}"
                                required>
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
                                    <option value="{{ $role->value }}"
                                        {{ old('role', $user->role?->value) === $role->value ? 'selected' : '' }}>
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
                                placeholder="Kata Sandi Baru">
                            <label for="floatingInputPassword">Kata Sandi Baru (Opsional)</label>
                            <small class="form-text text-muted">
                                Isi hanya jika Anda ingin mengubah password. Password minimal 8 karakter dan harus
                                mengandung huruf besar, huruf kecil, angka, dan karakter khusus.
                            </small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="ti ti-device-floppy me-1"></i> Perbarui Pengguna
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
                    <a href="{{ route('dashboard.admin.master-data.users.show', $user->id) }}"
                        class="btn btn-primary w-100 mb-2">
                        <i class="ti ti-eye me-1"></i> Lihat Detail Pengguna
                    </a>
                    @if ($user->role == RoleEnum::TEACHER && !empty($user->teacher))
                        <a href="{{ route('dashboard.admin.master-data.teachers.edit', $user->teacher->id) }}"
                            class="btn btn-warning w-100 mb-2">
                            <i class="ti ti-pencil me-1"></i> Edit Guru
                        </a>
                    @endif
                    <form id="form-delete-{{ $user->id }}" action="{{ route('dashboard.admin.master-data.users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger w-100 btn-delete" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                            <i class="ti ti-trash me-1"></i> Hapus Pengguna
                        </button>
                    </form>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-delete').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');
                    Swal.fire({
                        title: "Hapus Pengguna?",
                        text: "Apakah Anda yakin ingin menghapus \"" + userName + "\"? Aksi ini tidak dapat dibatalkan.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-delete-' + userId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
