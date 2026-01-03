@extends('layouts.dashboard')
@section('title', 'Detail Data Guru - SMAN 6 Tangerang')
@section('meta-description', 'Detail data guru SMAN 6 Tangerang')
@section('meta-keywords', 'master data, detail guru, detail teacher, guru, teacher, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Detail Guru</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Informasi lengkap guru: {{ $teacher->name ?? 'N/A' }}</p>
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
                    <h4 class="card-title fw-semibold mb-4">Informasi Guru</h4>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Nama Lengkap</div>
                        <div class="col-md-8 fw-medium">{{ $teacher->name ? ucwords(strtolower($teacher->name)) : '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">NIP</div>
                        <div class="col-md-8 fw-medium">{{ $teacher->nip ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Tanggal Lahir</div>
                        <div class="col-md-8 fw-medium">{{ $teacher->dob?->format('d M Y H:i:s') ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">ID User</div>
                        <div class="col-md-8 fw-medium">
                            @if ($teacher->user)
                                <a href="{{ route('dashboard.admin.master-data.users.show', $teacher->user->id) }}">
                                    {{ $teacher->user->id ?? '-' }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <h4 class="card-title fw-semibold mt-4 mb-3">Informasi Sistem</h4>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">ID Guru</div>
                        <div class="col-md-8 fw-medium">{{ $teacher->id ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Tanggal Dibuat</div>
                        <div class="col-md-8 fw-medium">{{ $teacher->created_at?->format('d M Y H:i:s') ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Terakhir Diperbarui</div>
                        <div class="col-md-8 fw-medium">{{ $teacher->updated_at?->format('d M Y H:i:s') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card my-0">
                <div class="card-body">
                    <h4 class="card-title fw-semibold mb-3">Aksi Cepat</h4>
                    @if (!empty($teacher->user))
                        <a href="{{ route('dashboard.admin.master-data.users.show', $teacher->user->id) }}"
                            class="btn btn-primary w-100 mb-2">
                            <i class="ti ti-pencil me-1"></i> Lihat Detail Pengguna
                        </a>
                    @endif
                    <a href="{{ route('dashboard.admin.master-data.teachers.edit', $teacher->id) }}"
                        class="btn btn-warning w-100 mb-2">
                        <i class="ti ti-pencil me-1"></i> Edit Guru
                    </a>
                    <form id="form-delete-{{ $teacher->id }}" action="{{ route('dashboard.admin.master-data.teachers.destroy', $teacher->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger w-100 btn-delete" data-id="{{ $teacher->id }}" data-name="{{ $teacher->name }}">
                            <i class="ti ti-trash me-1"></i> Hapus Guru
                        </button>
                    </form>
                    <hr class="my-4">
                    <h4 class="card-title fw-semibold mb-3">Catatan</h4>
                    <p class="text-muted small">
                        Halaman ini hanya menampilkan detail data yang tersimpan. Untuk mengubah, klik tombol "Edit Guru".
                    </p>
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
@endsection
