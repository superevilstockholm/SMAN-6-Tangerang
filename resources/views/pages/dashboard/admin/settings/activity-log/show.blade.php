@extends('layouts.dashboard')
@section('title', 'Detail Data Log Aktivitas - SMAN 6 Tangerang')
@section('meta-description', 'Detail data log aktivitas sekolah SMAN 6 Tangerang')
@section('meta-keywords', 'master data, detail log aktivitas, detail activity log, log aktivitas, activity log, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Detail Log Aktivitas</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Informasi lengkap log aktivitas: {{ $activityLog->id ?? 'N/A' }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.admin.settings.activity-logs.index') }}"
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
                    <h4 class="card-title fw-semibold mb-4">Informasi Log Aktivitas</h4>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Metode HTTP</div>
                        <div class="col-md-8 fw-medium">{{ $activityLog->method ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Path / URL</div>
                        <div class="col-md-8 fw-medium">{{ $activityLog->path ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Nama Route</div>
                        <div class="col-md-8 fw-medium">{{ $activityLog->route_name ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Status Code</div>
                        <div class="col-md-8 fw-medium">{{ $activityLog->status_code ?? '-' }}</div>
                    </div>
                    <h4 class="card-title fw-semibold mt-4 mb-3">Informasi Pengguna</h4>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">User</div>
                        <div class="col-md-8 fw-medium">
                            {{ $activityLog->user?->name ?? 'System / Guest' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">IP Address</div>
                        <div class="col-md-8 fw-medium">{{ $activityLog->ip_address ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">User Agent</div>
                        <div class="col-md-8 fw-medium">
                            <small>{{ $activityLog->user_agent ?? '-' }}</small>
                        </div>
                    </div>
                    <h4 class="card-title fw-semibold mt-4 mb-3">Informasi Sistem</h4>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">ID Visi</div>
                        <div class="col-md-8 fw-medium">{{ $activityLog->id ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Tanggal Dibuat</div>
                        <div class="col-md-8 fw-medium">{{ $activityLog->created_at?->format('d M Y H:i:s') ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Terakhir Diperbarui</div>
                        <div class="col-md-8 fw-medium">{{ $activityLog->updated_at?->format('d M Y H:i:s') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card my-0">
                <div class="card-body">
                    <h4 class="card-title fw-semibold mb-3">Catatan</h4>
                    <p class="text-muted small">
                        Data pada halaman ini bersifat <strong>read-only</strong> dan digunakan sebagai audit trail
                        sistem. Log aktivitas tidak dapat diubah secara manual.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
