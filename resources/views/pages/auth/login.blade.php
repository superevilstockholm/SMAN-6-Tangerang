@extends('layouts.base')
@section('title', 'Masuk - SMAN 6 Tangerang')
@section('content')
<section class="vh-100">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-md-6 col-lg-4 py-4">
                <div class="card border-0 bg-transparent">
                    <div class="card-body p-0 m-0 d-flex flex-column align-items-center">
                        <img class="mb-3" style="width: 35%;" src="{{ asset('static/img/logo-sman6tng.png') }}" alt="Logo SMAN 6 Tangerang">
                        <h1 class="fw-semibold text-center fs-2 ff-inter">Selamat Datang</h1>
                        <p class="text-center fw-medium text-muted mb-3 ff-poppins">Silakan Masuk Untuk Mengakses Halaman Admin</p>
                        <form action="{{ route('login') }}" class="p-0 m-0 w-100" method="POST">
                            @csrf
                            <label for="email" class="mb-2">Alamat Email</label>
                            <input type="email" name="email" id="email" class="form-control mb-3" placeholder="Alamat Email" value="{{ old('email') }}" required>
                            <label for="password" class="mb-2">Kata Sandi</label>
                            <input type="password" name="password" id="password" class="form-control mb-3" placeholder="Kata Sandi" required>
                            <x-alerts :errors="$errors" class="mb-3"></x-alerts>
                            <button type="submit" class="btn btn-primary w-100">Masuk</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
