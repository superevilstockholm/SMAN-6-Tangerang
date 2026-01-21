@extends('layouts.dashboard')
@section('title', 'Tambah Data Berita Sekolah - SMAN 6 Tangerang')
@section('meta-description', 'Tambah data berita sekolah SMAN 6 Tangerang')
@section('meta-keywords', 'master data, tambah berita sekolah, tambah berita, berita, news, sman 6, sman 6 tangerang')
@section('content')
    <x-alerts :errors="$errors" />
    @php
        use App\Enums\NewsStatusEnum;
    @endphp
    <div class="row mb-4">
        <div class="col">
            <div class="card my-0">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 gap-lg-5">
                    <div class="d-flex flex-column">
                        <h3 class="p-0 m-0 mb-1 fw-semibold">Tambah Berita</h3>
                        <p class="p-0 m-0 fw-medium text-muted">Formulir untuk memasukkan data berita baru.</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.admin.master-data.news.index') }}"
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
                    <form action="{{ route('dashboard.' . auth()->user()->role->value . '.master-data.news.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Judul --}}
                        <div class="form-floating mb-3">
                            <input type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="floatingTitle" placeholder="Judul Berita"
                                   value="{{ old('title') }}" required>
                            <label for="floatingTitle">Judul Berita</label>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Slug --}}
                        <div class="form-floating mb-3">
                            <input type="text" name="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   id="floatingSlug" placeholder="Slug Berita"
                                   value="{{ old('slug') }}">
                            <label for="floatingSlug">Slug (Opsional)</label>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Cover Image --}}
                        <div class="mb-4">
                            <label for="cover_image" class="form-label">Cover Image (Opsional)</label>
                            <input type="file" name="cover_image"
                                   class="form-control form-control-sm @error('cover_image') is-invalid @enderror"
                                   accept="image/*">
                            @error('cover_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Konten --}}
                        <div class="mb-4">
                            <label for="content" class="form-label">Konten Berita</label>
                            <textarea name="content" id="editor"
                                      class="form-control @error('content') is-invalid @enderror"
                                      rows="6" placeholder="Tulis isi berita...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Status --}}
                        <div class="form-floating mb-3">
                            <select name="status" id="statusSelect"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                <option value="" disabled {{ old('status') ? '' : 'selected' }}>Pilih Status</option>
                                @foreach (NewsStatusEnum::cases() as $status)
                                    <option value="{{ $status->value }}"
                                        {{ old('status') === $status->value ? 'selected' : '' }}>
                                        {{ ucwords(strtolower($status->name)) }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="statusSelect">Status Publikasi</label>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Tanggal Publikasi --}}
                        <div id="publishedAtWrapper" class="form-floating mb-4 d-none">
                            <input type="datetime-local" name="published_at"
                                   class="form-control @error('published_at') is-invalid @enderror"
                                   id="publishedAtInput"
                                   value="{{ old('published_at') }}">
                            <label for="publishedAtInput">Tanggal Publikasi</label>
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Submit --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Berita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
        const statusSelect = document.getElementById('statusSelect');
        const publishedWrapper = document.getElementById('publishedAtWrapper');
        const publishedInput = document.getElementById('publishedAtInput');
        function togglePublishedAt() {
            if (statusSelect.value === 'SCHEDULED') {
                publishedWrapper.classList.remove('d-none');
                publishedInput.required = true;
            } else {
                publishedWrapper.classList.add('d-none');
                publishedInput.required = false;
                publishedInput.value = '';
            }
        }
        togglePublishedAt();
        statusSelect.addEventListener('change', togglePublishedAt);
    </script>
@endsection
