@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 col-md-6 mx-auto">
        <div class="card-header bg-primary text-white">Add New Shop</div>
        <div class="card-body">@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
        <strong>✔️ {{ session('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
            <form action="{{ route('shops.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Shop Name</label>
                    <input type="text" name="shop_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Save Shop</button>
            </form>
        </div>
    </div>
</div>
@endsection