<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tax Invoice - {{ $invoice->id }}</title>
<style>
    /* Exactly Half A4 Portrait */
    @media print {
        .page-break {
            display: block;
            page-break-before: always; /* Pudhu page-ku thallum */
        }
    }
    
    .print-container {
        min-height: 1000px; /* Oru page-oda height-ai fix pannikkonga */
    }
    
    @page { size: A4; margin: 0; }
    body { 
        font-family: Arial, Helvetica, sans-serif; 
        font-size: 11px; 
        margin: 0; 
        padding: 0; 
        color: #000; 
        background-color: #f0f0f0; /* Preview screen-la background gray-ah irukkum */
    }
    
    .invoice-box { 
        width: 210mm; 
        height: 148.5mm; /* Half A4 Height */
        padding: 8mm; 
        box-sizing: border-box; 
        display: flex; 
        flex-direction: column; 
        background-color: #fff; /* Bill matum white-ah theriyaum */
        margin: 20px auto; /* Bill-ai screen-oda centre-kku kondu varum */
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .top-gst-line { display: flex; justify-content: space-between; font-weight: bold; border: 1.5px solid #000; padding: 5px; margin-bottom: -1.5px; }
    .header-table { width: 100%; border-collapse: collapse; border: 1.5px solid #000; }
    .header-table td { padding: 8px; vertical-align: top; border-right: 1.5px solid #000; }
    .agency-name { font-size: 17px; font-weight: bold; text-transform: uppercase; margin: 0; }

    .info-bar { width: 100%; border-collapse: collapse; border: 1.5px solid #000; margin-top: -1.5px; }
    .info-bar td { padding: 5px 10px; border-right: 1.5px solid #000; font-size: 10px; }
    .info-bar td:last-child { border-right: none; }

    .main-table { width: 100%; border-collapse: collapse; flex-grow: 1; margin-top: -1.5px; border-bottom: 1.5px solid #000; }
    .main-table th { border: 1.5px solid #000; padding: 5px; text-align: center; background-color: #f2f2f2; }
    .main-table td { border-left: 1.5px solid #000; border-right: 1.5px solid #000; border-bottom: none; padding: 4px 6px; height: 22px; }

    .total-row td { border-top: 1.5px solid #000 !important; border-bottom: 1.5px solid #000; font-weight: bold; padding: 8px; }
    .words-section { border: 1.5px solid #000; border-top: none; padding: 6px; font-style: italic; font-size: 10px; }

    .footer-table { width: 100%; border-collapse: collapse; margin-top: auto; }
    .footer-table td { width: 50%; padding: 20px 10px 10px 10px; }
    
    .text-right { text-align: right; }
    .text-center { text-align: center; }

    /* Button area bottom-la theriyaara maadhiri */
    .print-actions { text-align: center; padding: 20px; background-color: #f0f0f0; }

    @media print { 
        .no-print, .print-actions { display: none; } 
        body { background-color: #fff; margin: 0; }
        .invoice-box { margin: 0; box-shadow: none; border-bottom: none; } 
    }
</style>
</head>

<body onload="setupPrint()">
@php
if (!function_exists('convertToWords')) {
    function convertToWords($number) {
        $no = (int)floor($number);
        if ($no == 0) return 'Zero';

        $words = array(
            '0' => '', '1' => 'One', '2' => 'Two', '3' => 'Three', '4' => 'Four', '5' => 'Five',
            '6' => 'Six', '7' => 'Seven', '8' => 'Eight', '9' => 'Nine', '10' => 'Ten',
            '11' => 'Eleven', '12' => 'Twelve', '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen', '18' => 'Eighteen',
            '19' => 'Nineteen', '20' => 'Twenty', '30' => 'Thirty', '40' => 'Forty',
            '50' => 'Fifty', '60' => 'Sixty', '70' => 'Seventy', '80' => 'Eighty', '90' => 'Ninety'
        );
        
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        $str = array();
        $i = 0;

        while ($i < 5 && $no > 0) {
            $divider = ($i == 1) ? 10 : 100;
            if ($i == 0) $divider = 100;

            $num = $no % $divider;
            $no = (int)($no / $divider);
            $i++;

            if ($num > 0) {
                $counter = $i - 1;
                // "and" logic for Indian currency format
                $hundred_and = ($counter == 0 && count($str) > 0) ? 'and ' : '';
                
                $temp = ($num < 21) 
                    ? $words[$num] . " " . $digits[$counter] 
                    : $words[(int)($num / 10) * 10] . " " . $words[$num % 10] . " " . $digits[$counter];
                
                array_push($str, $hundred_and . trim($temp));
            }
        }
        
        $result = implode(' ', array_reverse($str));
        return trim(str_replace('  ', ' ', $result));
    }
}
@endphp




@php 
    $itemsPerPage = 10; 
    $chunks = $invoice->items->chunk($itemsPerPage); // 10, 10-ah piriCHu chunk panrom
@endphp

@foreach($chunks as $pageIndex => $chunk)
<div class="invoice-box {{ !$loop->last ? 'page-break' : '' }}">
    <div class="top-gst-line">
        <span>GSTIN : 33BBHPM59561Z79</span>
        <span>TAX INVOICE - Page {{ $pageIndex + 1 }} of {{ $chunks->count() }}</span>
        <span style="font-weight: normal;">Original</span>
    </div>

    <table class="header-table">
        <tr>
            <td width="55%">
                <p class="agency-name">NEW P K AGENCY</p>
                <p style="margin: 3px 0;">175D/1/5, Sundaravel Puram West, Thoothukudi - 02</p>
                <p style="margin: 0;">Phone : 7010588158</p>
            </td>
            <td width="45%" style="border-right: none;">
                <strong>Billed To :</strong> {{ $invoice->customer_name }}<br>
                <strong>Address :</strong> {{ App\Models\Shop::where('shop_name',$invoice->customer_name)->first()->address ?? 'Thoothukudi' }}
            </td>
        </tr>
    </table>

    <table class="info-bar">
        <tr>
            <td width="33%"><strong>Invoice No :</strong>{{ $invoice->bill_number }}</td>
            <td width="33%"><strong>Dated :</strong> {{ date('d-m-Y', strtotime($invoice->created_at)) }}</td>
            <td width="34%"><strong>Place of Supply :</strong> Tamilnadu (33)</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">S.N</th>
                <th width="45%">Goods / Services Supplied</th>
                <th width="10%">Qty</th>
                <th width="10%">Unit</th>
                <th width="15%">Rate</th>
                <th width="15%">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chunk as $i => $item)
            <tr>
                {{-- Serial Number Logic: Next page-la 11, 12-nu start aagum --}}
                <td class="text-center">{{ ($pageIndex * $itemsPerPage) + ($i + 1) }}</td>
                <td>{{ $item->item_name }}</td>
                <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                <td class="text-center">{{ $item->unit }}</td>
                <td class="text-right">{{ number_format($item->price, 2) }}</td>
                <td class="text-right">{{ number_format($item->amount, 2) }}</td>
            </tr>
            @endforeach

            {{-- Empty rows to fill the table height --}}
            @for($j = count($chunk); $j < $itemsPerPage; $j++)
            <tr>
                <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            @endfor

            {{-- Total row: Kadaisi page-la mattum grand total theriyaum --}}
            @if($loop->last)
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL</td>
                <td class="text-right">₹ {{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($loop->last)
    <div class="words-section">
        Amount in Words : <strong>{{ convertToWords($invoice->total_amount) }} Rupees Only</strong>
    </div>
    @endif

    <table class="footer-table">
        <tr>
            <td style="text-align: left; vertical-align: bottom;">Receiver's Signature</td>
            <td style="text-align: right;">
                For <strong>NEW P K AGENCY</strong><br><br><br>
                Authorised Signatory
            </td>
        </tr>
    </table>
</div>
@endforeach
<div class="print-actions no-print">
    <button id="printBtn" onclick="window.print()" style="padding: 12px 40px; cursor:pointer; font-weight:bold; background:#0056b3; color:#fff; border:none; border-radius: 5px; font-size: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
        PRINT BILL 
    </button>
</div>
<script>
function setupPrint() {
    // 3. Focus the print button at the bottom
    const btn = document.getElementById('printBtn');
    if (btn) btn.focus();

    document.addEventListener('keydown', function(event) {
        if (event.key === "Enter") {
            window.print();
        }
    });


window.onafterprint = function() {
    // Unga 'Add Bill' route name 'invoices.create' nu irundha idha use pannunga
    window.location.href = "{{ route('invoices.create') }}"; 
};

// Mobile alladhu Tab-la redirect aagala-na, indha secondary timer-ai use pannalaam
setTimeout(function() {
    window.onafterprint = function() {
        window.location.href = "{{ route('invoices.create') }}";
    };
}, 500);

}
</script>

</body>
</html>