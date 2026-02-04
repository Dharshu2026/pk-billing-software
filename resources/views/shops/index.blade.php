@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-4"><h5 class="mb-0 fw-bold text-primary">SHOP DIRECTORY (B2B)</h5></div>
                <div class="col-md-5">
                    <input type="text" id="shopSearch" class="form-control bg-light" placeholder="Search shop name, area or email...">
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('shops.create') }}" class="btn btn-primary btn-sm">+ Add New Shop</a>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="shopTable">
                    <thead class="table-light">
                        <tr>
                            <th width="20%">Shop Name</th>
                            <th width="25%">Address / Area</th>
                            <th width="15%">Phone</th>
                            <th width="25%">Login Email</th> {{-- Pudhu Column --}}
                            <th width="15%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shops as $shop)
                        <tr id="shop-row-{{ $shop->id }}">
                            <form action="{{ route('shops.update', $shop->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td>
                                    <span class="view-mode fw-bold text-dark">{{ $shop->shop_name }}</span>
                                    <input type="text" name="shop_name" class="form-control form-control-sm edit-mode d-none" value="{{ $shop->shop_name }}" required>
                                </td>
                                <td>
                                    <span class="view-mode small text-muted">{{ $shop->address }}</span>
                                    <textarea name="address" class="form-control form-control-sm edit-mode d-none" rows="2">{{ $shop->address }}</textarea>
                                </td>
                                <td>
                                    <span class="view-mode">{{ $shop->phone }}</span>
                                    <input type="text" name="phone" class="form-control form-control-sm edit-mode d-none" value="{{ $shop->phone }}">
                                </td>
                                <td>
                                    {{-- Email view/edit mode --}}
                                    <span class="view-mode small">{{ $shop->email ?? '--- No Login ---' }}</span>
                                    <input type="email" name="email" class="form-control form-control-sm edit-mode d-none" value="{{ $shop->email }}" placeholder="Enter email">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn view-mode">
                                        Edit
                                    </button>
                                    <div class="edit-mode d-none">
                                        <button type="submit" class="btn btn-sm btn-success shadow-sm mb-1">Save</button>
                                        <button type="button" class="btn btn-sm btn-danger cancel-btn shadow-sm">X</button>
                                    </div>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Script logic (Already neenga vachurukka adhae logic dhaan) --}}
<script>
    // Edit Toggle
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let row = this.closest('tr');
            row.querySelectorAll('.view-mode').forEach(el => el.classList.add('d-none'));
            row.querySelectorAll('.edit-mode').forEach(el => el.classList.remove('d-none'));
        });
    });

    // Cancel Toggle
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let row = this.closest('tr');
            row.querySelectorAll('.view-mode').forEach(el => el.classList.remove('d-none'));
            row.querySelectorAll('.edit-mode').forEach(el => el.classList.add('d-none'));
        });
    });

    // Smart Search (Now searches email too)
    document.getElementById('shopSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#shopTable tbody tr");
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>
@endsection