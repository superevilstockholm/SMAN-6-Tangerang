@extends('App')
@section('layout')
    {{-- Content --}}
    <x-sidebar :meta="$meta"></x-sidebar>
    <x-topbar></x-topbar>
    <div class="pc-container">
        <div class="pc-content">
            <div class="row mb-4">
                <div class="col">
                    @php
                        $segments = collect(request()->segments());
                        $category = $segments->get(1);
                        $item     = $segments->get(2);
                        $subitem  = $segments->get(3);
                        $isDashboardOnly = request()->is('dashboard');
                    @endphp
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item {{ $isDashboardOnly ? 'active' : 'fw-medium' }}"
                                @if($isDashboardOnly) aria-current="page" @endif>
                                @if($isDashboardOnly)
                                    Dashboard
                                @else
                                    <a href="{{ url('dashboard') }}">Dashboard</a>
                                @endif
                            </li>
                            @if($item)
                                @php
                                    $itemLabel = ucwords(str_replace('-', ' ', $item));
                                    $itemUrl   = url("dashboard/$category/$item");
                                @endphp
                                <li class="breadcrumb-item {{ $subitem ? 'fw-medium' : 'active' }}"
                                    @if(!$subitem) aria-current="page" @endif>
                                    @if($subitem)
                                        <a href="{{ $itemUrl }}">{{ $itemLabel }}</a>
                                    @else
                                        {{ $itemLabel }}
                                    @endif
                                </li>
                            @endif
                            @if($subitem)
                                @php
                                    $subLabel = ucwords(str_replace('-', ' ', $subitem));
                                @endphp
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $subLabel }}
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
            @yield('content')
        </div>
    </div>
    <footer class="pc-footer">
        <div class="footer-wrapper container">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <p class="m-0 text-center fw-medium text-muted">
                        Copyright &copy; 2025 <b>AmbaToCode</b>. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
@endsection
@push('css')
<!-- Tabler Icons -->
<link rel="stylesheet" href="{{ asset('static/css/tabler-icons/tabler-icons.min.css') }}">
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="{{ asset('static/css/style.css') }}" id="main-style-link">
<link rel="stylesheet" href="{{ asset('static/css/style-preset.css') }}">
{{-- ====== End Template ====== --}}
{{-- Custom CSS --}}
<link rel="stylesheet" href="{{ asset('static/css/custom.css') }}">
<style>
    input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 0 transparent inset !important;
        box-shadow: 0 0 0 0 transparent inset !important;
        color: var(--bs-body-color) !important;
    }
    input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active {
        -webkit-text-fill-color: var(--bs-body-color) !important;
    }
</style>
</head>
@endpush
@push('js_top')
{{-- CKEditor --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
@endpush
@push('js')
{{-- Sweet Alert 2 --}}
<script src="{{ asset('static/js/sweetalert2.min.js') }}" defer></script>
{{-- ====== Start Template ====== --}}
{{-- Popper JS --}}
<script src="{{ asset('static/js/plugins/popper.min.js') }}" defer></script>
{{-- Simple Bar JS --}}
<script src="{{ asset('static/js/plugins/simplebar.min.js') }}" defer></script>
{{-- Feather Icons JS --}}
<script src="{{ asset('static/js/plugins/feather.min.js') }}" defer></script>
{{-- Custom Template JS --}}
<script src="{{ asset('static/js/script.js') }}" defer></script>
{{-- ====== End Template ====== --}}
@endpush
