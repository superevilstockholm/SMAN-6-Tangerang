@extends('layouts.dashboard')
@section('title', 'Detail Data Berita - SMAN 6 Tangerang')
@section('meta-description', 'Detail data berita SMAN 6 Tangerang')
@section('meta-keywords', 'master data, detail berita sekolah, detail berita, berita, news, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Detail Berita</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Informasi lengkap berita: {{ $news->id ?? 'N/A' }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.' . auth()->user()->role->value . '.master-data.news.index') }}"
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
                    <h4 class="card-title fw-semibold mb-4">Data Berita</h4>
                    <div class="row mb-3">
                        <div class="col-12 text-muted mb-3">Cover Image</div>
                        <div class="col-12">
                            <img src="{{ $news->cover_image_url }}"
                                alt="Cover Berita"
                                class="img-fluid rounded w-100 object-fit-cover"
                                style="max-height: 300px; object-position: center;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Judul Berita</div>
                        <div class="col-md-8 fw-medium">{{ $news->title ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Slug</div>
                        <div class="col-md-8 fw-medium">{{ $news->slug ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-muted">Konten Berita</div>
                        <div class="col-12 fw-medium">
                            {!! $news->content ?? '-' !!}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Status</div>
                        <div class="col-md-8 fw-medium">
                            {{ $news->status?->label() ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Tanggal Publikasi</div>
                        <div class="col-md-8 fw-medium">
                            {{ $news->published_at?->format('d M Y H:i:s') ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Penulis</div>
                        <div class="col-md-8 fw-medium">
                            {{ $news->user?->name ?? '-' }}
                        </div>
                    </div>
                    <h4 class="card-title fw-semibold mt-4 mb-3">Informasi Sistem</h4>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">ID Berita</div>
                        <div class="col-md-8 fw-medium">{{ $news->id ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Tanggal Dibuat</div>
                        <div class="col-md-8 fw-medium">
                            {{ $news->created_at?->format('d M Y H:i:s') ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Terakhir Diperbarui</div>
                        <div class="col-md-8 fw-medium">
                            {{ $news->updated_at?->format('d M Y H:i:s') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card my-0">
                <div class="card-body">
                    <h4 class="card-title fw-semibold mb-3">Aksi Cepat</h4>
                    <a href="{{ route('dashboard.' . auth()->user()->role->value . '.master-data.news.edit', $news->id) }}"
                        class="btn btn-warning w-100 mb-2">
                        <i class="ti ti-pencil me-1"></i> Edit Berita
                    </a>
                    <form id="form-delete-{{ $news->id }}" action="{{ route('dashboard.' . auth()->user()->role->value . '.master-data.news.destroy', $news->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger w-100 btn-delete" data-id="{{ $news->id }}" data-name="{{ $news->title }}">
                            <i class="ti ti-trash me-1"></i> Hapus Berita
                        </button>
                    </form>
                    <hr class="my-4">
                    <h4 class="card-title fw-semibold mb-3">Catatan</h4>
                    <p class="text-muted small">
                        Halaman ini hanya menampilkan detail data yang tersimpan. Untuk mengubah, klik tombol "Edit Berita".
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-delete').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const newsId = this.getAttribute('data-id');
                    const newsContent = this.getAttribute('data-name');
                    Swal.fire({
                        title: "Hapus Data Berita?",
                        text: "Apakah Anda yakin ingin menghapus \"" + newsContent + "\"? Aksi ini tidak dapat dibatalkan.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-delete-' + newsId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
