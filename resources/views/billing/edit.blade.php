@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark fw-bold">
         <span>MODIFY BILL NO: {{ $invoice->bill_number }}</span>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">BILL TO:</label>
                        <input type="text" name="customer_name" value="{{ $invoice->customer_name }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Item Description</th>
                                <th width="15%">Qty</th>
                                <th width="15%">Rate</th>
                                <th width="20%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- MUKKIYAM: Inga $invoice->items mattum dhaan use pannaNum --}}
                            @foreach($invoice->items as $index => $item)
                            <tr>
                                <td>
                                    <input type="text" name="items[{{ $index }}][name]" value="{{ $item->item_name }}" class="form-control border-0 bg-transparent" readonly>
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $index }}][qty]" value="{{ $item->quantity }}" class="form-control text-center qty-input" oninput="calculateRow(this)">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="items[{{ $index }}][rate]" value="{{ $item->price }}" class="form-control text-end rate-input" oninput="calculateRow(this)">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="items[{{ $index }}][amount]" value="{{ $item->amount }}" class="form-control text-end amount-input" readonly>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success px-5 fw-bold">UPDATE BILL & SYNC STOCK</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Qty alladhu Rate type panna udanae indha function call aagum
function calculateRow(input) {
    let row = input.closest('tr');
    let qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    let rate = parseFloat(row.querySelector('.rate-input').value) || 0;
    
    // 1. Individual row amount calculate panrom
    let amount = (qty * rate).toFixed(2);
    row.querySelector('.amount-input').value = amount;
    
    // 2. Motham Bill Total-aiyum update panrom
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.amount-input').forEach(input => {
        total += parseFloat(input.value || 0);
    });
    
    // Total theriya vendiya idathula update pannuvom
    if(document.getElementById('grandTotalText')) {
        document.getElementById('grandTotalText').innerText = '₹ ' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
    }
}</script>
@endsection