@extends('layouts.guest')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow border-0 p-4" style="width: 400px; border-top: 5px solid #0056b3 !important;">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary">NEW P K AGENCY</h4>
            <p class="text-muted small">Billing System Login</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="admin@pkagency.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold">SIGN IN</button>
        </form>
    </div>
</div>
@endsection