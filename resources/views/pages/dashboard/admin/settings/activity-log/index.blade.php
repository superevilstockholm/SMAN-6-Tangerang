@extends('layouts.dashboard')
@section('title', 'Activity Logs - SMAN 6 Tangerang')
@section('meta-description', 'Daftar activity logs sistem SMAN 6 Tangerang')
@section('meta-keywords', 'activity logs, log aktivitas, audit trail, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    @php
        use Illuminate\Contracts\Pagination\LengthAwarePaginator;
    @endphp
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Activity Logs</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Log aktivitas sistem dan pengguna.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card my-0">
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard.admin.settings.activity-logs.index') }}"
                        id="filterForm">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center mb-3 gap-2 gap-md-0">
                            <div class="d-flex align-items-center">
                                @php
                                    $limits = [5, 10, 25, 50, 'all'];
                                    $currentLimit = request('limit', 10);
                                @endphp
                                <label for="limitSelect" class="form-label mb-0 me-2">Limit</label>
                                <select class="form-select form-select-sm" id="limitSelect" name="limit"
                                    onchange="document.getElementById('filterForm').submit()">
                                    @foreach ($limits as $limit)
                                        <option value="{{ $limit }}"
                                            {{ (string) $currentLimit === (string) $limit ? 'selected' : '' }}>
                                            {{ $limit === 'all' ? 'All' : $limit }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="ms-2">entries</span>
                            </div>
                            <div class="text-muted small">
                                @if ($activityLogs instanceof LengthAwarePaginator)
                                    Menampilkan {{ $activityLogs->firstItem() }} hingga {{ $activityLogs->lastItem() }} dari
                                    {{ $activityLogs->total() }} total entri
                                @else
                                    Menampilkan {{ $activityLogs->count() }} total entri
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-md-row align-items-md-stretch mb-3 gap-2">
                            {{-- Select Filter Type --}}
                            <div class="form-floating" style="min-width: 180px;">
                                @php
                                    $filterTypes = [
                                        'method' => 'Method',
                                        'path' => 'Path',
                                        'user_name' => 'User Name',
                                        'ip_address' => 'IP Address',
                                        'user_agent' => 'User Agent',
                                        'status_code' => 'Status Code',
                                        'date' => 'Tanggal',
                                    ];
                                    $currentType = request('type') ?: 'path';
                                @endphp
                                <select class="form-select form-select-sm" id="filterType" name="type">
                                    @foreach ($filterTypes as $key => $label)
                                        <option value="{{ $key }}" {{ $currentType === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="filterType">Filter berdasarkan</label>
                            </div>
                            {{-- Input Text for most filters --}}
                            <div class="form-floating flex-grow-1" id="filterTextWrapper">
                                <input type="text" class="form-control form-control-sm" id="filterTextInput"
                                    placeholder="Masukan kata kunci" value="{{ request('search') }}">
                                <label for="filterTextInput">Masukan kata kunci</label>
                            </div>
                            {{-- Method Select --}}
                            <div class="form-floating flex-grow-1 d-none" id="filterMethodWrapper">
                                <select class="form-select form-select-sm" id="filterMethodSelect">
                                    <option value="">-- Pilih Method --</option>
                                    <option value="POST" {{ request('search') === 'POST' ? 'selected' : '' }}>POST
                                    </option>
                                    <option value="PUT" {{ request('search') === 'PUT' ? 'selected' : '' }}>PUT</option>
                                    <option value="PATCH" {{ request('search') === 'PATCH' ? 'selected' : '' }}>PATCH
                                    </option>
                                    <option value="DELETE" {{ request('search') === 'DELETE' ? 'selected' : '' }}>DELETE
                                    </option>
                                </select>
                                <label for="filterMethodSelect">Pilih Method</label>
                            </div>
                            {{-- Date Fields --}}
                            <div class="form-floating flex-grow-1 d-none" id="filterStartDateWrapper">
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                    id="filterStartDate" value="{{ request('start_date') }}">
                                <label for="filterStartDate">Tanggal Mulai</label>
                            </div>
                            <div class="form-floating flex-grow-1 d-none" id="filterEndDateWrapper">
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                    id="filterEndDate" value="{{ request('end_date') }}">
                                <label for="filterEndDate">Tanggal Akhir</label>
                            </div>
                            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                <i class="ti ti-search"></i> Cari
                            </button>
                            <a href="{{ route('dashboard.admin.settings.activity-logs.index') }}"
                                class="btn btn-secondary d-flex align-items-center justify-content-center">
                                <i class="ti ti-rotate-clockwise-2"></i> Reset
                            </a>
                        </div>
                    </form>
                    <div class="table-responsive @if (!($activityLogs instanceof LengthAwarePaginator && $activityLogs->hasPages())) mb-0 @else mb-3 @endif">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Path</th>
                                    <th>Method</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activityLogs as $index => $log)
                                    <tr>
                                        <td class="text-center">
                                            @if ($activityLogs instanceof LengthAwarePaginator)
                                                {{ $activityLogs->firstItem() + $loop->index }}
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </td>
                                        <td>{{ $log->path ?? '-' }}</td>
                                        <td>
                                            @php
                                                $methodColors = [
                                                    'GET' => 'success',
                                                    'POST' => 'primary',
                                                    'PUT' => 'warning',
                                                    'PATCH' => 'info',
                                                    'DELETE' => 'danger',
                                                ];
                                                $color = $methodColors[$log->method] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ $log->method }}</span>
                                        </td>
                                        <td>{{ $log->ip_address ?? '-' }}</td>
                                        <td class="text-truncate" style="max-width: 200px;">
                                            {{ $log->user_agent ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn border-0 p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                        href="{{ route('dashboard.admin.settings.activity-logs.show', $log->id) }}">
                                                        <i class="ti ti-eye me-1"></i> Lihat
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-warning my-2" role="alert">
                                                Tidak ada activity log yang ditemukan dengan kriteria tersebut.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($activityLogs instanceof LengthAwarePaginator && $activityLogs->hasPages())
                        <div class="overflow-x-auto mt-0 py-1">
                            <div class="d-flex justify-content-center d-md-block w-100 px-3">
                                {{ $activityLogs->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('filterType');
            const textInput = document.getElementById('filterTextInput');
            const methodSelect = document.getElementById('filterMethodSelect');
            const textWrapper = document.getElementById('filterTextWrapper');
            const methodWrapper = document.getElementById('filterMethodWrapper');
            const startDateWrapper = document.getElementById('filterStartDateWrapper');
            const endDateWrapper = document.getElementById('filterEndDateWrapper');
            function resetNames() {
                textInput.removeAttribute('name');
                methodSelect.removeAttribute('name');
            }
            function updateFilterFields() {
                const value = typeSelect.value;
                textWrapper.classList.add('d-none');
                methodWrapper.classList.add('d-none');
                startDateWrapper.classList.add('d-none');
                endDateWrapper.classList.add('d-none');
                resetNames();
                if (value === 'date') {
                    startDateWrapper.classList.remove('d-none');
                    endDateWrapper.classList.remove('d-none');
                } else if (value === 'method') {
                    methodWrapper.classList.remove('d-none');
                    methodSelect.setAttribute('name', 'search');
                } else {
                    textWrapper.classList.remove('d-none');
                    textInput.setAttribute('name', 'search');
                }
            }
            updateFilterFields();
            typeSelect.addEventListener('change', updateFilterFields);
        });
    </script>
@endsection
