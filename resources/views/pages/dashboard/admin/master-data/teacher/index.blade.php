@extends('layouts.dashboard')
@section('title', 'Data Guru - SMAN 6 Tangerang')
@section('meta-description', 'Daftar data guru SMAN 6 Tangerang')
@section('meta-keywords', 'master data, data guru, data teachers, guru, teachers, sman 6, sman 6 tangerang')
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
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Data Guru</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Manajemen data guru.</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.admin.master-data.teachers.create') }}"
                            class="btn btn-sm btn-primary px-4 rounded-pill m-0">
                            <i class="ti ti-plus me-1"></i> Tambah Guru
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
                    <form method="GET" action="{{ route('dashboard.admin.master-data.teachers.index') }}" id="filterForm">
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
                                @if ($teachers instanceof LengthAwarePaginator)
                                    Menampilkan {{ $teachers->firstItem() }} hingga {{ $teachers->lastItem() }} dari
                                    {{ $teachers->total() }} total entri
                                @else
                                    Menampilkan {{ $teachers->count() }} total entri
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-md-row align-items-md-stretch mb-3 gap-2">
                            {{-- Select Filter Type --}}
                            <div class="form-floating" style="min-width: 180px;">
                                @php
                                    $filterTypes = [
                                        'name' => 'Nama',
                                        'email' => 'Email',
                                        'nip' => 'NIP',
                                        'dob' => 'Tanggal Lahir',
                                        'date' => 'Tanggal Dibuat',
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
                            {{-- Input Text for Name/Email/NIP --}}
                            <div class="form-floating flex-grow-1" id="filterTextWrapper">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    id="filterTextInput" placeholder="Masukan kata kunci" value="{{ request('search') }}">
                                <label for="filterTextInput">Masukan kata kunci</label>
                            </div>
                            {{-- Date Fields for DOB/Date --}}
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
                            <a href="{{ route('dashboard.admin.master-data.teachers.index') }}"
                                class="btn btn-secondary d-flex align-items-center justify-content-center">
                                <i class="ti ti-rotate-clockwise-2"></i> Reset
                            </a>
                        </div>
                    </form>
                    <div class="table-responsive @if (!($teachers instanceof LengthAwarePaginator && $teachers->hasPages())) mb-0 @else mb-3 @endif">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>ID User</th>
                                    <th>Dibuat Pada</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($teachers as $index => $teacher)
                                    <tr>
                                        <td class="text-center">
                                            @if ($teachers instanceof LengthAwarePaginator)
                                                {{ $teachers->firstItem() + $loop->index }}
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </td>
                                        <td>{{ $teacher->name ? ucwords(strtolower($teacher->name)) : '-' }}</td>
                                        <td>{{ $teacher->nip ?? '-' }}</td>
                                        <td>
                                            @if ($teacher->user)
                                                <a href="{{ route('dashboard.admin.master-data.users.show', $teacher->user->id) }}">
                                                    {{ $teacher->user->id }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $teacher->created_at?->format('d M Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn border-0 p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                        href="{{ route('dashboard.admin.master-data.teachers.show', $teacher->id) }}">
                                                        <i class="ti ti-eye me-1"></i> Lihat
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('dashboard.admin.master-data.teachers.edit', $teacher->id) }}">
                                                        <i class="ti ti-pencil me-1"></i> Edit
                                                    </a>
                                                    <form id="form-delete-{{ $teacher->id }}"
                                                        action="{{ route('dashboard.admin.master-data.teachers.destroy', $teacher->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item text-danger btn-delete"
                                                            data-id="{{ $teacher->id }}" data-name="{{ $teacher->name }}">
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
                                                Tidak ada data guru yang ditemukan dengan kriteria tersebut.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($teachers instanceof LengthAwarePaginator && $teachers->hasPages())
                        <div class="overflow-x-auto mt-0 py-1">
                            <div class="d-flex justify-content-center d-md-block w-100 px-3">
                                {{ $teachers->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const teacherId = this.getAttribute('data-id');
                    const teacherName = this.getAttribute('data-name');
                    Swal.fire({
                        title: "Hapus Guru?",
                        text: "Apakah Anda yakin ingin menghapus guru \"" + teacherName + "\"? Aksi ini tidak dapat dibatalkan.",
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
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('filterType');
            const textInputWrapper = document.getElementById('filterTextWrapper');
            const startDateWrapper = document.getElementById('filterStartDateWrapper');
            const endDateWrapper = document.getElementById('filterEndDateWrapper');
            function updateFilterFields() {
                const value = typeSelect.value;
                textInputWrapper.classList.add('d-none');
                startDateWrapper.classList.add('d-none');
                endDateWrapper.classList.add('d-none');
                if (value === 'date' || value === 'dob') {
                    startDateWrapper.classList.remove('d-none');
                    endDateWrapper.classList.remove('d-none');
                } else {
                    textInputWrapper.classList.remove('d-none');
                }
            }
            updateFilterFields();
            typeSelect.addEventListener('change', updateFilterFields);
        });
    </script>
@endsection
