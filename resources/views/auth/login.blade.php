@extends('layouts.app')
@section('title','Login')
@section('content')
<div class="container py-5" style="max-width:440px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-calendar-event-fill text-primary" style="font-size:2.5rem"></i>
                <h4 class="fw-bold mt-2">Sign In to EventMS</h4>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Sign In</button>
                </div>
            </form>
            <hr>
            <div class="text-center small">
                New client? <a href="{{ route('register') }}">Create account</a> ·
                Organiser? <a href="{{ route('register.organiser') }}">Register as organiser</a>
            </div>
        </div>
    </div>
</div>
@endsection