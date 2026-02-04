@extends('layouts.app')

@section('content')
<style>
    /* Busy Software Style UI */
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }


    .bg-busy-highlight {
        background-color: #fff9c4 !important; /* Busy Style Yellow */
        border: 1px solid #0056b3;
        font-weight: bold;
        color: #000;
    }
    .busy-table { border: 2px solid #0056b3; }
    .busy-table thead th {
        background-color: #0056b3 !important;
        color: white !important;
        font-size: 12px;
        text-transform: uppercase;
        border: 1px solid #ffffff;
    }
    .busy-table td { padding: 2px !important; border: 1px solid #ccc !important; position: relative; }
    .form-control-sm { border-radius: 0px; border: 1px solid #999; }
    .form-control:focus { 
        background-color: #fff9c4 !important; /* Yellow focus like Busy */
        border: 2px solid #0056b3 !important;
        box-shadow: none;
    }
    
    /* Custom Dropdown Styling */
    .custom-dropdown {
        position: absolute;
        z-index: 1000;
        background: white;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #0056b3;
        display: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .dropdown-item-custom {
        padding: 8px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        font-size: 13px;
        color: #333;
    }
    .dropdown-item-custom:hover { background-color: #fff9c4; }
</style>

<div class="container py-4">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 1000px;">
        <div class="card-body p-4">
            <div class="text-center mb-4 border-bottom pb-3">
                <h4 class="fw-bold text-primary">NEW P K AGENCY</h4>
                <p class="mb-0 small text-muted">175D/1/4, Sundaravel Puram West, Thoothukudi - 628 002</p>
                <p class="mb-0 small"><b>GSTIN: 33BBHPM59561Z79</b></p>
            </div>

            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6" style="position: relative;">
                        <label class="form-label fw-bold text-secondary small">BILL TO (PARTY NAME):</label>
                        <input type="text" autocomplete="off" name="customer_name" class="form-control form-control-sm shadow-sm party-search" placeholder="Type to search party..." required>
                    
<div class="custom-dropdown party-results">
    @foreach($shops as $shop)
        {{-- Area details-ai bracket-la sethurukkom --}}
        <div class="dropdown-item-custom" data-value="{{ $shop->shop_name }}">
            <b>{{ $shop->shop_name }}</b> <span class="text-muted small">({{ $shop->address }})</span>
        </div>
    @endforeach
</div>
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="mb-0 small"><b>Date:</b> {{ date('d-M-Y') }}</p>
                    </div>
                </div>

                <div class="table-responsive">
    <table class="table table-bordered busy-table" id="billTable">
        <thead>
            <tr class="text-center">
                <th width="5%">S.N</th>
                <th width="45%">ITEM NAME</th>
                <th width="10%">QTY</th>
                <th width="10%">UNIT</th>
                <th width="15%">RATE</th>
                <th width="15%">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < 5; $i++) {{-- Start with 5 empty rows --}}
            <tr>
                <td class="text-center bg-light small fw-bold">{{ $i + 1 }}</td>
                <td>
                    <input type="text" autocomplete="off" name="items[{{ $i }}][name]" class="form-control form-control-sm item-select-input border-0" placeholder="Search item...">
                    <div class="custom-dropdown item-results">
                        @foreach($products as $p)
                            <div class="dropdown-item-custom" 
                                 data-name="{{ $p->name }}" 
                                 data-price="{{ $p->price }}" 
                                 data-unit="{{ $p->unit ?? 'Kgs' }}">
                                {{ $p->name }}
                            </div>
                        @endforeach
                    </div>
                </td>
                <td><input type="number" step="0.01" name="items[{{ $i }}][qty]" class="form-control form-control-sm border-0 text-center qty"></td>
                <td><input type="text" name="items[{{ $i }}][unit]" class="form-control form-control-sm border-0 text-center item-unit" readonly></td>
                <td><input type="number" step="0.01" name="items[{{ $i }}][rate]" class="form-control form-control-sm border-0 text-end rate"></td>
                <td><input type="number" step="0.01" name="items[{{ $i }}][amount]" class="form-control form-control-sm border-0 text-end amount" readonly></td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>

                <div class="d-flex justify-content-between align-items-start mt-3">
                    <button type="button" class="btn btn-link btn-sm text-decoration-none p-0" onclick="addRow()">+ Add More Items</button>
                    <div class="text-end">
                        <div class="d-flex justify-content-end gap-3 mb-2">
                            <span class="text-muted"></span>
                            <h4 class="fw-bold text-primary"><span class="total-amount-display"></span></h4>
                        </div>
                        <input type="hidden" name="total_amount" id="total_amount_input">
                        <button type="submit" class="btn btn-primary px-5 shadow-sm fw-bold">GENERATE & PRINT BILL</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // 1. Search Filter Logic (Updated with Area search)
    $(document).on('input', '.party-search, .item-select-input', function() {
        let query = $(this).val().toLowerCase().trim();
        let resultsBox = $(this).siblings('.custom-dropdown');
        
        if(query.length > 0) {
            resultsBox.show();
            let items = resultsBox.find('.dropdown-item-custom');
            let hasMatch = false;

            items.each(function() {
                let text = $(this).text().toLowerCase(); 
                if(text.includes(query)) {
                    $(this).show();
                    hasMatch = true;
                } else {
                    $(this).hide();
                }
            });

            if(!hasMatch) { resultsBox.hide(); }
            items.removeClass('bg-busy-highlight');
            resultsBox.find('.dropdown-item-custom:visible').first().addClass('bg-busy-highlight');
        } else {
            resultsBox.hide();
        }
    });

    // 2. Selection logic (Party & Items)
    $(document).on('click', '.party-results .dropdown-item-custom', function() {
        $(this).closest('.col-md-6').find('.party-search').val($(this).data('value'));
        $(this).parent().hide();
        $('.item-select-input').first().focus();
    });

  $(document).on('click', '.item-results .dropdown-item-custom', function() {
    let row = $(this).closest('tr');
    
    row.find('.item-select-input').val($(this).data('name'));
    row.find('.item-unit').val($(this).data('unit'));
    
    // Price-ai check pannuvom
    let productPrice = parseFloat($(this).data('price')) || 0;
    
    if (productPrice > 0) {
        row.find('.rate').val(productPrice.toFixed(2));
    } else {
        // Price illana 0.00-ku pathila empty-ah viduvom
        row.find('.rate').val(''); 
    }
    
    $(this).parent().hide();
    row.find('.qty').focus(); 
    calculateRow(row);
});

    // 3. Calculation Logic
    $(document).on('input', '.rate, .qty', function() { calculateRow($(this).closest('tr')); });

    // Calculation logic-layum chinna change
function calculateRow(row) {
    let qty = parseFloat(row.find('.qty').val()) || 0;
    let rate = parseFloat(row.find('.rate').val()) || 0;
    let total = qty * rate;

    // Amount 0-va irundha blank-ah vai
    row.find('.amount').val(total > 0 ? total.toFixed(2) : ''); 
    calculateGrandTotal();
}
    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.amount').each(function() { grandTotal += parseFloat($(this).val()) || 0; });
        $('.total-amount-display').html(grandTotal > 0 ? 'Total: ₹' + grandTotal.toFixed(2) : '');
        $('#total_amount_input').val(grandTotal.toFixed(2));
    }

    // 4. Outside click and Keyboard
    $(document).click(function(e) {
        if (!$(e.target).closest('.party-search, .item-select-input, .custom-dropdown').length) {
            $('.custom-dropdown').hide();
        }
    });
$(document).on('keydown', 'input', function(e) {
    let inputs = $(this).closest('form').find(':input:visible:not([readonly])');
    let index = inputs.index(this);

    if (e.which === 13) { 
        let resultsBox = $(this).siblings('.custom-dropdown');
        
        if (resultsBox.is(':visible')) {
            // Dropdown theriunjaa, highlight aana item-ai select pannu
            resultsBox.find('.dropdown-item-custom.bg-busy-highlight').click();
            e.preventDefault();
        } else {
            
            e.preventDefault(); 
            inputs.eq(index + 1).focus();
        }
    }
});
    // 5. Add Row Logic
    function addRow() {
        let table = document.getElementById('billTable').getElementsByTagName('tbody')[0];
        let rowCount = table.rows.length;
        let row = table.insertRow(rowCount);
        row.innerHTML = `
            <td class="text-center bg-light small fw-bold">${rowCount + 1}</td>
            <td>
                <input type="text" autocomplete="off" name="items[${rowCount}][name]" class="form-control form-control-sm item-select-input border-0" placeholder="Search item...">
                <div class="custom-dropdown item-results">
                    @foreach($products as $p)
                        <div class="dropdown-item-custom" data-name="{{ $p->name }}" data-price="{{ $p->price }}" data-unit="{{ $p->unit ?? 'Kgs' }}">
                            {{ $p->name }}
                        </div>
                    @endforeach
                </div>
            </td>
            <td><input type="number" name="items[${rowCount}][qty]" class="form-control form-control-sm border-0 text-center qty"></td>
            <td><input type="text" name="items[${rowCount}][unit]" class="form-control form-control-sm border-0 text-center item-unit" readonly></td>
            <td><input type="number"  name="items[${rowCount}][rate]" class="form-control form-control-sm border-0 text-end rate"></td>
            <td><input type="number" step="0.01" name="items[${rowCount}][amount]" class="form-control form-control-sm border-0 text-end amount" readonly></td>
        `;
    }
</script>

{{-- 6. Print Popup (Indha block kandaipa thaniya dhaan irukanum) --}}
@if(session('print_id'))
<script>
    window.addEventListener("load", function() {
        var printUrl = "{{ route('invoices.show', session('print_id')) }}";
        var printWindow = window.open(printUrl, '_blank'); 
        if (printWindow) {
            printWindow.onload = function() { printWindow.print(); };
        } else {
            alert("Please allow pop-ups for this site to print the bill.");
        }
    });
</script>
@endif
@endsection