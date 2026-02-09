
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold">BILL HISTORY</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="historyTable">
                    <thead class="table-light">
                        <tr>
                            <th>Bill No</th>
                            <th>Date</th>
                            <th>Shop Name</th>
                            <th>Total Amount</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>

                            <td> BILL NO: {{ $invoice->bill_number }}</td>
                            <td>{{ date('d-m-Y', strtotime($invoice->created_at)) }}</td>
                            <td>{{ $invoice->customer_name }}</td>
                            <td>₹ {{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="text-center">
                                {{-- Edit button loop-kulla sariyaana row-la irukku --}}
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm fw-bold">
                                    <i class="bi bi-pencil-square"></i> EDIT
                                </a>
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm text-white fw-bold ms-1">
                                    <i class="bi bi-printer"></i> PRINT
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection