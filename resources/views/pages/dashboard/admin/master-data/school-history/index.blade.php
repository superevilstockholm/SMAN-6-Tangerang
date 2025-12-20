@extends('layouts.dashboard')
@section('title', 'Data Sejarah Sekolah - SMAN 6 Tangerang')
@section('meta-description', 'Daftar data Sejarah Sekolah SMAN 6 Tangerang')
@section('meta-keywords', 'master data, data sejarah sekolah, data school history, history, sekolah, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    @php
        use Illuminate\Contracts\Pagination\LengthAwarePaginator;
        use Illuminate\Support\Str;
    @endphp
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Data Sejarah Sekolah</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Manajemen data sejarah sekolah.</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.admin.master-data.school-histories.create') }}"
                            class="btn btn-sm btn-primary px-4 rounded-pill m-0">
                            <i class="ti ti-plus me-1"></i> Tambah Sejarah
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
                    <form method="GET" action="{{ route('dashboard.admin.master-data.school-histories.index') }}" id="filterForm">
                        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center mb-3 gap-2 gap-md-0">
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
                                @if ($school_histories instanceof LengthAwarePaginator)
                                    Menampilkan {{ $school_histories->firstItem() }} hingga {{ $school_histories->lastItem() }} dari
                                    {{ $school_histories->total() }} total entri
                                @else
                                    Menampilkan {{ $school_histories->count() }} total entri
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-md-row align-items-md-stretch mb-3 gap-2">
                            {{-- Select Filter Type --}}
                            <div class="form-floating" style="min-width: 180px;">
                                @php
                                    $filterTypes = [
                                        'title' => 'Judul',
                                        'description' => 'Deskripsi',
                                        'history_year' => 'Tahun Sejarah',
                                        'created_date' => 'Tanggal Dibuat',
                                    ];
                                    $currentType = request('type') ?: array_key_first($filterTypes);
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
                            {{-- Input Text for Title/Description --}}
                            <div class="form-floating flex-grow-1" id="filterTextWrapper">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    id="filterTextInput" placeholder="Masukan kata kunci" value="{{ request('search') }}">
                                <label for="filterTextInput">Masukan kata kunci</label>
                            </div>
                            {{-- Year Fields for history_year --}}
                            <div class="form-floating flex-grow-1 d-none" id="filterStartYearWrapper">
                                <input type="number" name="start_year" class="form-control form-control-sm"
                                    id="filterStartYear" placeholder="Tahun Mulai"
                                    value="{{ request('start_year') }}" min="1900" max="2100" step="1">
                                <label for="filterStartYear">Tahun Mulai</label>
                            </div>
                            <div class="form-floating flex-grow-1 d-none" id="filterEndYearWrapper">
                                <input type="number" name="end_year" class="form-control form-control-sm"
                                    id="filterEndYear" placeholder="Tahun Akhir"
                                    value="{{ request('end_year') }}" min="1900" max="2100" step="1">
                                <label for="filterEndYear">Tahun Akhir</label>
                            </div>
                            {{-- Date Fields for created_date --}}
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
                            <a href="{{ route('dashboard.admin.master-data.school-histories.index') }}"
                                class="btn btn-secondary d-flex align-items-center justify-content-center">
                                <i class="ti ti-rotate-clockwise-2"></i> Reset
                            </a>
                        </div>
                    </form>
                    <div class="table-responsive @if (!($school_histories instanceof LengthAwarePaginator && $school_histories->hasPages())) mb-0 @else mb-3 @endif">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Tahun</th>
                                    <th>Dibuat Pada</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($school_histories as $index => $school_history)
                                    <tr>
                                        <td class="text-center">
                                            @if ($school_histories instanceof LengthAwarePaginator)
                                                {{ $school_histories->firstItem() + $loop->index }}
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </td>
                                        <td>{{ $school_history->title ?? '-' }}</td>
                                        <td>{{ $school_history->description ? Str::limit($school_history->description, 50, '...') : '-' }}</td>
                                        <td>
                                            @if ($school_history->start_year)
                                                {{ $school_history->start_year }}
                                                @if ($school_history->end_year)
                                                    - {{ $school_history->end_year }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $school_history->created_at?->format('d M Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn border-0 p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                        href="{{ route('dashboard.admin.master-data.school-histories.show', $school_history->id) }}">
                                                        <i class="ti ti-eye me-1"></i> Lihat
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('dashboard.admin.master-data.school-histories.edit', $school_history->id) }}">
                                                        <i class="ti ti-pencil me-1"></i> Edit
                                                    </a>
                                                    <form id="form-delete-{{ $school_history->id }}"
                                                        action="{{ route('dashboard.admin.master-data.school-histories.destroy', $school_history->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item text-danger btn-delete"
                                                            data-id="{{ $school_history->id }}" data-title="{{ $school_history->title }}">
                                                            <i class="ti ti-trash me-1 text-danger"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-warning my-2" role="alert">
                                                Tidak ada data sejarah sekolah yang ditemukan dengan kriteria tersebut.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($school_histories instanceof LengthAwarePaginator && $school_histories->hasPages())
                        <div class="overflow-x-auto mt-0 py-1">
                            <div class="d-flex justify-content-center d-md-block w-100 px-3">
                                {{ $school_histories->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
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
            const textInputWrapper = document.getElementById('filterTextWrapper');
            const startYearWrapper = document.getElementById('filterStartYearWrapper');
            const endYearWrapper = document.getElementById('filterEndYearWrapper');
            const startDateWrapper = document.getElementById('filterStartDateWrapper');
            const endDateWrapper = document.getElementById('filterEndDateWrapper');
            function updateFilterFields() {
                const value = typeSelect.value;
                textInputWrapper.classList.add('d-none');
                startYearWrapper.classList.add('d-none');
                endYearWrapper.classList.add('d-none');
                startDateWrapper.classList.add('d-none');
                endDateWrapper.classList.add('d-none');
                if (value === 'history_year') {
                    startYearWrapper.classList.remove('d-none');
                    endYearWrapper.classList.remove('d-none');
                } else if (value === 'created_date') {
                    startDateWrapper.classList.remove('d-none');
                    endDateWrapper.classList.remove('d-none');
                } else {
                    textInputWrapper.classList.remove('d-none');
                }
            }
            updateFilterFields();
            typeSelect.addEventListener('change', updateFilterFields);
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const historyId = this.getAttribute('data-id');
                    const historyTitle = this.getAttribute('data-title');
                    Swal.fire({
                        title: "Hapus Sejarah Sekolah?",
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
