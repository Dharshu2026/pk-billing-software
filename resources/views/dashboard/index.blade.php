@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 fw-bold">Business Overview</h2>

    {{-- 1. Sales Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase small">Today's Sales</h6>
                    <h2 class="fw-bold">₹ {{ number_format($todaySales, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase small">Total Bills Generated</h6>
                    <h2 class="fw-bold">{{ $totalBills }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase small">Low Stock Alerts</h6>
                    <h2 class="fw-bold">{{ $lowStockItems->count() }} Items</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- {{-- 2. Low Stock Alert Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Low Stock Items (Less than 10)
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Item Name</th>
                        <th>Current Stock</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockItems as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td><span class="badge bg-danger">{{ $product->stock }} {{ $product->unit }}</span></td>
                        <td>₹ {{ number_format($product->price, 2) }}</td>
                        <td><a href="{{ route('inventory.index') }}" class="btn btn-sm btn-outline-dark">Restock</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-3 text-muted">All items are in good stock!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> -->
</div>
@endsection