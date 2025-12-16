@extends('App')
@section('layout')
    @if ($meta['showNavbar'] ?? true)
        <x-navbar></x-navbar>
    @endif
    <main>
        @yield('content')
    </main>
    @if ($meta['showFooter'] ?? true)
        <x-footer></x-footer>
    @endif
@endsection
@push('css')
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
@endpush
