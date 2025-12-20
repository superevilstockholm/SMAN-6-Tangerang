@extends('layouts.dashboard')
@section('title', 'Detail Data Sejarah Sekolah - SMAN 6 Tangerang')
@section('meta-description', 'Detail data sejarah sekolah SMAN 6 Tangerang')
@section('meta-keywords', 'master data, detail sejarah sekolah, detail sejarah, sejarah, history, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Detail Sejarah Sekolah</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Informasi lengkap sejarah sekolah: {{ $school_history->name ?? 'N/A' }}</p>
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
                    <h4 class="card-title fw-semibold mb-4">Data Sejarah Sekolah</h4>
                    <div class="row mb-4">
                        <div class="col">
                            <img class="w-100 object-fit-cover rounded" style="height: 250px; object-position: 50% 25%;" src="{{ $school_history->image_path_url }}" alt="{{ $school_history->title ?? '-' }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Judul</div>
                        <div class="col-md-8 fw-medium">{{ $school_history->title ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Deskripsi</div>
                        <div class="col-md-8 fw-medium">{{ $school_history->description ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Tahun Mulai</div>
                        <div class="col-md-8 fw-medium">{{ $school_history->start_year ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Tahun Selesai</div>
                        <div class="col-md-8 fw-medium">{{ $school_history->end_year ?? '-' }}</div>
                    </div>
                    <h4 class="card-title fw-semibold mt-4 mb-3">Informasi Sistem</h4>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">ID Sejarah Sekola</div>
                        <div class="col-md-8 fw-medium">{{ $school_history->id ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Tanggal Dibuat</div>
                        <div class="col-md-8 fw-medium">{{ $school_history->created_at?->format('d M Y H:i:s') ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Terakhir Diperbarui</div>
                        <div class="col-md-8 fw-medium">{{ $school_history->updated_at?->format('d M Y H:i:s') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card my-0">
                <div class="card-body">
                    <h4 class="card-title fw-semibold mb-3">Aksi Cepat</h4>
                    <a href="{{ route('dashboard.admin.master-data.school-histories.edit', $school_history->id) }}"
                        class="btn btn-warning w-100 mb-2">
                        <i class="ti ti-pencil me-1"></i> Edit Sejarah
                    </a>
                    <form id="form-delete-{{ $school_history->id }}" action="{{ route('dashboard.admin.master-data.school-histories.destroy', $school_history->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger w-100 btn-delete" data-id="{{ $school_history->id }}" data-title="{{ $school_history->title }}">
                            <i class="ti ti-trash me-1"></i> Hapus Sejarah
                        </button>
                    </form>
                    <hr class="my-4">
                    <h4 class="card-title fw-semibold mb-3">Catatan</h4>
                    <p class="text-muted small">
                        Halaman ini hanya menampilkan detail data yang tersimpan. Untuk mengubah, klik tombol "Edit Sejarah".
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-delete').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const historyId = this.getAttribute('data-id');
                    const historyTitle = this.getAttribute('data-title');
                    Swal.fire({
                        title: "Hapus Sejarah?",
                        text: "Apakah Anda yakin ingin menghapus sejarah sekolah \"" + historyTitle + "\"? Aksi ini tidak dapat dibatalkan.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-delete-' + historyId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
