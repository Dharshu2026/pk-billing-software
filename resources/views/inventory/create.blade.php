@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 col-md-6 mx-auto">
        <div class="card-header bg-success text-white">Add New Item</div>
        <div class="card-body">
            @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
        <strong>✔️ {{ session('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Item Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Unit</label>
                    <select name="unit" class="form-control">
                        <option value="Kgs">Kgs</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Box">Box</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Price (₹)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Save Item</button>
            </form>
        </div>
    </div>
</div>
@endsection