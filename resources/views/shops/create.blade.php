@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 col-md-6 mx-auto">
        <div class="card-header bg-primary text-white fw-bold">Add New Shop (B2B Access)</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>✔️ {{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('shops.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Shop Name</label>
                    <input type="text" name="shop_name" class="form-control" placeholder="e.g. Selvam Stores" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Address / Area</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="e.g. Sundaravel Puram"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" placeholder="70105xxxxx">
                </div>

                <hr>
                <p class="text-primary small fw-bold">Login Details for Shop Order App</p>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Login Email</label>
                    <input type="email" name="email" class="form-control" placeholder="shopname@gmail.com">
                    <div class="form-text" style="font-size: 10px;">Indha email vachu dhaan shop-kaaranga login panna mudiyum.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Set Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Min 6 characters">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold">Save Shop & Enable App Access</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection