@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <h5 class="mb-0 fw-bold text-dark">INVENTORY MASTER</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="itemSearch" class="form-control border-start-0 bg-light" placeholder="Search by Item Name...">
                    </div>
                </div>
                <div class="col-md-3 text-end">
    <a href="{{ route('products.create') }}" class="btn btn-success btn-sm shadow-sm">
        <i class="bi bi-plus-lg"></i> Add New Item
    </a>
</div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="inventoryTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">S.N</th>
                            <th width="35%">Item Name</th>
                            <th width="15%">Unit</th>
                            <th width="15%" class="text-end">Rate (₹)</th>
                            <th width="15%" class="text-center">Stock</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                <tbody>
    @foreach($products as $index => $p)
    <tr id="row-{{ $p->id }}">
        {{-- S.N Column --}}
        <td class="text-muted">{{ $index + 1 }}</td>

        {{-- Item Name Column --}}
        <td>
            <span class="view-mode fw-bold">{{ $p->name }}</span>
            <input type="text" form="edit-form-{{ $p->id }}" name="name" class="form-control form-control-sm edit-mode d-none" value="{{ $p->name }}" required>
        </td>

        {{-- Unit Column --}}
        <td>
            <span class="view-mode badge bg-info text-dark">{{ $p->unit ?? 'Kgs' }}</span>
            <select form="edit-form-{{ $p->id }}" name="unit" class="form-control form-control-sm edit-mode d-none">
                <option value="Kgs" {{ $p->unit == 'Kgs' ? 'selected' : '' }}>Kgs</option>
                <option value="Pcs" {{ $p->unit == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                <option value="Box" {{ $p->unit == 'Box' ? 'selected' : '' }}>Box</option>
            </select>
        </td>

        {{-- Rate Column --}}
        <td class="text-end">
            <span class="view-mode fw-bold text-success">₹{{ number_format($p->price, 2) }}</span>
            <input type="number" step="0.01" form="edit-form-{{ $p->id }}" name="price" class="form-control form-control-sm edit-mode d-none text-end" value="{{ $p->price }}" required>
        </td>

        

{{-- Stock Column --}}
<td class="text-center">
    @if($p->stock < 10)
        {{-- Stock 10-kku kuraivaa irundha Warning Badge --}}
        <span class="badge bg-danger p-2 shadow-sm" title="Low Stock Alert!">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $p->stock }} {{ $p->unit }}
        </span>
    @else
        {{-- Stock sariyaa irundha Normal Badge --}}
        <span class="badge bg-secondary p-2">
            {{ $p->stock }} {{ $p->unit }}
        </span>
    @endif
</td>


        {{-- Actions Column (Buttons ippo inga dhaan varum) --}}
        <td class="text-center">
            <div class="view-mode d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm edit-btn">
                    <i class="bi bi-pencil"></i>
                </button>

                <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete panna virumbugireergala?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>

            <div class="edit-mode d-none">
                <button type="submit" form="edit-form-{{ $p->id }}" class="btn btn-success btn-sm px-3">SAVE</button>
                <button type="button" class="btn btn-secondary btn-sm cancel-btn">X</button>
            </div>
            
            {{-- Edit Form (Hidden) --}}
            
            <form action="{{ route('products.update', $p->id) }}" method="POST" id="edit-form-{{ $p->id }}" class="d-none">
                @csrf
                @method('PUT')
            </form>
        </td>
    </tr>
    @endforeach
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Search Logic
    const searchInput = document.getElementById('itemSearch');
    const table = document.getElementById('inventoryTable');
    const rows = table.querySelectorAll('tbody tr');
    const noDataMsg = document.getElementById('noDataMsg');

    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.toLowerCase();
        let found = 0;

        rows.forEach(row => {
            const itemName = row.cells[1].textContent.toLowerCase();
            if (itemName.includes(query)) {
                row.style.display = "";
                found++;
            } else {
                row.style.display = "none";
            }
        });

        if (found === 0) {
            table.classList.add('d-none');
            noDataMsg.classList.remove('d-none');
        } else {
            table.classList.remove('d-none');
            noDataMsg.classList.add('d-none');
        }
    });

    // 2. Inline Edit Logic
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            row.querySelectorAll('.view-mode').forEach(el => el.classList.add('d-none'));
            row.querySelectorAll('.edit-mode').forEach(el => el.classList.remove('d-none'));
        });
    });

    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            row.querySelectorAll('.view-mode').forEach(el => el.classList.remove('d-none'));
            row.querySelectorAll('.edit-mode').forEach(el => el.classList.add('d-none'));
        });
    });
});
</script>

<style>
    /* Prevent sidebar collapse from hiding buttons */
    .table-responsive {
        border-radius: 0 0 8px 8px;
    }
    .edit-mode .form-control-sm {
        height: 30px;
        padding: 2px 5px;
    }
    /* Smooth hover effect */
    tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    /* Table Compact Look */
.busy-table { 
    border: 1px solid #dee2e6 !important; 
    font-size: 13px; /* Chinna font-naala professional-ah theriyaum */
}
.busy-table thead th {
    background-color: #f8f9fa !important;
    color: #333 !important;
    font-weight: 700;
    text-transform: uppercase;
    padding: 8px !important;
}
.busy-table td { 
    padding: 6px 10px !important; 
    border-bottom: 1px solid #eee !important;
}

/* Row Hover Effect */
.busy-table tbody tr:hover {
    background-color: #fff9c4 !important; /* Yellow highlight on hover like Busy */
    transition: 0.2s;
}

/* Action Buttons UI */
.btn-icon {
    padding: 2px 6px;
    font-size: 12px;
    border-radius: 4px;
}
</style>
@endsection