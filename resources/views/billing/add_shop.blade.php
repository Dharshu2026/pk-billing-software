@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        ✔️ {{ session('success') }}
    </div>
@endif
<div class="container">
    <div class="card shadow border-0 col-md-8 mx-auto">
        <div class="card-header bg-success text-white">Register New Shop / Customer</div>
        <div class="card-body">
            <form action="/add-shop" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Shop Name</label>
                    <input type="text" name="shop_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Address / Area</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <button type="submit" class="btn btn-success w-100">Save Shop Details</button>
            </form>
        </div>
    </div>
</div>
@endsection